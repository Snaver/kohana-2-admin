<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 1 Front End Controller
 *
 *
 */
class Fe_example_1_Controller extends Front_end_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function index(){
		echo '(FE) Front end controller!!';exit;
	}
	
}