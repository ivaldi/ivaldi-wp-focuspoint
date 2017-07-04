<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_554c624489a0b',
	'title' => 'Image Focus point',
	'fields' => array (
		array (
			'key' => 'field_554c625263904',
			'label' => 'Focus point X',
			'name' => 'focus_point_x',
			'type' => 'text',
			'instructions' => 'Value between -1 (left) and 1 (right). Use a dot in comma values. E.g. -0.75',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_554c630d63905',
			'label' => 'Focus point Y',
			'name' => 'focus_point_y',
			'type' => 'text',
			'instructions' => 'Value between -1 (bottom) and 1 (top). Use a dot in comma values. E.g. 0.5',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'attachment',
				'operator' => '==',
				'value' => 'all',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
	'modified' => 1431069542,
));

endif;
