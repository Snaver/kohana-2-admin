<?php defined('SYSPATH') or die('No direct script access.');

include Kohana::find_file('vendor','Less');

class assets_Core {
 
	/**
	 * Generates a css meta tag from a less source file
	 *
	 * @return css meta tag
	 */
	public static function less($path,$variables = array())
	{
		$cache_path = self::less_src($path,$variables);
			
		return html::stylesheet($cache_path, 'screen');
	}
	
	/**
	 * Parse less file and generate path to cache
	 *
	 * @return css meta tag
	 */
	public static function less_src($path,$variables = array())
	{
		try {		
			$parser = new Less_Parser(Kohana::config('assets.less_options'));
			
			$parser->parseFile(DOCROOT.$path, url::base());
			
			if(!empty($variables)){
				$parser->ModifyVars( $variables );
			}
			
			$css_file_name = Less_Cache::Get( array(DOCROOT.$path => url::base()) );
			
			return url::base().Kohana::config('assets.cache_directory').'/'.$css_file_name;
		} catch (exception $e) {			
			ob_clean();
			
			die("fatal error: " . $e->getMessage());
			
			// @TODO see why this doesn't work
			//throw new Kohana_User_Exception('Failed to generate less file', $e->getMessage());
		}
	}
	
}