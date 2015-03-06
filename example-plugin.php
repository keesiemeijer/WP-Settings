<?php
/*
* Plugin Name: WP Settings Example Plugin
* Description: Commented plugin example of adding two admin pages with WP Settings.
*/

if ( is_admin() ) {

	// Include the Settings classes.
	require_once plugin_dir_path( __FILE__ ) . 'wp-settings-fields.php';
	require_once plugin_dir_path( __FILE__ ) . 'wp-settings.php';

}

// Example plugin class.
class WP_Settings_Example {

	private $page_hook;
	private $settings;

	function __construct() {
		// Adds the plugin admin menu.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Initialize the settings class on admin_init.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}


	function admin_menu() {
		// Create the admin settings page in wp-admin > Settings (options-general.php).
		// Let's grab the page hook from the admin page we created and assign it to $this->page_hook.
		$this->page_hook = add_submenu_page( 'options-general.php', __( 'Example Admin Page', 'plugin-text-domain' ),  __( 'Example Admin Page', 'plugin-text-domain' ), 'administrator', 'example-plugin', array( $this, 'admin_page' ) );
	}


	function admin_init() {

		// Instantiate the settings class.
		$this->settings = new WP_Settings_Settings();

		// Create a $pages array with the add_page(), add_pages(), add_section(), add_sections(), add_field() and add_fields() methods.

		// Add two admin pages with the add_pages() method.
		$pages = $this->settings->add_pages( array(

				// page one
				array(
					'id'        => 'example_page', // required
					'slug'      => 'example',      // required
					'title'     => __( 'WP Settings Example Page', 'wp-settings' ),
					//'multiform' => true,
				),

				// page two
				array(
					'id'    => 'second_page', // required
					'slug'  => 'second-page', // required
					'title' => __( 'Second Page', 'wp-settings' ),
				),
			)
		);

		// Admin pages need at least one section.
		// Let's add two sections for the first admin page.
		$pages = $this->settings->add_sections( 'example_page', array(

				// section one
				array(
					'id'    => 'first_section', // required (database option name)
					'title' => __( 'First Section', 'wp-settings' ),

					// this section's fields need validation,
					// Add a validation callback function
					'validate_callback' => array( $this, 'validate_section' ),
				),

				// section two
				array(
					'id'    => 'second_section', // required (database option name)
					'title' => __( 'Second Section.', 'wp-settings' ),
				),
			)
		);

		// Let's add one section for the second admin page.
		$pages = $this->settings->add_section( 'second_page',
			array(
				// section one
				'id'    => 'second_page_first_section', // required (database option name)
				'title' => __( 'Welcome to the second page', 'wp-settings' ),
			)
		);

		// Fields for the first page
		$fields = array(

			/* normal text input field with default text and extra attributes (class) */
			/* This field will be validated with the validate_section() function below */
			array(
				'id'      => 'section_one_text', // required
				'type'    => 'text', // required
				'label'   => __( 'Text field label (required)', 'wp-settings' ),
				'default' => 'default text',
				'desc'    => __( 'This is a required field.', 'wp-settings' ),
				'attr'    => array( 'class' => 'my_class' )
			),

			/* small text input field with before and after text */
			array(
				'id'      => 'section_one_text_before_after', // required
				'type'    => 'text', // required
				'label'   => __( 'Text field label', 'wp-settings' ),
				'size'    => 'small', // defaults to regular. Sizes 'regular' - 'large' - 'small'
				'before'  => '<p>' . __( 'Text before', 'wp-settings' ) . ' ',
				'after'   => ' ' . __( 'and after.', 'wp-settings' ) . '</p>',
			),

			/* textarea input field with extra attributes  */
			array(
				'id'      => 'section_one_textarea', // required
				'type'    => 'textarea', // required
				'label'   => __( 'Textarea label', 'wp-settings' ),
				'desc'    => __( 'Textarea description.', 'wp-settings' ),
				'attr'    => array( 'cols' => '55', 'rows' => 8 ),
			),

			/* extra content  */
			array(
				'id'      => 'content_field', // required
				'type'    => 'content', // required
				'content' => __( 'This is content from the content field type', 'wp-settings' ) . '<br/><br/>' .
				'Lorem ipsum dolor sit amet, ullamcorper ultricies ut integer vestibulum ac venenatis, fringilla a laoreet donec vel.
				 Et in praesent ut, elit nunc fringilla cumque, mauris suspendisse aliquip porttitor ornare, quisque pariatur enim.'
				,
			),

			/* checkbox */
			array(
				'id'      => 'section_one_checkbox', // required
				'type'    => 'checkbox', // required
				'label'   => __( 'Checkbox label', 'wp-settings' ),
				'desc'    => __( 'Checkbox description.', 'wp-settings' ),
				'default' => 'on', // default for a single checkbox is "on".
			),

			/* multiple checkboxes */
			array(
				'id'      => 'section_one_checkbox_multi', // required
				'type'    => 'multicheckbox', // required
				'label'   => __( 'Multicheckbox label', 'wp-settings' ),
				'desc'    => __( 'Multi checkbox description.', 'wp-settings' ),
				'default' => 'maybe', // array keys of 'options' array
				'options' => array( // required for field type 'multicheckbox'
					'yes'   => 'Yes', // value => label
					'no'    => 'No',
					'maybe' => 'Maybe',
				),
			),

			/* select dropdown field */
			array(
				'id'      => 'section_one_select', // required
				'type'    => 'select', //  required
				'label'   => __( 'Select label', 'wp-settings' ),
				'desc'    => __( 'Select dropdown description', 'wp-settings' ),
				'default' => 'all', // array key (or array with keys if multi-select) of 'options' array
				'options' => array( // required for field type 'select'
					'all'   => __( 'Select All', 'wp-settings' ), // value => label
					'yes'   => __( 'Yes', 'wp-settings' ),
					'no'    => __( 'No', 'wp-settings' ),
					'maybe' => __( 'Maybe', 'wp-settings' ),
				)
			),

			/* select multiple dropdown field */
			array(
				'id'      => 'section_one_select_multi', // required
				'type'    => 'select', //  required
				'label'   => __( 'Multiple select label', 'wp-settings' ),
				'desc'    => __( 'Select multiple dropdown description', 'wp-settings' ),
				'default' => array( 'yes', 'maybe' ), // array key (keys if multi-select) of 'options' array
				'attr'    => array( 'multiple' => 'multiple' ), // add 'multiple' attribute for it to be a multi select dropdown
				'options' => array( // required for field type 'select'
					'all'   => __( 'Select All', 'wp-settings' ), // value => label
					'yes'   => __( 'Yes', 'wp-settings' ),
					'no'    => __( 'No', 'wp-settings' ),
					'maybe' => __( 'Maybe', 'wp-settings' ),
				)
			),

			/* radio buttons */
			array(
				'id'      => 'section_one_radio', // required
				'type'    => 'radio', //  required
				'label'   => __( 'Radio button label', 'wp-settings' ),
				'desc'    => __( 'Radio button description', 'wp-settings' ),
				'default' => 'maybe', // 'default' is required for field type 'radio'
				'options' => array( // required for field type 'radio'
					'yes'   => __( 'Yes', 'wp-settings' ), // value => label
					'no'    => __( 'No', 'wp-settings' ),
					'maybe' => __( 'Maybe', 'wp-settings' ),
				)
			),  // end of field
		);

		// Add the fields for the first admin page to the first section.
		$pages = $this->settings->add_fields( 'example_page', 'first_section', $fields );

		// Add a text input field to the second section on the first admin page
		$pages = $this->settings->add_field( 'example_page', 'second_section', array(
				'id'      => 'section_two_text', // required
				'type'    => 'text', // required
				'label'   => __( 'Text field label', 'wp-settings' ),
			)
		);

		// Add a text input field to the first section on the second admin page
		$pages = $this->settings->add_field( 'second_page', 'second_page_first_section', array(
				'id'      => 'section_two_text', // required
				'type'    => 'text', // required
				'label'   => __( 'Text field label', 'wp-settings' ),
			)
		);

		// Almost done.
		// Initialize the settings with the $pages array and the unique page hook.
		$this->settings->init( $pages, $this->page_hook );

		// That's it.

	} // admin_init


	function admin_page() {
		// Display the example plugin admin page.
		echo '<div class="wrap">';

		// Display settings errors if it's not a settings page (options-general.php).
		// settings_errors();

		// Display the plugin title and tabbed navigation.
		$this->settings->render_header( __( 'WP Settings Example', 'plugin-text-domain' ) );

		// Display debug messages (only needed when developing).
		// Displays errors and the database options created for this page.
		// echo $this->settings->debug;

		// Use the function get_settings() to get all the settings.
		// $settings = $this->settings->get_settings();

		// Use the function get get_current_admin_page() to check what page you're on
		// $page         = $this->settings->get_current_admin_page();
		// $current_page = $page['id'];

		// Display the form(s).
		$this->settings->render_form();
		echo '</div>';
	}


	function validate_section( $fields ) {

		// Validation of the section_one_text text input.
		// Show an error if it's empty.

		// to check the section that's being validated you can check the 'section_id'
		// that was added with a hidden field in the admin page form.
		//
		// example
		// if( 'first_section' === $fields['section_id'] ) { // do stuff }

		if ( empty( $fields['section_one_text'] ) ) {

			// Use add_settings_error() to show an error messages.
			add_settings_error(
				'section_one_text', // Form field id.
				'texterror', // Error id.
				__( 'Error: please enter some text.', 'plugin-text-domain' ), // Error message.
				'error' // Type of message. Use 'error' or 'updated'.
			);
		}

		// Don't forget to return the fields
		return $fields;
	}

} // end of class

// Instantiate your plugin class.
$settings = new WP_Settings_Example();