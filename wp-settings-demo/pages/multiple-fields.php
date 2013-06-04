<?php
/**
 * Section multiple fields array
 *
 * Make sure all field ids are unique (for this section).
 *
 * For more information see: https://github.com/keesiemeijer/WP-Settings
 */

// Extra content.
$content_extra_text_two = '<p>' . __( 'Add (multiple) fields after a specific \'multicheckbox\' or \'radio\' option.', 'wp-settings' ) . '</p>';
$content_extra_two = array(
	'id'      => 'extra_type_field_two', // required
	'type'    => 'extra', // required
	'label'   => __( 'Field type \'extra\'', 'wp-settings' ),
	'content' => $content_extra_text_two,
);

// The fields array.
$multiple_fields = array(

	/* section id (required) */
	'section_two' => array(

		/* multiple text fields with labels */
		array(
			'id'     => 'section_two_text_with_label',
			'type'   => 'field_row',
			'label'  => __( 'Multiple text fields with label', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_text_with_label_one', // required
					'type'    => 'text', // required
					'label'   =>  ' ' . __( 'Label one', 'wp-settings' ) . ' ',
					'size'    => 'small',
				),
				array(
					'id'      => 'section_two_text_with_label_two', // required
					'type'    => 'text', // required
					'label'   =>  ' ' . __( 'Label two', 'wp-settings' ) . ' ',
					'size'    => 'small',
				),
				array(
					'id'      => 'section_two_text_with_label_three', // required
					'type'    => 'text', // required
					'label'   =>  ' ' . __( 'Label three', 'wp-settings' ),
					'size'    => 'small',
					'desc'    => __( 'Add description text here', 'wp-settings' ), // ads description to field
				),
			)
		),  // end of field

		/* multiple text fields without labels and default text*/
		array(
			'id'    => 'section_two_text_without_label',
			'type'  => 'field_row',
			'label' => __( 'Multiple text fields without label', 'wp-settings' ),

			'fields' => array(
				array(
					'id'      => 'section_two_text_without_label_one', // required
					'type'    => 'text', // required
					'label'   =>  ' ', // space as seperator
					'attr'    => array( 'size' => '12' ),
					'default' =>  __( 'add', 'wp-settings' ),
				),
				array(
					'id'      => 'section_two_text_without_label_two', // required
					'type'    => 'text', // required
					'label'   =>  ' ', // space as seperator
					'attr'    => array( 'size' => '12' ),
					'default' =>  __( 'some', 'wp-settings' ),
				),
				array(
					'id'      => 'section_two_text_without_label_three', // required
					'type'    => 'text', // required
					'label'   => ' ', // space as seperator
					'attr'    => array( 'size' => '12' ),
					'default' =>  __( 'text', 'wp-settings' ),
				),
				array(
					'id'      => 'section_two_text_without_label_four', // required
					'type'    => 'text', // required
					'attr'    => array( 'size' => '12' ),
					'default' =>  __( 'here', 'wp-settings' ),
					'desc'    => __( 'Add description text here.', 'wp-settings' ),
				),
			)
		),  // end of field

		/* multiple regular text fields with labels */
		array(
			'id'     => 'section_two_text_with_regular_label',
			'type'   => 'field_row',
			'label'  => __( 'Multiple regular text fields with label', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_text_with_regular_label_one', // required
					'type'    => 'text', // required
					'label'   =>  ' ' . __( 'Label one', 'wp-settings' ) . ' ',

				),
				array(
					'id'      => 'section_two_text_with_regular_label_two', // required
					'type'    => 'text', // required
					'label'   =>  ' ' . __( 'Label two', 'wp-settings' ) . ' ',
					'before'  =>  '<br/>',
				),
				array(
					'id'      => 'section_two_text_with_regular_label_three', // required
					'type'    => 'text', // required
					'label'   =>  ' ' . __( 'Label three', 'wp-settings' ),
					'before'  =>  '<br/>',
					'desc'    => __( 'Add description text here', 'wp-settings' ), // ads description to field
				),
			)
		),  // end of field

		/* multiple select dropdowns without labels*/
		array(
			'id'     => 'section_two_select_multiple',
			'type'   => 'field_row',
			'label'  => __( 'Multiple select dropdowns', 'wp-settings' ),
			'desc'   => __( 'For multiple selects you can also use labels', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_select_multiple_one', // required
					'type'    => 'select', // required
					'label'   => ' ', // space as seperator
					'default' => 'select_one_yes', // array key (or array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'select_one_yes'   => __( 'Yes', 'wp-settings' ),
						'select_one_no'    => __( 'No', 'wp-settings' ),
						'select_one_maybe' => __( 'Maybe', 'wp-settings' ),
					)
				),
				array(
					'id'      => 'section_two_select_multiple_two', // required
					'type'    => 'select', //  required
					'label'   => ' ', // space as seperator
					'default' => 'select_two_no', // array key (or array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'select_two_yes'   => __( 'Yes', 'wp-settings' ),
						'select_two_no'    => __( 'No', 'wp-settings' ),
						'select_two_maybe' => __( 'Maybe', 'wp-settings' ),
					)
				),
				array(
					'id'      => 'section_two_select_multiple_three', // required
					'type'    => 'select', //  required
					'label'   => ' ', // space as seperator
					'desc'    => __( 'Multiple selects can also have labels.', 'wp-settings' ),
					'default' => 'select_three_maybe', // array key (or array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'select_three_yes'   => __( 'Yes', 'wp-settings' ),
						'select_three_no'    => __( 'No', 'wp-settings' ),
						'select_three_maybe' => __( 'Maybe', 'wp-settings' ),
					)
				),

			)
		),  // end of field

		/* multiple select dropdowns without labels*/
		array(
			'id'     => 'section_two_select_multiple_with_label',
			'type'   => 'field_row',
			'label'  => __( 'Multiple select dropdowns', 'wp-settings' ),
			'desc'   => __( 'For multiple selects you can also use labels', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_select_multiple_with_label_one', // required
					'type'    => 'select', // required
					'label'   => __( 'Choose number', 'wp-settings' ), // space as seperator
					'default' => 'select_two_one', // array key (or array with keys if multi-select) of 'options' array
					'options'   => array( // required for field type 'select'
						'select_two_one'   => __( 'one', 'wp-settings' ),
						'select_two_two'   => __( 'two', 'wp-settings' ),
						'select_two_three' => __( 'three', 'wp-settings' ),
					)
				),
				array(
					'id'      => 'section_two_select_multiple_with_label_three', // required
					'type'    => 'select', //  required
					'label'   => __( 'Choose?', 'wp-settings' ), // space as seperator
					'before'  =>  '<br/>',
					'default' => 'select_three_maybe', // array key (or array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'select_three_yes'   => __( 'Yes', 'wp-settings' ),
						'select_three_no'    => __( 'No', 'wp-settings' ),
						'select_three_maybe' => __( 'Maybe', 'wp-settings' ),
					)
				),
				array(
					'id'      => 'section_two_select_multiple_with_label_two', // required
					'type'    => 'select', //  required
					'label'   => __( 'Choose weekday', 'wp-settings' ), // space as seperator
					'before'  =>  '<br/>',
					'default' => 'checkbox_select_friday', // array key (or array with keys if multi-select) of 'options' array
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

			)
		),  // end of field

		/* multiple fields on one line */
		array(
			'id'    => 'section_two_checkbox_with_fields',
			'type'  => 'field_row',
			'label' => __( 'Multiple fields on one line', 'wp-settings' ),
			'desc'  => __( 'Add description text here', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_checkbox', // required
					'type'    => 'checkbox', // required
					'desc'    => __( 'Check out this select', 'wp-settings' ) . ' ',
					'default' => 'on', // default for a single checkbox is "on".
				),
				array(
					'id'      => 'section_two_one line_select', // required
					'type'    => 'select', // required
					'label'   => ' ' . __( 'and another', 'wp-settings' ) . ' ',
					'default' => 'checkbox_select_saturday', // array key (array with keys if multi-select) of 'options' array
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
					'id'      => 'section_two_one line_select_two', // required
					'type'    => 'select', //  required
					'label'   => ' ' . __( 'with text after it.', 'wp-settings' ),
					'desc'    => __( 'Add description text here', 'wp-settings' ),
					'default' => 'checkbox_select_yes', // array key (array with keys if multi-select) of 'options' array
					'options' => array( // required for field type 'select'
						'checkbox_select_yes'   => __( 'Yes', 'wp-settings' ),
						'checkbox_select_no'    => __( 'No', 'wp-settings' ),
						'checkbox_select_maybe' => __( 'Maybe', 'wp-settings' ),
					)
				),
			)
		),  // end of field

		$content_extra_two,

		/* Multiple checkboxes with extra fields */
		array(
			'id'     => 'section_two_multicheckbox',
			'type'   => 'field_row',
			'label'  => __( 'Multiple checkboxes with extra fields', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_multicheckbox', // required
					'type'    => 'multicheckbox', // required
					'label'   => __( 'Multi checkbox with form fields', 'wp-settings' ),
					'desc'    => __( 'Multi checkbox description.', 'wp-settings' ),
					'default' => array( 'check_me', 'specific' ), // array keys of 'options' array
					'options' => array( // required for field type 'multicheckbox'
						'check_me'           => 'Check me', // value => label
						'specific'           => __( 'A text field after a specific checkbox', 'wp-settings' ) . ' ',
						'specific_two'       => __( 'Add as many as you want', 'wp-settings' ) . ' ',
						'refined_check_me'   => 'Please consider checking this checkbox.', // value => label
					),
				),
				array(
					'id'        => 'section_two_multicheckbox_text', // required
					'type'      => 'text', // required
					'label'     =>  ' ' . __( 'and another dropdown', 'wp-settings' ) . ' ',
					'size'      => 'small',
					'option_id' => 'specific'
				),
				array(
					'id'        => 'section_two_multicheckbox_select', // required
					'type'      => 'select', //  required
					'option_id' => 'specific',
					'label'     => ' ' . __( 'with text after it.', 'wp-settings' ),
					'default'   => 'select_one_dropdown',
					'options'   => array( // required for field type 'select'
						'select_one_dropdown'   => __( 'dropdown', 'wp-settings' ),
						'select_one_text_field' => __( 'text field', 'wp-settings' ),
						'select_one_checkbox'   => __( 'checkbox', 'wp-settings' ),
					)
				),
				array(
					'id'        => 'section_two_multicheckbox_select_two', // required
					'type'      => 'select', // required
					'option_id' => 'specific_two',
					'default'   => 'select_two_two',
					'options'   => array( // required for field type 'select'
						'select_two_one'   => __( 'one', 'wp-settings' ),
						'select_two_two'   => __( 'two', 'wp-settings' ),
						'select_two_three' => __( 'three', 'wp-settings' ),
					)
				),
				array(
					'id'        => 'section_two_multicheckbox_text_two', // required
					'type'      => 'text', // required
					'label'     =>  ' ' . __( 'after the checkboxes.', 'wp-settings' ),
					'size'      => 'small',
					'option_id' => 'refined_check_me',
					'before'    => '<br/>' .__( 'Text with fields ', 'wp-settings' ),

				),
			)
		),  // end of field

		/* Multiple checkboxes with extra fields */
		array(
			'id'    => 'section_two_radio',
			'type'  => 'field_row',
			'label' => __( 'Radio button with extra field', 'wp-settings' ),
			'fields' => array(
				array(
					'id'      => 'section_two_radio', // required
					'type'    => 'radio', // required
					'default' => 'specific', // array keys of 'options' array
					'options' => array( // required for field type 'radio'
						'choose_me'         => __( 'Choose me', 'wp-settings' ), // value => label
						'or_choose_me'      => __( 'Or choose me', 'wp-settings' ) . ' ',
						'refined_choose_me' => __( 'Please consider choosing this radio button.', 'wp-settings' ),
						'specific'          => __( 'Add as many', 'wp-settings' ) . ' ',
					),
				),
				array(
					'id'        => 'section_two_radio_select', // required
					'type'      => 'select', // required
					'option_id' => 'specific',
					'label'     => ' ' . __( 'as you want.', 'wp-settings' ),
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


	), // end of section

);