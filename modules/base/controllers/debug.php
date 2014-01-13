<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Debug Controller
 *
 * Misc useful debugging methods
 *
 */
class Debug_Controller extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->model = new Base_Model();
		
		if(IN_PRODUCTION){
			die();
		}
	}

	public function index()
	{
		echo '<h1>Session</h1>';
		echo Kohana::debug($this->session->get());exit;
	}
	
	public function kill_session()
	{
		$this->session->destroy();
		
		url::redirect('/');
	}

	public function __call($function, $segments)
	{
		
	}

}