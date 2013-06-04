WP Settings
========================

Two simple classes to easily add plugin and theme settings pages using the WordPress [Settings Api](http://codex.wordpress.org/Settings_API). 

### Features

* Easily add single or multiple admin pages ( with tabbed navigation ).
    * Add single or **multiple settings sections** on a page.
    * Add single or **multiple settings forms** on a page.
* Easily validate individual settings sections (validation errors displayed on form fields).
* The classes take care of the display of the settings form(s), form fields and navigation.
* The classes create and update all settings in the database.
* The classes use WordPress [coding standards](http://make.wordpress.org/core/handbook/coding-standards/php/) and WordPress [plugin conventions](https://codex.wordpress.org/Writing_a_Plugin).

### The classes
`WP_Settings_Form_Fields` - class for display of the form fields.<br/>
`WP_Settings_Settings` - class for display of the settings form and for updating all settings in the database.<br/>
There is also third class `WP_Settings_Debug` which you can use when developing your plugin or theme settings page. It validates your plugin or theme settings pages for you. See <a href="#wp_settings_debug">WP_Settings_Debug</a>

### Screenshots

***

Example of a validation error:

![Screenshot of validation eror](/screenshots/wp_settings_validation_error.png "")

***

Example of combining multiple fields:

![Screenshot of combination of fields](/screenshots/wp_settings_multiple_fields.png "")

See more screenshots in the [screenshots](/screenshots/) directory.

***

### Instructions

1. Do a multiple **case sensitive** search and replace in all classes (to make them unique for your plugin). Replace in this order:
    * Search for 'WP_Settings' and replace it with your plugin or theme [name](https://codex.wordpress.org/Writing_a_Plugin#Plugin_Name). The name should use capitalized words separated by underscores. Acronyms should be upper case.
    * Search for 'wp_settings' and replace it with your own plugin or theme name. Words should be lower case separated by underscores.
    * Search for 'wp-settings' and replace it with your own plugin or theme [text domain](http://codex.wordpress.org/I18n_for_WordPress_Developers#Choosing_and_loading_a_domain). Words should be lower case and separated by dashes.
2. Include the classes in your plugin or theme.

3. Extend the class where you add your plugin or theme [admin menu](http://codex.wordpress.org/Administration_Menus) with the `WP_Settings_Settings` class.

4. Create an admin <a href="#the-pages-array">pages array</a> and create a <a href="#the-fields-array">form fields array</a>.

5. Initialize your plugin or theme settings with the `settings_admin_init()` method. See the <a href="#settings_admin_init">function</a> below.

6. Display the tabbed navigation and the settings form with the `render_header()` and `render_form()` methods. See the <a href="#render_header">functions</a> below.

It's also recommended to rename the class file names to adhere to WordPress <a href="http://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions">naming conventions</a>.

### Example

Example of a simplified plugin settings page (without a search and replace as in the instructions) with one text input and validation:
```php
<?php
/*
* Plugin Name: Example Plugin
*/

if ( is_admin() ) {

// Include the Settings class 'WP_Settings_Form_Fields'.
require_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-settings-form-fields.php';

// Include the Settings class 'WP_Settings_Settings'.
require_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-settings-settings.php';

// Include the debug class 'WP_Settings_Debug' (only needed when developing).
// require_once plugin_dir_path( __FILE__ ) . 'classes/class-wp-settings-debug.php';

}

// your plugin class extending the 'WP_Settings_Settings' class
class Example extends WP_Settings_Settings {

	private $page_hook;

	function __construct() {
		// Adds the plugin admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		// Initialize settings.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}


	function admin_menu() {
		// Adds the plugin admin menu page (in wp-admin > Settings)
		// sets the page hook for this settings page.
		$this->page_hook = add_submenu_page( 'options-general.php', __( 'Example', 'plugin-text-domain' ),  __( 'Example', 'plugin-text-domain' ), 'administrator', 'example-plugin', array( $this, 'admin_page' ) );
	}


	function admin_init() {
		// The pages array.
		$pages = array(
		
			/* page array */
			array(
				'id'          => 'example_page', // required
				'slug'        => 'example', // required
				'sections' => array( // required
				
					/* section array */
					array(
						'id'    => 'page_section_one', // required (database option name)
						'validate_callback' => 'validate_section', // optional (see callback function below)
					),
					
					// Add more section arrays here.
				),
			),
			
			// Add more page arrays here ( creates tabbed navigation ).
		);
		
		// The fields array.
		$fields = array(
		
			/* section id array (required) */
			'page_section_one' => array(

				/* text input form field array */
				array(
					'id'    => 'my_text_input', // required
					'type'  => 'text', // required
					'label' => __( 'My text input', 'plugin-text-domain' ), // optional
					'default' => __( 'Default text.', 'plugin-text-domain' ), // optional
				),
				
				// Add more form field arrays here.
			)
			
			// Add more section id arrays (with form field arrays) here.
		);

		// Initialize the settings.
		parent::settings_admin_init( $pages, $fields, $this->page_hook );
		
	} // admin_init


	function admin_page() {
		// Display of your settings page.
		echo '<div class="wrap">';

		// Display settings errors.
		settings_errors();

		// Display plugin title and tabbed navigation.
		parent::render_header( __( 'Example', 'plugin-text-domain' ) );
		
		// Display debug messages (only needed when developing)
		// Displays errors and the database option created for this page.
		// echo $this->debug; // or echo parent::debug;
		
		// Display the form(s).
		parent::render_form();
		echo '</div>';
	}


	function validate_section( $fields ) {
		// Check if the text field is empty.
		if ( trim( $fields['my_text_input'] ) == '' ) {
		
			// Add the default text back (optional).
			$fields['section_one_textarea'] = __( "Don't leave this textarea empty.", 'wp-settings' );

			// Use add_settings_error() to show error messages.
			// Class 'WP_Settings_Form_Fields' uses these errors to show errors on the form fields.
			add_settings_error(
				'my_text_input', // Form field id.
				'texterror', // Error id.
				__( 'Error: please type some text.', 'plugin-text-domain' ), // Error message.
				'error' // Type of message. Use 'error' or 'updated'.
			);
		}
		
		// Don't forget to return the fields
		return $fields;
	}


} // end of class

//Instantiate your plugin class.
$settings = new Example();
?>
```

### Functions

***
#### settings_admin_init()

```php
settings_admin_init( $pages, $fields, $page_hook );
```
Add your admin pages and setting fields to the `WP_Settings_Settings` class.
Use this function on the `admin_init` action. See the <a href="#example">example</a> above.

#### Parameters:
* `$pages` (array)(required) Array of pages. See <a href="#the-pages-array">pages array</a>.
* `$fields` (array)(required) Array of fields. See <a href="#the-fields-array">fields array</a>.
* `$page_hook` (string)(required) Unique [page hook suffix](http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix) of your plugin or theme page. See the <a href="#example">example</a> above.

**Note**: This function expects the format of the `$pages` and `$fields` arrays to be correct and that it contains all the required values. Use the `WP_Settings_Debug` class to help you validate these arrays. See <a href="#wp_settings_debug">WP_Settings_Debug</a>.

***

#### render_header()

```php
render_header( $menu_title, $tab_id );
```
Display of the plugin or theme admin page (tabbed) navigation or title.
Use this function in your plugin's admin menu callback function. See the <a href="#example">example</a> above.

#### Parameters:
* `$menu_title` (string)(optional) Displays the plugin title. Default is an empty string.
* `$tab_id` (string)(optional) Page id to set the current active tab in tabbed navigation. Default is: false.

**Note**: Only use the `$tab_id` parameter if you want to override the default behavior of the tabbed navigation. For example, if you want to set the active tab to another page than the page you are currently on. 

***

#### render_form()

```php
render_form();
```
Display of the plugin or theme settings form(s).
Use this function in your plugin's admin menu callback function. See the <a href="#example">example</a> above.

***

### The pages array
The pages array consists of an array with page arrays. A page array consist of parameters needed to create the admin pages.
The required parameters for a page array are  `id`, `slug`, and `sections`. A page is required to have at least one section. The required parameter for a section is the `id`. The section `id` is used to create the database option. It will be prefixed by the page hook you provided in the <a href="#settings_admin_init">settings_admin_init()</a> function to make the database option unique.
Use the `multiform` parameter to have multiple settings forms on a settings page. This will make all sections for that page a form (if more than one section is provided).
Use dashes to separate words for the page `slug` parameter. Use underscores for the `id` parameter.
#### Page parameters (array)

* `id`          (required) (string) (unique). The page id. Use underscores to separate words
* `slug`        (required) (string) (unique)The slug used for tabbed pages. Use dashes to separate words.
* `title`       (optional) (string) Title of the admin age. Default is an empty string.
* `multiform`   (optional) (bool) Multiple forms on this page. All sections become a settings form. Default is false.
* `submit`      (optional) (array) Array with submit button attributes.
    * `text` (optional) (string) Text used by the submit button. Default is 'Save Changes'.
    * `type` (optional) (string) The type of button. Types: 'primary', 'secondary', 'delete'. Default is 'primary'.
    * `name` (optional) (string) The HTML 'name' of the submit button. Defaults is 'submit'.
* `sections`    (required) (array)  Array of section arrays. A page can have multiple sections.

**section array**
* `id`       (required) (string) (unique) The Section id (also database option name). Use underscores to separate words
* `title`    (optional) (string) Title of the section. Default is an empty string.
* `desc`     (optional) (string) Description of the section. Default is an empty string.
* `validate_callback` (optional) (string) (unique) Callback function name. Provide a function for validation with the same name in your plugin class. Default is an empty string.
* `submit`   (optional)(array) Array with submit button attributes. Only used when `multiform` is set. Overrides the page `submit` parameter.
    * `text` (optional) (string) Text used by the submit button. Default is 'Save Changes'.
    * `type` (optional) (string) The type of button. Types are 'primary', 'secondary', 'delete'. Default is 'primary'.
    * `name` (optional) (string) The HTML 'name' of the submit button. Default is 'submit'.

**Note**: Section ids can't be identical to page ids.

**Note**: If your plugin has multiple pages and you want to show tabbed navigation, you have to provide a page `title` for each page.

**Note**: Use the `WP_Settings_Debug` class to validate that no required parameters are missing, and all ids and slugs are unique. See <a href="#wp_settings_debug">WP_Settings_Debug</a>.

Example for two settings pages (with tabbed navigation) :
```php
$pages = array(

	/* Page Array */
	array(
		'id'    => 'example_page', // required
		'slug'  => 'example', // required
		'title' => __( 'Page one', 'plugin-text-domain' ),
		'sections' => array( // required. Array of page sections.
		
			/* Section Array */
			array(
				'id'    => 'section_one_settings', // required (database option name)
				'title' => __( 'Section One Title', 'plugin-text-domain' ),
			),
			
			// Add more section arrays here. 
		),
	),

	/* Page Array */
	array(
		'id'          => 'example_page_two', // required
		'slug'        => 'page-two', // required
		'title' => __( 'Page two', 'plugin-text-domain' ),
		'sections' => array( // required. Array of page sections.
		
			/* Section Array */
			array(
				'id'    => 'section_two_settings', // required (database option name)
				'title' => __( 'Section two title', 'plugin-text-domain' ),
			),
			
			// add more section arrays here
		),
	),

);
```

### The fields array
This array has all the parameters of the form fields belonging to your plugin's page sections.

Example of the fields array format:
```php
$fields = array(

	// page section id
	'section_id' => array( // required
	
		// form fields of this section
		'text'  => array( /* field paremeters here */ ),
		'radio' => array(),
		
	),
	
	// page section id
	'section_id' => array( // reqiured
	
		// form fields of this section
		'select' => array()
		
	),
)
```
Supported form fields are 'text', 'textarea', 'checkbox', 'multicheckbox', 'radio', 'field_row' and 'extra'.
The 'field_row' field lets you combign multiple fields in a row. See <a href="#field_row">field_row</a>.
The 'extra' form field is not a real form field but can be used if you want to display extra content in the form.  All 'page' sections ids declared in the pages array (above) are required. The `id` and `type` parameters are always required for the form fields. The `options` parameter is required depending on `type`.
Make sure all field ids are unique. Use underscores to separate words for the `id` parameter. 

#### Field parameters:

* `id`        (required) (string) (unique) Id of field. Use underscores to separate words.
* `type`      (required) (string) Type of field. Types: 'text', 'textarea', 'checkbox', 'multicheckbox', 'radio', 'select', 'field_row', 'extra'.
* `label`     (optional) (string) Label of field.
* `options`   (mixed) (array)  Array of options for `type`. Only required for `type` 'radio', 'select','multicheckbox'.
* `desc`      (optional) (string) Description of field.
* `size`      (optional) (string) Size of field (css classname). Sizes are 'regular' (default), 'small','large'.
* `default`   (optional) (string) (array) Initial default value of field. Set your defaults here.
* `attr`      (optional) (string) (array) Additional attributes of field.
* `option_id` (optional) (string) Key of 'options' array for type `field_row`. See <a href="#field_row">field_row</a>.
* `before`	  (optional) (string) Content before the field label. 
* `after`	  (optional) (string) Content after the field label. 
* `content`   (optional) (string) Content used for `type` 'extra'.

**Note**: Use the `WP_Settings_Debug` class to validate that no required parameters are missing, and all ids are unique for a section. See <a href="#wp_settings_debug">WP_Settings_Debug</a>.

Example of the setting fields array:
```php

$fields = array(

	/* section id from pages array (required) */
	'section_one_settings' => array(

		/* text input field with default text */
		array(
			'id'      => 'example_text_input', // required
			'type'    => 'text', // required
			'label'   => __( 'Text Input Label', 'plugin-text-domain' ),
			'desc'    => __( 'Text Input Description', 'plugin-text-domain' ),
			'size'    => 'regular', // defaults to regular. Sizes 'regular' - 'large' - 'small'
			'default' => 'default text', // initial default value
		),

		/* textarea input field with extra attributes */
		array(
			'id'    => 'example_textarea', // required
			'type'  => 'textarea', // required
			'label' => __( 'Textarea Input Label', 'plugin-text-domain' ),
			'desc'  => __( 'Textarea description', 'plugin-text-domain' ),
			'attr'  => array( 'cols' => '55', 'rows' => 8 ) // add attributes to input
		),
	),

	/* section id from pages array (required) */
	'section_two_settings' => array(
		
		/* Radio button input field. */
		array(
			'id'      => 'example_radio_button', // required
			'type'    => 'radio', // required
			'label'   => __( 'Radio Button Label', 'plugin-text-domain' ),
			'desc'    => __( 'radio button description', 'plugin-text-domain' ),
			'default' => 'maybe', // (optional) array key of options array
			'options' => array( // required for type 'radio'
				'yes'   => __( 'Yes', 'plugin-text-domain' ), // value => label
				'no'    => __( 'No', 'plugin-text-domain' ),
				'maybe' => __( 'Maybe', 'plugin-text-domain' ),
			)
		),

		/* Select dropdown field. */
		array(
			'id'      => 'example_select', // required
			'type'    => 'select', // required
			'label'   => __( 'Dropdown Label', 'plugin-text-domain' ),
			'desc'    => __( 'Dropdown Description', 'plugin-text-domain' ),
			'default' => 'none', // (optional) array key of options array
			'options' => array( // required for field type 'select'
				'none'  => '', // value => label
				'yes'   => __( 'Yes', 'plugin-text-domain' ), // value => label
				'no'    => __( 'No', 'plugin-text-domain' ),
				'maybe' => __( 'Maybe', 'plugin-text-domain' ),
			)
		),

	),
);
```

See the [wp-settings-demo](https://github.com/keesiemeijer/WP-Settings/blob/master/wp-settings-demo/wp-settings-demo.php) for an example of all the form fields.

### field_row
The field row field lets you add multiple fields in a row. Required are `id`, `type` ('field_row') and `fields`.
Example of the fields array format:
```php	
		// Inside a section in the fields array. 
		array(
			'id'    => 'multiple_fields_example', // required
			'type'  => 'field_row',  // required
			'label' => __( 'My multiple fields example.', 'plugin-text-domain' ), // optional
			'fields' => array(  // required
				// multiple fields here
				
				// field
				array( /* field parameters as described in the fields array above */ ),  // required
				// field
				array( /* field parameters as described in the fields array above */ ),  // required
				
			)
		), // end of field
		
```
If you use the 'radio' or 'multicheckbox' field in the field_row 'fields' array only the last one (of those types) is used. You can add add fields after specific 'radio' or 'multicheckbox' options by using the `option_id` field parameter.
See the [wp-settings-demo plugin](https://github.com/keesiemeijer/WP-Settings/blob/master/wp-settings-demo/wp-settings-demo.php) for an example of all the form fields.

![Screenshot of radio with select after specific button](/screenshots/wp_settings_radio_with_extra_field.png "")

Example of a 'field_row' form field adding a select dropdown field after a specific radio button option. See screenshot above.
```php
// inside fields array
array(
	'id'    => 'example_field_row', // required
	'type'  => 'field_row', // required
	'label' => __( 'Radio button with extra field', 'wp-settings' ),
	'fields' => array( // required for type 'field_row'
		array(
			'id'      => 'my_field_row_radio', // required
			'type'    => 'radio', // required
			'default' => 'specific', // array keys of 'options' array
			'options' => array( // required for field type 'radio'
				'choose_me'         => __( 'Choose me', 'wp-settings' ), // value => label
				'or_choose_me'      => __( 'Or choose me', 'wp-settings' ) . ' ',
				'refined_choose_me' => __( 'Please consider choosing this radio button.', 'wp-settings' ),
				'specific'          => __( 'Add as many', 'wp-settings' ) . ' ', // add a select after this option *
			),
		),
		array(
			'id'        => 'field_row_select', // required
			'type'      => 'select', // required
			'option_id' => 'specific',  // array key of the 'options' array above *
			'label'     => ' ' . __( 'fields as you want.', 'wp-settings' ),
			'default'   => 'add_one_fields',
			'options'   => array( // required for field type 'select'
				'add_one_fields'   => __( 'fields', 'wp-settings' ),
				'add_one_dropdown'   => __( 'dropdowns', 'wp-settings' ),
				'add_one_text_field' => __( 'text fields', 'wp-settings' ),
				'add_one_checkbox'   => __( 'checkboxes', 'wp-settings' ),
			)
		),
	)
), // end of field
```

### WP_Settings_Debug
This class does basic validation of the <a href="#the-pages-array">pages array</a> and <a href="#the-fields-array">fields array</a>. **It's intended for use when developing**. Include the class in your plugin or theme **after** you included the `WP_Settings_Settings` class. 
The error "Plugin could not be initialized" will be displayed if required parameters are missing, or if ids or slugs are not unique. **Always test** if the class is working by removing or commenting out an 'id' parameter in the pages or fields array. To display extra information where the error occurred you can echo the $this->debug variable from the `WP_Settings_Settings` class. See the <a href="#example">example</a> above.

![Screenshot of radio with select after specific button](/screenshots/wp_settings_debug_error.png "") 

After you've fixed all errors the settings should save correctly. Also **inspect the output** (source code) of what these settings classes produce before you put your plugin or theme in production. **Remove this class** if everything is working the way it should.