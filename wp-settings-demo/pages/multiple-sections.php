<?php
/**
 * Section multiple sections fields array.
 *
 * Make sure all field ids are unique (for this section).
 *
 * For more information see: https://github.com/keesiemeijer/WP-Settings
 */

// The fields array.
$multiple_sections =  array(

	/* section id (required) */
	'section_three' => array(
		array(
			'id'      => 'section_three_text', // required
			'type'    => 'text', // required
			'label'   => __( 'Text field', 'wp-settings' ),
			'desc'    => __( 'Text Input Description', 'wp-settings' ),
			'attr'    => array( 'class' => 'my_class' )
		),
		array(
			'id'        => 'section_three_radio', // required
			'type'      => 'radio', // required
			'label'     => __( 'Radio field.', 'wp-settings' ),
			'default'   => 'radio_dropdown',
			'options'   => array( // required for field type 'select'
				'radio_dropdown'   => __( 'dropdowns', 'wp-settings' ),
				'radio_text_field' => __( 'text fields', 'wp-settings' ),
				'radio_checkbox'   => __( 'checkboxes', 'wp-settings' ),
			)
		),
		/* Multiple checkboxes with extra fields */
		array(
			'id'     => 'section_three_multicheckbox',
			'type'   => 'field_row',
			'label'  => __( 'Multiple checkboxes with extra fields', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_three_multicheckbox', // required
					'type'    => 'multicheckbox', // required
					'label'   => __( 'Multi checkbox with form fields', 'wp-settings' ),
					'desc'    => __( 'Multi checkbox description.', 'wp-settings' ),
					'default' => array( 'check_me', 'specific' ), // array keys of 'options' array
					'options' => array( // required for field type 'multicheckbox'
						'check_me'          => __( 'Check me', 'wp-settings' ), // value => label
						'or_check_me'       => __( 'Or check me', 'wp-settings' ) . ' ',
						'why_u_no_check_me' => __( 'Y u no check me', 'wp-settings' ) . ' ',
						'refined_check_me'  => __( 'Please ', 'wp-settings' ),
					),
				),

				array(
					'id'        => 'section_three_multicheckbox_select', // required
					'type'      => 'select', //  required
					'option_id' => 'refined_check_me',
					'label'     => ' ' . __( 'checking this checkbox.', 'wp-settings' ),
					'default'   => 'select_one_dropdown',
					'options'   => array( // required for field type 'select'
						'select_one_dropdown'  => __( 'consider', 'wp-settings' ),
						'select_two_checkbox'  => __( 'flirt with the idea of', 'wp-settings' ),
						'select_thre_checkbox' => __( 'support this checkbox by', 'wp-settings' ),
					)
				),
			)
		),  // end of field
	), // end of section
	'section_four' => array(
		/* textarea input field with default text and extra attributes (validated) */
		array(
			'id'      => 'section_four_textarea', // required
			'type'    => 'textarea', // required
			'label'   => __( 'Textarea Input Label (required)', 'wp-settings' ),
			'desc'    => __( 'Textarea description', 'wp-settings' ),
			'attr'    => array( 'cols' => '55', 'rows' => 8 ),
		),

		/* multiple fields on one line */
		array(
			'id'     => 'section_four_checkbox_with_fields',
			'type'   => 'field_row',
			'label'  => __( 'Multiple fields on one line', 'wp-settings' ),
			'desc'   => __( 'Add description text here', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_four_checkbox_with_fields', // required
					'type'    => 'checkbox', // required
					'desc'    => __( 'Check out this select', 'wp-settings' ) . ' ',
					'default' => 'on', // default for a single checkbox is "on".
				),
				array(
					'id'      => 'section_four_select', // required
					'type'    => 'select', // required
					'label'   => ' ' . __( 'and another', 'wp-settings' ) . ' ',
					'default' => 'checkbox_select_saturday', // array key (or array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'checkbox_select_monday'    => __( 'Monday', 'wp-settings' ),
						'checkbox_select_tuesday'   => __( 'Tuesday', 'wp-settings' ),
						'checkbox_select_wednesday' => __( 'Wednesday', 'wp-settings' ),
						'checkbox_select_thursday'  => __( 'Thursday', 'wp-settings' ),
						'checkbox_select_friday'    => __( 'Friday', 'wp-settings' ),
						'checkbox_select_saturday'  => __( 'Saturday', 'wp-settings' ),
						'checkbox_select_sunday'    => __( 'Sunday', 'wp-settings' ),

					)
				),
				array(
					'id'      => 'section_four_select_two', // required
					'type'    => 'select', //  required
					'label'   => ' ' . __( 'with text after it.', 'wp-settings' ),
					'desc'    => __( 'Add description text here', 'wp-settings' ),
					'default' => 'checkbox_select_yes', // array key (or array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'checkbox_select_yes'   => __( 'Yes', 'wp-settings' ),
						'checkbox_select_no'    => __( 'No', 'wp-settings' ),
						'checkbox_select_maybe' => __( 'Maybe', 'wp-settings' ),
					)
				),
			)
		),  // end of field ),
	),

);