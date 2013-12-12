<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Session driver name.
 */
$config['driver'] = 'database';

/**
 * Session storage parameter, used by drivers.
 */
$config['storage'] =  array(
	'group'	=> 'default', // or use 'default'
	'table'	=> 'sessions' // or use 'sessions'
);

/**
 * Session name.
 * It must contain only alphanumeric characters and underscores. At least one letter must be present.
 */
$config['name'] = "kohana_2_admin";

/**
 * Number of page loads before the session id is regenerated.
 * A value of 0 will disable automatic session id regeneration.
 */
$config['regenerate'] = 0;

/**
 * Enable or disable session encryption.
 * Note: this has no effect on the native session driver.
 * Note: the cookie driver always encrypts session data. Set to TRUE for stronger encryption.
 */
$config['encryption'] = TRUE;

/**
 * Session lifetime. Number of seconds that each session will last.
 * A value of 0 will keep the session active until the browser is closed (with a limit of 24h).
 */
$config['expiration'] = 60 * 60 * 24;