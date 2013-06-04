<?php
/**
 * Section single fields array.
 *
 * Make sure all field ids are unique (for this section).
 *
 * For more information see: https://github.com/keesiemeijer/WP-Settings
 */

// Extra content.
$disabled = array(
	'id'      => 'text_disabled', // required
	'type'    => 'extra', // required
	'label'   => __( 'Text field disabled', 'wp-settings' ),
	'desc'    => __( 'Type \'extra\' is used to display this disabled text field.', 'wp-settings' ),
	'content' => '<input id="plugins_page_wp-settings_section_one[text_disabled]" class="code disabled regular-text" type="text" value="You can&#039;t touch this." disabled="disabled" name="plugins_page_wp-settings_section_one[text_disabled]">',

);

// Extra content.
$content_extra_text = '<p style="width: 30em;"><strong>' . __( 'This is some \'extra\' content.', 'wp-settings' ) . '</strong><br/>' . __( 'Change the size of a field with the \'size\' parameter or add additional attributes to the field to change it\'s size.', 'wp-settings' ) . '</p>';
$content_extra = array(
	'id'      => 'extra_type_field', // required
	'type'    => 'extra', // required
	'label'   => __( 'Field \'extra\' label', 'wp-settings' ),
	'desc'    => __( 'Type \'extra\' can also have a description.', 'wp-settings' ),
	'content' => $content_extra_text,
);

// The fields array.
$single_fields = array(

	/* section id (required) */
	'section_one' => array(

		/* normal text input field with default text and extra attributes */
		array(
			'id'      => 'section_one_text', // required
			'type'    => 'text', // required
			'label'   => __( 'Text field label', 'wp-settings' ),
			'default' => 'default text',
			'desc'    => __( 'Text field description', 'wp-settings' ),
			'attr'    => array( 'class' => 'my_class' )
		),


		/* text input field with before and after text */
		array(
			'id'      => 'section_one_text_before_after', // required
			'type'    => 'text', // required
			'label'   => __( 'Text field label', 'wp-settings' ),
			'size'    => 'small', // defaults to regular. Sizes 'regular' - 'large' - 'small'
			'before'  => '<p>' . __( 'Text before', 'wp-settings' ) . ' ',
			'after'   => ' ' . __( 'and after.', 'wp-settings' ) . '</p>',
		),

		/* textarea input field with default text and extra attributes (validated) */
		array(
			'id'      => 'section_one_textarea', // required
			'type'    => 'textarea', // required
			'label'   => __( 'Textarea label (required)', 'wp-settings' ),
			'desc'    => __( 'This is a required field.', 'wp-settings' ),
			'default' => __( "Don't leave this textarea empty.", 'wp-settings' ),
			'attr'    => array( 'cols' => '55', 'rows' => 8 ),
		),
		$content_extra, // extra field is stored in an array above

		$disabled, // disabled field is stored in an array above

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
			'attr'    => array( 'cols' => '55', 'rows' => 8 ),
			'default' => 'maybe', // 'default' is required for field type 'radio'
			'options' => array( // required for field type 'radio'
				'yes'   => __( 'Yes', 'wp-settings' ), // value => label
				'no'    => __( 'No', 'wp-settings' ),
				'maybe' => __( 'Maybe', 'wp-settings' ),
			)
		),  // end of field

	), // end of section
);