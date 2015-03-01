<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['cache_directory'] = 'cache';

$config['cache_path'] = DOCROOT.$config['cache_directory'];

$config['less_options'] = array(
	'compress'			=> (IN_PRODUCTION) ? true : false,	// option - whether to compress
	'strictUnits'		=> false,							// whether units need to evaluate correctly
	'strictMath'		=> false,							// whether math has to be within parenthesis
	'relativeUrls'		=> true,							// option - whether to adjust URL's to be relative
	'urlArgs'			=> array(),							// whether to add args into url tokens
	'numPrecision'		=> 8,

	'import_dirs'		=> array(),
	'import_callback'	=> null,
	'cache_dir'			=> $config['cache_path'],
	'cache_method'		=> 'php', 							//false, 'serialize', 'php', 'var_export';

	'sourceMap'			=> false,							// whether to output a source map
	'sourceMapBasepath'	=> null,
	'sourceMapWriteTo'	=> null,
	'sourceMapURL'		=> null,

	'plugins'			=> array()
);