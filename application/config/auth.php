<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Defines the hash offsets to insert the salt at. The password hash length
 * will be increased by the total number of offsets.
 * 
 * Ensure the largest number is less than the length of your chosen hash algorithm. 
 * (40 for sha1, 32 for md5)
 * 
 */
$config['salt_pattern'] = '2, 4, 5, 13, 21, 23, 26, 30, 32';

/**
 * Disable this
 * 
 */
$config['admin'] = array();