<?php defined('SYSPATH') or die('No direct access allowed.');

$lang = array(
	'email' => array(
		'required' 	=> 'Email is required',
		'email' 	=> 'Email address not valid',
		'length' 	=> 'Email too short/long',
		'default'	=> 'Invalid input'
	),
	'username' => array(
		'required' 	=> 'Username is required',
		'chars' 	=> 'Username contains invalid characters',
		'invalid' 	=> 'Username/Password is invalid',
		'length' 	=> 'Username too short/long',
		'check_unique'	=> 'Username not unique',
		'default'	=> 'Invalid input'
	),
	'password' => array(
		'required' 	=> 'Password is required',
		'matches' 	=> 'Password does not match',
		'length' 	=> 'Password too short/long',
		'default'	=> 'Invalid input'
	),
	'password_confirm' => array(
		'required' 	=> 'Password confirm is required',
		'matches' 	=> 'Password does not match',
		'length' 	=> 'Password confirm too short/long',
		'default'	=> 'Invalid input'
	),
	'status' => array(
		'required' 	=> 'Status is required',
		'default'	=> 'Invalid input'
	)
);