<?php
/*
* Plugin Name: WP Settings Demo
* Version: 0.1
* Plugin URI: https://github.com/keesiemeijer/WP-Settings/tree/master/wp-settings
* Author: keesiemeijer
* Description: This is a demo plugin for the WP Settings classes.
*/

if ( is_admin() ) {

	// Include the form fields class 'WP_Settings_Form_Fields'.
	require_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-settings-demo-form-fields.php';

	// Include the settings class 'WP_Settings_Settings'.
	require_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-settings-demo-settings.php';

	// Include the debug class 'WP_Settings_Demo_Debug' (only needed when developing).
	// require_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-settings-demo-debug.php';

}


/**
 * Class creates four admin settings pages.
 * single form fields
 * multiple form fields,
 * multiple settings sections
 * multiple forms.
 *
 * @since 0.1
 */
if ( !class_exists( 'WP_Settings_Demo' ) && class_exists( 'WP_Settings_Demo_Settings' ) ) {

	class WP_Settings_Demo extends WP_Settings_Demo_Settings {

		/**
		 * Arguments used by add_submenu_page()
		 * http://codex.wordpress.org/Function_Reference/add_submenu_page
		 *
		 * @var array
		 */
		private $args;

		/**
		 * Page Hook Suffix generated when you add an admin page
		 * http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
		 *
		 * @var string
		 */
		private $page_hook;


		/**
		 * Class constructor
		 */
		public function __construct() {

			// Arguments used by add_submenu_page() .
			$this->args = array(
				'capability' => 'administrator',
				'menu_title' => __( 'WP Settings Demo', 'wp-settings' ),
				'page_title' => __( 'WP Settings Demo', 'wp-settings' ),
				'plugin_slug' => 'wp-settings-demo',
				'parent_slug' => 'options-general.php',
			);

			// Adds the plugin admin menu.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			// Initialize settings.
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}


		/**
		 * Initialize settings.
		 *
		 * @since 0.1
		 */
		public function admin_init() {
			// get the $pages and $fields arrays
			require_once plugin_dir_path( __FILE__ ) . 'pages/pages.php';

			// Initialize settings.
			parent::settings_admin_init( $pages, $fields, $this->page_hook );
		}


		/**
		 * Adds the plugin admin menu.
		 *
		 * @since 0.1
		 */
		public function admin_menu() {

			// Add the plugin admin menu page (in wp-admin > Settings).
			// And set the page hook for this settings page (used in settings_admin_init() above).
			$this->page_hook = add_submenu_page( $this->args['parent_slug'], $this->args['page_title'], $this->args['menu_title'], $this->args['capability'], $this->args['plugin_slug'], array( $this, 'admin_page' ) );

		}


		/**
		 * Displays the plugin's admin page.
		 *
		 * @since 0.1
		 */
		public function admin_page() {
			echo '<div class="wrap">';

			// This is an example on how you can show admin notices on your plugin page.
			// The conditional is needed because notices are shown by default in options-general.php.
			if ( $this->args['parent_slug'] != 'options-general.php' ) {
				// display setting errors on pages other than wp-admin -> Settings (options-general.php).
				settings_errors();
			}

			// Display plugin title and tabbed navigation.
			parent::render_header( $this->args['menu_title'] );

			// Display debug messages (only needed when developing)
			// echo $this->debug;

			// Display the form(s).
			parent::render_form();
			echo '</div>';
		}


		/**
		 * Validation of form fields.
		 * register_setting() $sanitize_callback function
		 * http://codex.wordpress.org/Function_Reference/register_setting
		 *
		 * @since 0.1
		 * @return array
		 */
		public function validate_section_one( $fields ) {
			// Checks if the textarea is empty.
			if ( trim( $fields['section_one_textarea'] ) == '' ) {

				// Add the default text back (optional).
				$fields['section_one_textarea'] = __( "Don't leave this textarea empty.", 'wp-settings' );

				// Use add_settings_error() to show error messages.
				// class 'WP_Settings_Demo_Form_Fields' uses these errors to show errors on the form fields.
				add_settings_error(
					'section_one_textarea', // Form field id.
					'texterror', // Error id.
					__( 'Error: please type a comment.', 'wp-settings' ), // Error message.
					'error' // Type of message. Use 'error' or 'updated'.
				);

			}

			// Don't forget to return the fields.
			return $fields;
		}


	} // class

	$wp_settings_demo = new WP_Settings_Demo();
} // class exists