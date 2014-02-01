<?php defined('SYSPATH') or die('No direct script access.');
 
class admin_Core {
 
	/**
	 * Figure out which tab template to use. By default check for tab template
	 * in the given module first.
	 *
	 * @return view html
	 */
	public static function tabTemplate($section_url,$tab)
	{
		$path = $section_url.'/be/tab-'.$tab;
		
		if(!Kohana::find_file('views', $path)){
			$path = 'tabs/tab';
		}
			
		return new View($path, array('tab' => $tab));
	}
	
}