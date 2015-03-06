WP Settings
========================

A wrapper for the WordPress [Settings Api](http://codex.wordpress.org/Settings_API) to create plugin and theme settings pages the easy way. 

### Features

* Add settings for single or multiple admin pages ( with tabbed navigation ).
    * Add single or **multiple settings sections** on a page.
    * Add single or **multiple settings forms** on a page.
* Lets the WordPress settings api take care of displaying, saving and retrieving the settings.
* Easy Validation with errors displayed on the form fields itself.
* Uses WordPress [coding standards](http://make.wordpress.org/core/handbook/coding-standards/php/) and WordPress [plugin conventions](https://codex.wordpress.org/Writing_a_Plugin).

### Files
This wrapper consists of only two classes. Include them in your plugin or theme.

`WP_Settings_Settings_Fields` - class for display of the form fields.<br/>
`WP_Settings_Settings` - class for display of the settings form(s) and for updating all settings in the database.<br/>

### Screenshots

***

Example of an admin page with a validation error:

![Screenshot of validation eror](/screenshots/wp_settings_detail.png "")

See the full settings page [here](/screenshots/wp_settings.png "")

***

### Instructions

1. Do a **case sensitive** search in both classes for 'WP_Settings' and replace it with your plugin or theme [name](https://codex.wordpress.org/Writing_a_Plugin#Plugin_Name). The name should use capitalized words separated by underscores. Acronyms should be upper case.
 
2. Include the classes in your plugin or theme.

3. Create admin pages with the methods `add_page()`, `add_pages()`, `add_section()`,`add_sections()`,`add_field()` and `add_fields()`. See the <a href="#functions">functions</a> below.

4. Initialize your plugin or theme settings with the `init()` method. See the <a href="#init">init</a> function below.

5. Display the (tabbed) navigation and the settings form with the `render_header()` and `render_form()` methods.

It's also recommended to rename the class file names to adhere to WordPress <a href="http://make.wordpress.org/core/handbook/coding-standards/php/#naming-conventions">naming conventions</a>.

See the example below and the [example plugin](/example-plugin.php "") on how to implement the settings classes.

### Example

Example of a simplified plugin settings page with two sections and validation:
```php
<?php
/*
* Plugin Name: WP Settings Example
*/

if ( is_admin() ) {

	// Include the Settings classes.
	require_once plugin_dir_path( __FILE__ ) . 'wp-settings-fields.php';
	require_once plugin_dir_path( __FILE__ ) . 'wp-settings.php';

}

// Your plugin class.
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
		$this->page_hook = add_submenu_page( 'options-general.php', __( 'Example', 'plugin-text-domain' ),  __( 'Example', 'plugin-text-domain' ), 'administrator', 'example-plugin', array( $this, 'admin_page' ) );
	}


	function admin_init() {

		// Instantiate the settings class.
		$this->settings = new WP_Settings_Settings();

		// Create a $pages array with the add_page(), add_pages(), add_section(), add_sections(), add_field() and add_fields() methods.

		// Add the admin page attributes for our settings page with the add_page() method.
		$pages = $this->settings->add_page(
			array(
				'id'          => 'example_page', // required
				'slug'        => 'example',      // required
				'title'  => __( 'WP Settings Example Plugin', 'wp-settings' ),
			)
		);

		// Admin pages need at least one section.
		// Let's add two sections for this example.
		$sections = array(

			// section one
			array(
				'id'    => 'first_section', // required (database option name)
				'title' => __( 'First Section', 'wp-settings' ),
			),

			// section two
			array(
				'id'    => 'second_section', // required (database option name)
				'title' => __( 'Second Section.', 'wp-settings' ),

				// this section's fields need validation,
				// Add a validation callback function
				'validate_callback' => array( $this, 'validate_section' ),
			),
		);

		// Add the sections to our admin page.
		$pages = $this->settings->add_sections( 'example_page', $sections );

		// Let's add a text input field to the first section.
		$pages = $this->settings->add_field( 'example_page', 'first_section',
			array(
				'id'      => 'name_setting', // required
				'type'    => 'text', // required
				'label'   => __( 'Name', 'wp-settings' ),
				'desc'    => __( 'Your Name', 'wp-settings' )
			)
		);

		// multiple fields
		$fields = array(

			/* text input */
			array(
				'id'      => 'email_setting', // required
				'type'    => 'text', // required
				'label'   => __( 'Email', 'wp-settings' ),
				'desc'    => __( 'Your Email', 'wp-settings' ),
			),

			/* checkbox */
			array(
				'id'      => 'agree_setting', // required
				'type'    => 'checkbox', // required
				'desc'    => __( 'I agree with these settings.', 'wp-settings' ),
			),
		);

		// Here we're adding multiple fields to the second section.
		$pages = $this->settings->add_fields( 'example_page', 'second_section', $fields );

		// Almost done.
		// Initialize the settings with the $pages array and the unique page hook.
		$this->settings->init( $pages, $this->page_hook );

		// That's it.

	} // admin_init


	function admin_page() {
		// Display of your settings page.
		echo '<div class="wrap">';

		// Display settings errors if it's not a settings page (options-general.php).
		// settings_errors();

		// Display the plugin title and tabbed navigation.
		$this->settings->render_header( __( 'WP Settings Example', 'plugin-text-domain' ) );

		// Display debug messages (only needed when developing).
		// Displays errors and the database option created for this page.
		// echo $this->settings->debug;

		// Display the form(s).
		$this->settings->render_form();
		echo '</div>';
	}


	function validate_section( $fields ) {

		// Validation of the email setting
		// Show an error if it's empty
	
		if ( empty( $fields['email'] ) ) {

			// Use add_settings_error() to show an error messages.
			add_settings_error(
				'email', // Form field id.
				'texterror', // Error id.
				__( 'Error: please submit your email.', 'plugin-text-domain' ), // Error message.
				'error' // Type of message. Use 'error' or 'updated'.
			);
		}

		// Don't forget to return the fields
		return $fields;
	}


} // end of class

// Instantiate your plugin class.
$settings = new WP_Settings_Example();
?>
```

### Functions

***
### init()

```php
init( $pages, $page_hook );
```
Add your admin pages and setting fields to the `WP_Settings_Settings` class.
Use this function on the `admin_init` action. See the <a href="#example">example</a> above.

#### Parameters:
* `$pages` (array)(required) Array of admin pages. See <a href="#the-pages-array">pages array</a>.
* `$page_hook` (string)(required) Unique [page hook suffix](http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix) of your plugin or theme page. See the <a href="#example">example</a> above.

***

### add_page(), add_pages()

```php
add_page( $page );
add_pages( $pages );
```
Add an admin page.

##### Parameters:
* `$page` (array) (required) Array with page attributes.

Use an array of `$page` arrays for adding multiple pages with `add_pages()`.

##### Page array attributes

The required attributes for a `$page` array are  `id` and `slug`.
Use the `multiform` attribute to have multiple forms on a settings page. This will make all sections for that page a form (if more than one section is provided).


* `id`          (required) (string) (unique). The page id. Use underscores to separate words
* `slug`        (required) (string) (unique)The slug used for tabbed pages. Use dashes to separate words.
* `title`       (optional) (string) Title of the admin age. Default is an empty string.
* `multiform`   (optional) (bool) Multiple forms on this page. All sections become a settings form. Default is false.
* `submit`      (optional) (array) Array with submit button attributes.
    * `text` (optional) (string) Text used by the submit button. Default is 'Save Changes'.
    * `type` (optional) (string) The type of button. Types: 'primary', 'secondary', 'delete'. Default is 'primary'.
    * `name` (optional) (string) The HTML 'name' of the submit button. Defaults is 'submit'.

**Note**: All `ids` must be unique.

**Note**: If your plugin has multiple pages and you want to show tabbed navigation, you have to provide a page `title` for each page.


***

### add_section(), add_sections()

```php
add_section( $page, $section );
add_sections( $page, $sections );
```
Add a section to an admin page.

##### Parameters:
* `$page` (string) (required) Page id.
* `$section` (array) (required) Array with section attributes.

Use an array of `$section` arrays for adding multiple section with `add_sections()`.

##### Section array attributes
The required attribute for a `section array` is the `id`. The section `id` is used to create the database option. It will be prefixed by the page hook you provided in the <a href="#init">init()</a> function to make the database option unique.
* `id`       (required) (string) (unique) The Section id (also database option name). Use underscores to separate words 
* `title`    (optional) (string) Title of the section. Default is an empty string.
* `desc`     (optional) (string) Description of the section. Default is an empty string.
* `validate_callback` (optional) (string) (unique) Callback function name. Provide a function for validation with the same name in your plugin class. Default is an empty string.
* `submit`   (optional)(array) Array with submit button attributes. Only used when `multiform` is set. Overrides the page `submit` parameter.
    * `text` (optional) (string) Text used by the submit button. Default is 'Save Changes'.
    * `type` (optional) (string) The type of button. Types are 'primary', 'secondary', 'delete'. Default is 'primary'.
    * `name` (optional) (string) The HTML 'name' of the submit button. Default is 'submit'.

**Note**: All `ids` must be unique.

***

### add_field(), add_fields()

```php
add_field( $page, $section, $field );
add_fields( $page, $sections, $fields );
```
Add a field to a section.

##### Parameters:
* `$page` (string) (required) Page id.
* `$section` (string) (required) Section id.
* `$field` (array) (required) Array with field attributes.

Use an array of `$field` arrays for adding multiple fields with `add_fields()`.

##### Field array attributes
The required attributes for a `field array` are  `id` and `type`.
Supported form field types are 'text', 'textarea', 'checkbox', 'multicheckbox', 'radio' and 'content'.
The 'content' type is not a real form field but can be used if you want to display extra content in the form. 
The `options` parameter is required depending on `type`.

* `id`        (required) (string) (unique) Id of field. Use underscores to separate words.
* `type`      (required) (string) Type of field. Types: 'text', 'textarea', 'checkbox', 'multicheckbox', 'radio', 'select', 'field_row', 'extra'.
* `label`     (optional) (string) Label of field.
* `options`   (mixed) (array)  Array of options for `type`. Only required for `type` 'radio', 'select','multicheckbox'.
* `desc`      (optional) (string) Description of field.
* `size`      (optional) (string) Size of field (css classname). Sizes are 'regular' (default), 'small','large'.
* `default`   (optional) (string) (array) Initial default value of field. Set your defaults here.
* `attr`      (optional) (string) (array) Additional attributes of field.
* `before`	  (optional) (string) Content before the field label. 
* `after`	  (optional) (string) Content after the field label. 
* `content`   (optional) (string) Content used for `type` 'extra'.
* `text_type` (optional) (string) Set the type for text input field (e.g. 'hidden' ).

**Note**: All `ids` must be unique.

***
### render_header()

```php
render_header( $menu_title, $tab_id );
```
Display of the plugin or theme admin page (tabbed) navigation or title.
Use this function in your plugin's admin menu callback function. See the <a href="#example">example</a> above.

##### Parameters:
* `$menu_title` (string)(optional) Displays the plugin title. Default is an empty string.
* `$tab_id` (string)(optional) Page id to set the current active tab in tabbed navigation. Default is: false.

**Note**: Only use the `$tab_id` parameter if you want to override the default behavior of the tabbed navigation. For example, if you want to set the active tab to another page than the page you are currently on. 

***

### render_form()

```php
render_form();
```
Display of the plugin or theme settings form(s).
Use this function in your plugin's admin menu callback function. See the <a href="#example">example</a> above.

***

### get_settings()

```php
get_settings( $section );
```

Get all admin page settings from the database.

##### Parameters:
* `$section` (string)(optional) Only get the settings from one section. Default is an empty string (get all section settings).

***

### get_current_admin_page()

```php
get_current_admin_page();
```

Returns the current admin page and it's attributes.

***

### Manually adding pages.
Here's an example of how you can manually add admin pages without the `add_page()`, `add_section()` and `add_field()` methods. Create the array of admin pages yourself and pass it to the <a href="#init">init()</a> method.

```php
/* Pages Array */
$pages = array(

	/* Admin page array */
	array(
		'id'    => 'example_page', // required
		'slug'  => 'example',      // required
		'title' => __( 'Page one', 'plugin-text-domain' ),
		'sections' => array(       // required. Array of page sections.

			/* Section Array */
			array(
				'id'     => 'section_one_settings', // required (database option name)
				'title'  => __( 'Section One', 'plugin-text-domain' ),
				'fields' => array(

					/* Field Array */
					array(
						'id'    => 'text_input', // required
						'type'  => 'text',       // required
						'label' => 'Name',
						'desc'  => 'Your Name',
					),

					// Add more field arrays here.
				),
			),

			// Add more section arrays here.
		),
	),

	// Add more admin page arrays here.
);

// Instantiate the settings class.
$settings = new WP_Settings_Settings();

// Initialize the settings.
$settings->init( $pages, $page_hook );
```