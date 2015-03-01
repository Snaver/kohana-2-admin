<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Homepage Controller
 *
 *
 */
class Welcome_Controller extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		echo 'Homepage';
	}
	

	public function __call($function, $segments)
	{
		
	}

}