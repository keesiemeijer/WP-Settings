<?php
/**
 * Class for registering settings and sections and for display of the settings form(s).
 * For detailed instructions see: https://github.com/keesiemeijer/WP-Settings
 *
 * @version 0.1
 *
 * @author keesiemeijer
 */

if ( !class_exists( 'WP_Settings_Demo_Settings' ) ) {
	class WP_Settings_Demo_Settings {

		/**
		 * Version of WP_Settings_Demo_Settings class
		 */
		public $version = 0.1;

		/**
		 * Current settings page.
		 */
		public $current_page = array();

		/**
		 * Debug errors and notices.
		 */
		public $debug = '';

		/**
		 * Generic error message.
		 */
		public $error_msg = '';

		/**
		 * Admin pages array.
		 */
		private $pages;

		/**
		 * Settings fields.
		 */
		private $fields;

		/**
		 * Page hook of current settings page.
		 */
		private $page_hook;

		/**
		 * Page title count (for tabbed settings pages).
		 */
		private $page_title_count = 0;

		/**
		 * Multiple forms on one settings page.
		 */
		private $multiple_forms = false;

		/**
		 * validation of admin pages and fields arrays.
		 */
		private $valid_pages = false;


		/**
		 * Setup of admin page.
		 * Registers settings using the WorPres settings Api.
		 *
		 * @uses WP_Settings_Demo_Form_Fields class
		 *
		 * @param array $fields Page parameters array.
		 * @param array $fields Form fields parameters array.
		 * @param string $page_hook Unique plugin admin page hook suffix
		 */
		protected function settings_admin_init( $pages, $fields, $page_hook = '' ) {

			$this->pages = (array) $pages;
			$this->fields = (array) $fields;
			$this->page_hook = trim( sanitize_title( (string) $page_hook ) );

			// debug strings don't use Gettext functions for translation.
			if ( !class_exists( 'WP_Settings_Demo_Form_Fields' ) )
				$this->debug  .= "Error: class WP_Settings_Demo_Form_Fields doesn't exist<br/>";

			if ( '' == $page_hook )
				$this->debug  .= "Error: parameter 'page_hook' not provided in settings_admin_init()<br/>";

			$this->debug = apply_filters( 'wp_settings_demo_debug', $this->debug, $pages, $fields, $page_hook );

			if ( '' !== $this->debug )
				return $this->valid_pages = false; // don't show the form and navigation

			// passed validation
			$this->valid_pages = true;
			$page = $this->get_current_admin_page( $this->pages );

			foreach ( $page['sections'] as $sections ) {

				$section_description = '__return_false';
				if ( isset( $sections['desc'] ) && $sections['desc'] ) // optional
					$section_description = array( $this, 'render_section_description' );

				$title = ( isset( $sections['title'] ) ) ? $sections['title'] : ''; // optional
				$page_id = ( $this->multiple_forms ) ? $sections['id'] : $page['id'];
				$page_id = $page_hook . '_' . $page_id;
				$sections_id = $page_hook . '_' . $sections['id'];
				$this->debug .= ( '' === $this->debug ) ? 'Database option(s) created for this page:<br/>' : '';
				$this->debug .= "database option: " . $sections_id . '<br/>'; // database option name

				$use_defaults = false;
				if ( false === get_option( $sections_id ) )
					$use_defaults = true;

				add_settings_section( $sections_id, $title, $section_description, $page_id );

				if ( isset( $this->fields[ $sections['id'] ] ) ) {

					$option_defaults = array();

					foreach ( $this->fields[ $sections['id'] ] as $option ) {

						$defaults = array(
							'section'      => $sections_id,
							'id'           => '',
							'type'         => '',
							'label'        => '',
							'desc'         => '',
							'size'         => false,
							'options'      => '',
							'default'      => '',
							'content'      => '',
							'attr'         => false,
							'before'       => '',
							'after'        => '',
							'use_defaults' => $use_defaults,
						);

						$args = wp_parse_args( $option, $defaults );

						if ( $use_defaults && ( $args['type'] != 'field_row' ) )
							$option_defaults[ $args['id'] ] = $args['default'];

						if ( $use_defaults && ( $args['type'] == 'field_row' ) ) {
							foreach ( (array) $args['fields'] as $field ) {
								if ( isset( $field['default'] ) )
									$option_defaults[ $field[ 'id'] ] = $field['default'];
								else
									$option_defaults[ $field[ 'id'] ] = '';
							}
						}

						$args['default'] = ( $use_defaults ) ? $args['default'] :'';

						// add the fields
						$form_fields =  new WP_Settings_Demo_Form_Fields( get_settings_errors() );
						if ( method_exists( $form_fields, 'callback_' . $option['type']  ) ) {
							add_settings_field(
								$sections_id . '[' . $option['id'] . ']',
								$args['label'],
								array( $form_fields, 'callback_' . $option['type'] ),
								$page_id,
								$sections_id,
								$args
							);
						}
					}
					// Need to add the option else settings validation errors show twice on first submit.
					if ( $use_defaults )
						add_option( $sections_id, $option_defaults );
				}
			}

			// Register all the settings.
			foreach ( $pages as $page ) {
				foreach ( $page['sections'] as $section ) {

					// Use section ids for multiple forms.
					if ( isset( $page['multiform'] ) && $page['multiform'] )
						$page['id'] = ( count( $page['sections'] )  > 1 ) ? $section['id'] : $page['id'];

					$page_id = $page_hook . '_' . $page['id'];
					$sections_id = $page_hook . '_' . $section['id'];

					if ( isset( $section['validate_callback'] ) && $section['validate_callback'] )
						register_setting( $page_id, $sections_id, array( $this, $section['validate_callback'] ) );
					else
						register_setting( $page_id, $sections_id );
				}
			}
		} // admin_init()


		/**
		 * Returns the current settings page.
		 *
		 * @param array $admin_pages. Array of settings pages.
		 * @return array Current settings page.
		 */
		public function get_current_admin_page( $admin_pages ) {

			foreach ( (array) $admin_pages as $page ) {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					if ( ( $_GET['tab'] == $page['id'] ) || ( $_GET['tab'] == $page['slug'] ) )
						$this->current_page = $page;
				}

				if ( isset( $page['title'] ) && $page['title'] )
					++$this->page_title_count;
			}

			// Set the first settings page as current if it's not a tab.
			if ( empty( $this->current_page ) )
				$this->current_page = $admin_pages[0];

			if ( isset( $this->current_page['multiform'] ) && $this->current_page['multiform'] )
				$this->multiple_forms = ( count( $this->current_page['sections'] )  > 1 ) ? true : false;

			return $this->current_page;
		}


		/**
		 * Display the description of a section.
		 *
		 * @param array $section Description of section
		 */
		public function render_section_description( $section ) {
			foreach ( $this->current_page['sections'] as $setting ) {
				if ( $this->page_hook . '_' . $setting['id'] == $section['id'] )
					echo $setting['desc'];
			}
		}


		/**
		 * Display Plugin Title and if needed tabbed navigation.
		 *
		 * @param string $plugin_title Plugin title.
		 * @param string $tab_id Page id. Sets tab to active tab.
		 */
		protected function render_header( $plugin_title = '', $tab_id = false ) {

			if ( !empty( $plugin_title ) )
				echo get_screen_icon() . '<h2>' . (string) $plugin_title . '</h2>';

			if ( !$this->valid_pages )
				return;

			$html = '';
			$current = $this->current_page;
			$page_ids = wp_list_pluck( $this->pages, 'id' );
			$cur_tab_id = ( $tab_id ) ? (string) $tab_id : $current['id'];
			$cur_tab_id = ( in_array( $cur_tab_id, $page_ids ) ) ? $cur_tab_id : $current['id'];
			$i = 0;

			foreach ( $this->pages as $page ) {

				if ( ( isset( $page['title'] ) && $page['title'] ) ) {
					if ( $this->page_title_count  > 1 ) {
						$html .= ( 0 == $i ) ? '<h3 class="nav-tab-wrapper">' : '';

						$active = '';
						if ( $cur_tab_id == $page['id'] )
							$active = ' nav-tab-active';

						// Get the url of the current settings page.
						$tab_url = remove_query_arg( array( 'tab', 'settings-updated' ) );

						// Add query arg 'tab' if it's not the first settings page.
						if ( $this->pages[0]['id'] != $page['id'] )
							$tab_url = add_query_arg( 'tab', $page['slug'], $tab_url );

						$html .= sprintf(
							'<a href="%1$s" class="nav-tab%2$s" id="%3$s-tab">%4$s</a>',
							esc_url( $tab_url ),
							$active,
							esc_attr( $page['id'] ),
							$page['title']
						);

						$html .= ( ++$i == $this->page_title_count ) ? '</h3>' : '';
					}

					if ( $this->page_title_count == 1 ) {
						if ( isset( $current['title'] ) && $current['title'] == $page['title'] ) {
							$html .= '<h3>' . $page['title'] . '</h3>';
							break;
						}
					}

				}
			}

			echo $html;
		} // render_header()


		/**
		 * Displays the form(s) and sections.
		 */
		protected function render_form( $form = '' ) {

			if ( !$this->valid_pages )
				return;

			$page = $this->current_page;
			if ( !empty( $page ) ) {
				$forms = ( $this->multiple_forms ) ? $page['sections'] : array( $page );

				foreach ( $forms as $form ) {
					echo '<form method="post" action="options.php">';
					// lets you add additional fields
					echo apply_filters( 'wp_settings_demo_form', '', $form['id'] );

					settings_fields( $this->page_hook . '_' . $form['id'] );
					do_settings_sections( $this->page_hook . '_' . $form['id'] );

					$submit = ( isset( $form['submit'] ) && $form['submit'] ) ? $form['submit'] : '';

					if ( ( $submit == '' ) && isset( $page['submit'] ) && $page['submit'] )
						$submit = $page['submit'];

					$text = isset( $submit['text'] ) ? $submit['text'] : null;
					$type = isset( $submit['$type'] ) ? $submit['text'] : 'primary';
					$name = isset( $submit['$name'] ) ? $submit['name'] : 'submit';
					$other_attributes = ( $this->multiple_forms ) ? array( 'id' => $form['id'] ) : null;

					submit_button( $text, $type, $name, true, $other_attributes );
					echo '</form>';
				}
			}
		} // render_form()


	} // class
} // class exists