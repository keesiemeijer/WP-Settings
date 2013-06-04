<?php
/**
 * Section multiple forms fields array.
 *
 * Make sure all field ids are unique (for this section).
 *
 * For more information see: https://github.com/keesiemeijer/WP-Settings
 */

// The fields array.
$multiple_forms = array(

	/* section id (required) */
	'section_five' => array(

		/* normal text input field with default text and extra attributes */
		array(
			'id'      => 'section_five_text_normal', // required
			'type'    => 'text', // required
			'label'   => __( 'Text field', 'wp-settings' ),
			'attr'    => array( 'class' => 'my_class' )
		),
		array(
			'id'        => 'section_five_radio', // required
			'type'      => 'radio', // required
			'label'     => __( 'Radio field.', 'wp-settings' ),
			'default'   => 'radio_dropdown',
			'options'   => array( // required for field type 'select'
				'radio_dropdown'   => __( 'dropdowns', 'wp-settings' ),
				'radio_text_field' => __( 'text fields', 'wp-settings' ),
				'radio_checkbox'   => __( 'checkboxes', 'wp-settings' ),
			)
		),
		/* multiple checkboxes */
		array(
			'id'      => 'section_five_checkboxes', // required
			'type'    => 'multicheckbox', // required
			'label'   => __( 'Multicheckbox label:', 'wp-settings' ),
			'default' => 'maybe', // array keys of 'options' array
			'options' => array( // required for field type 'multicheckbox'
				'check_one'   => __( 'One', 'wp-settings' ), // value => label
				'check_two'   => __( 'Two', 'wp-settings' ),
				'check_three' => __( 'Three', 'wp-settings' ),
				'check_four'  => __( 'Four', 'wp-settings' ),
				'check_five'  => __( 'Five', 'wp-settings' ),
				'check_six'  => __( 'Six', 'wp-settings' ),
			),
		),
	), // end of section

	'section_six' => array(
		array(
			'id'      => 'section_six_text', // required
			'type'    => 'text', // required
			'label'   => __( 'Text field label', 'wp-settings' ),
		),
		array(
			'id'      => 'section_six_text_two', // required
			'type'    => 'text', // required
			'label'   => __( 'Text field label', 'wp-settings' ),
		),
		array(
			'id'      => 'section_six_textarea', // required
			'type'    => 'textarea', // required
			'label'   => __( 'Textarea label', 'wp-settings' ),
			'attr'    => array( 'cols' => '55', 'rows' => 8 ),
		),

	) // end of section
);