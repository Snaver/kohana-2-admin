<?php defined('SYSPATH') or die('No direct access allowed.');

$lang = arr::add_prefix(array(
	'name' => array(
		'required' 	=> 'Name is required',
		'default'	=> 'Invalid input'
	),
	'date' => array(
		'required' 	=> 'Date is required',
		'default'	=> 'Invalid input'
	),
	'text' => array(
		'default'	=> 'Invalid input'
	),
	'dropdown' => array(
		'required' 	=> 'Dropdown is required',
		'default'	=> 'Invalid input'
	),
	'status' => array(
		'required' 	=> 'Status is required',
		'default'	=> 'Invalid input'
	)
), 'example_1_');