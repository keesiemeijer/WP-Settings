<?php
/**
 *
 * For detailed instructions see: https://github.com/keesiemeijer/WP-Settings
 * 
 * !important
 * Do a search and replace for 'WP_Settings'. 
 * Replace it with your own plugin or theme name.
 * 
 * Class names should use capitalized words separated by underscores. 
 * Any acronyms should be all upper case.
 *
 * !important
 * Do a search and replace for 'wp_settings'. 
 * Replace it with your own plugin or theme name. 
 * Words should be lower case separated by underscores.
 *
 * !important
 * Do a search and replace for 'wp-settings'.
 * Replace it with your own plugin or theme text domain.
 * Words should be lower case and separated by dashes.
 * 
 */

/**
 * Basic validation for pages and field arrays.
 * Adds a settings error if the admin page didn't pass validation.
 * Include this file after the 'WP_Settings_Settings' class is included.
 * Only include this class in development code.
 *
 * @uses Uses the 'wp_settings_debug' filter from the 'WP_Settings_Settings' class.
 * For detailed instructions see: https://github.com/keesiemeijer/WP-Settings
 *
 * @version 0.1
 * @author keesiemeijer
 */
if ( !class_exists( 'WP_Settings_Debug' ) ) {
	class WP_Settings_Debug {
		
		var $version = 0.1;
		var $debug;
		var $fields;
		var $pages;
		var $page_hook;

		public function __construct() {
			if ( class_exists( 'WP_Settings_Settings' ) ) {
				add_action( 'admin_notices', array( $this, 'doing_it_wrong' ) );
				add_filter( 'wp_settings_debug', array( $this, 'is_valid_admin_page' ), 10, 4 );
			}
		}


		/**
		 * Displays a settings error if the pages or fields array didn't pass validation.
		 * 
		 * called on the 'admin_notices' action hook 
		 */
		public function doing_it_wrong() {

			$screen = get_current_screen();
			if ( $screen->id == $this->page_hook ) {
				$page = $this->is_valid_admin_page( '', $this->pages, $this->fields );
				if ( '' != $page ) {
					$error_msg = __( 'Error: Plugin could not be initialized.', 'wp-settings' );
					add_settings_error( 'invalid', 'text', $error_msg, 'error' );
					// use $this->debug from the 'WP_Settings_Settings' class to show more error information. 					
					// trigger an error if WP_DEBUG is set to true
					// trigger_error( $page ); 
				}
			}
		}


		/**
		 * Basic validation of required values for pages, sections and fields.
		 * Checks if page ids, page slugs, section ids are unique.
		 * Checks required fields for form fields
		 *
		 * @param string $debug String with debug messages
		 * @param array $pages Page parameters array.
		 * @param array $fields Form field parameters array.
		 * @param string $pages Page hook for current admin page.
		 * @return boolean
		 */
		public function is_valid_admin_page( $debug = '', $pages = '', $fields = '', $page_hook = '' ) {

			$this->debug = $debug;
			$this->fields = $fields;
			$this->pages = $pages;
			$this->page_hook = $page_hook;

			// debug strings don't use Gettext functions for translation.
			if ( empty( $pages ) || empty( $fields ) )
				return 'Error: no pages or fields found';

			$page_ids    = array();
			$page_slugs  = array();
			$section_ids = array();
			$notice = '';
			$br = '<br/>';
			$err = "missing, empty or wrong type";

			foreach ( (array) $pages as $key => $page ) {
				foreach ( array( 'id', 'slug' ) as $required ) {
					if ( !$this->valid_var( $page, $required ) )
						return "page 'id', 'slug' or 'sections' not provided";
				}
				if ( !$this->valid_var( $page, 'sections', 'array' ) )
					return "'sections' not provided for page: '{$page['id']}'";

				if ( in_array( $page['id'], $page_ids ) )
					return "page id is not unique: '{$page['id']}'";
				$page_ids[] = $page['id'];

				if ( in_array( $page['slug'], $page_slugs ) )
					return "page 'slug' is not unique: '{$page['slug']}'";
				$page_slugs[] = $page['slug'];

				foreach ( (array) $page['sections'] as $section ) {

					if ( !$this->valid_var( $section, 'id' ) )
						return "section 'id' $err in page: '{$page['id']}'";
					if ( in_array( $section['id'], $section_ids ) )
						return "duplicate section 'id': '{$section['id']}'";
					if ( !in_array( $section['id'], array_keys( (array) $fields ) ) )
						return "section 'id' not in fields array: '{$section['id']}'";

					$section_ids[] = $section['id'];
					$field_ids = array(); // reset field ids for new section.
					foreach ( (array) $fields[$section['id']] as $val => $form_field ) {

						if ( isset( $form_field['type'] ) && $form_field['type'] == 'field_row' ) {
							if ( !$this->valid_var( $form_field, 'fields', 'array' ) ) {
								$error = "'fields' parameter $err for 'field_row' in section: '";
								return $error . $section['id']. "'";
							}
							$all_fields = (array) $form_field['fields'];
						} else {
							$all_fields = array( $form_field );
						}

						foreach ( (array) $all_fields as $field ) {
							foreach ( array( 'id', 'type' ) as $req ) {
								if ( !$this->valid_var( $field, $req ) )
									return "field '$req' parameter $err in section: '{$section['id']}'";
							}

							if ( in_array( $field['id'], $field_ids ) ) {
								$error = "field 'id' duplicate of '{$field['id']}'";
								return $error . "' found in section: '{$section['id']}'";
							}
							$field_ids[] = $field['id'];

							if ( in_array( $field['type'], array( 'radio', 'select', 'multicheckbox' ) ) ) {
								if ( !$this->valid_var( $field, 'options', 'array' ) ) {
									$error = "parameter 'options' $err for field 'radio',";
									$error .= " 'select' or 'multicheckbox' in field '{$field['id']}'";
									return $error . " in section '{$section['id']}'";
								}
							}

						}
					}
				}
			}
			// make sure section ids are unique
			foreach ( $section_ids as $section ) {
				if ( in_array( $section,  $page_ids ) )
					return "section 'id' duplicate of page id: " . $section ;
			}

			return '';
		} // is_valid_admin_page()


		/**
		 * Checks if variable exists, is of the correct type and is not empty.
		 *
		 * @param array $var Array.
		 * @param string $key Array key.
		 * @param string $key type to check, string or array.
		 * @return boolean
		 */
		function valid_var( $var, $key, $type = 'string' ) {
			if ( !is_array( $var ) )
				return false;

			if ( !( isset( $var[$key] ) && $var[$key] ) )
				return false;

			if ( $type == 'string' ) {
				if ( !is_string( $var[$key] ) )
					return false;
				if ( '' == trim( $var[$key] ) )
					return false;
			}

			if ( $type == 'array' ) {
				if ( !is_array( $var[$key] ) )
					return false;
				if ( empty( $var[$key] ) )
					return false;
			}
			return true;
		}

	}
	$debug = new WP_Settings_Debug();
}
?>