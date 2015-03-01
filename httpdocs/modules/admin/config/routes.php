<?php defined('SYSPATH') OR die('No direct access allowed.');

// Maps /admin to dashboard controller
$config['admin'] = 'dashboard/index';

// Re-route all admin URL's directly to controllers
$config[kohana::config('admin.url').'/(.*)'] = '$1';
