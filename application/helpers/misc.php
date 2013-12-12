<?php defined('SYSPATH') or die('No direct script access.');
 
class misc_Core {
 
	public static function getActionsDropdowns($alternateOptions = false)
	{
		$actions[''] = '-- Select --';
		
		$actions['Status'] = array(
			'status-0'	=> 'Unarchive selected',
			'status-1'	=> 'Archive selected'				
		);
		
		if(Auth::instance()->logged_in('admin')){
			$actions['Delete'] = array(
				'delete-0'	=> 'Un-Delete selected',
				'delete-1'	=> 'Delete selected',
				'delete-2'	=> 'Permanently delete selected'
			);
			
			if(!$alternateOptions){
				unset($actions['Delete']['delete-0']);
			}
		}
		
		if(!$alternateOptions){
			unset($actions['Status']['status-0']);
		}
		
		return $actions;
	}
	
	public static function getFiltersDropdowns($alternateOptions = false)
	{
		$filters[''] = '-- Select --';
		
		$filters['status-0'] = 'Archived items';
		
		if(Auth::instance()->logged_in('admin')){
			$filters['deleted-1'] = 'Deleted items';
		}
		
		return $filters;
	}
	
	public static function getPerPageDropdowns()
	{
		return array(
			5	=> 5,
			25	=> 25,
			50	=> 50,
			100	=> 100,
			200	=> 200,
			400	=> 400,
			800	=> 800
		);
	}
	
	/**
	 * Sets 'value' key value from supplied $array2 etc
	 * 
	 */
	static function field_values($array1)
	{
		foreach (array_slice(func_get_args(), 1) as $array2)
		{
			foreach ($array2 as $key => $value)
			{
				if (array_key_exists($key, $array1))
				{
					$array1[$key]['value'] = $value;
				}
			}
		}

		return $array1;
	}
	
}