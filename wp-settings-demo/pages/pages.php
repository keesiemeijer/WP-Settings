<?php
/**
 * pages array
 *
 * Make sure all page ids are unique.
 * Make sure all page slugs are unique.
 * Make sure all section ids are unique.
 *
 * Section ids are database option names generated for this plugin.
 * The database option name will be prefixed with the page hook of this plugin.
 *
 * For more information see: https://github.com/keesiemeijer/WP-Settings
 */

// The pages array
$pages = array(

	/* Page 1*/
	array(
		'id'       => 'single_form_fields', // required
		'slug'     => 'single-fields', // required
		'title'    => __( 'Single Fields', 'wp-settings' ),
		'sections' => array( // 'sections' is required
			
			/* section */
			array(
				'id'                => 'section_one', // required (database option name)
				'title'             => __( 'Single Form Fields', 'wp-settings' ),
				'validate_callback' => 'validate_section_one',
			),
		),
	), // end of page

	/* Page 2*/
	array(
		'id'       => 'multiple_form_fields', // required
		'slug'     => 'multiple-fields', // required
		'title'    => __( 'Multiple Fields', 'wp-settings' ),
		'submit'   => array( 'text' => __( 'Save Settings', 'wp-settings' ) ),
		'sections' => array( // 'sections' is required

			/* section */
			array(
				'id'    => 'section_two', // required (database option name)
				'title' => __( 'Multiple Form Fields', 'wp-settings' ),
				'desc'  => '<p>' . __( 'Section Description', 'wp-settings' ) . '</p>',
			),

		),
	), // end of page

	/* Page 3*/
	array(
		'id'       => 'multiple_sections', // required
		'slug'     => 'multiple-sections', // required
		'title'    => __( 'Multiple Sections', 'wp-settings' ),
		'submit'   => array( 'text' => __( 'Save Your Settings', 'wp-settings' ) ),
		'sections' => array( // 'sections' is required

			/* section */
			array(
				'id'    => 'section_three', // required (database option name)
				'title' => __( 'Section One', 'wp-settings' ),
				'desc'  => '<p style="width: 45em;">' . __( '<strong>This is the description for section one.</strong><br/> Lorem ipsum dolor sit amet, ullamcorper ultricies ut integer vestibulum ac venenatis, fringilla a laoreet donec vel. Et in praesent ut, elit nunc fringilla cumque, mauris suspendisse aliquip porttitor ornare, quisque pariatur enim, sit et aliquam eget eget. ' ) . '</p>',
			),

			/* section */
			array(
				'id'    => 'section_four', // required (database option name)
				'title' => __( 'Section Two', 'wp-settings' ),
				'desc'  => '<p style="width: 45em;">' . __( '<strong>This is the description for section two.</strong><br/> Lorem ipsum dolor sit amet, ullamcorper ultricies ut integer vestibulum ac venenatis, fringilla a laoreet donec vel. Et in praesent ut, elit nunc fringilla cumque, mauris suspendisse aliquip porttitor ornare, quisque pariatur enim, sit et aliquam eget eget.' ) . '</p>',
			),

		),
	), // end of page

	/* Page 4*/
	array(
		'id'        => 'multiple_forms', // required
		'slug'      => 'multiple-forms', // required
		'title'     => __( 'Multiple Forms', 'wp-settings' ),
		'multiform' => true,
		'sections' => array( // required

			/* section */
			array(
				'id'     => 'section_five', // required (database option name)
				'title'  => __( 'Form One', 'wp-settings' ),
				'submit' => array( 'text' => __( 'Save Form One', 'wp-settings' ) ),
				'desc'   => '<p style="width: 50em;">' . __( '<strong>This is the description for form one.</strong><br/> Lorem ipsum dolor sit amet, ullamcorper ultricies ut integer vestibulum ac venenatis, fringilla a laoreet donec vel.', 'wp-settings' ) . '</p>',
			),

			/* section */
			array(
				'id'     => 'section_six', // required (database option name)
				'title'  => __( 'Form Two', 'wp-settings' ),
				'submit' => array( 'text' => __( 'Save Form Two', 'wp-settings' ) ),
				'desc'   => '<p style="width: 50em;">' . __( '<strong>This is the description for form two.</strong><br/> Lorem ipsum dolor sit amet, ullamcorper ultricies ut integer vestibulum ac venenatis, fringilla a laoreet donec vel.', 'wp-settings' ) . '</p>',
			),

		),
	), // end of page

);

// I've split up the fields array in to individual files.
require_once plugin_dir_path( __FILE__ ) . 'single-fields.php';
require_once plugin_dir_path( __FILE__ ) . 'multiple-fields.php';
require_once plugin_dir_path( __FILE__ ) . 'multiple-sections.php';
require_once plugin_dir_path( __FILE__ ) . 'multiple-forms.php';

// Merge all fields arrays.
$fields  = array();
$fields +=  $single_fields + $multiple_fields + $multiple_sections + $multiple_forms;
