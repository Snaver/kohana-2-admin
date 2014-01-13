<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Admin Dashboard Controller
 *
 *
 */
class Dashboard_Controller extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->_authenticated();
	}

	public function index()
	{
		$view = $this->start_view('dashboard');
		
		$this->render_view($view);
	}

	public function __call($function, $segments)
	{
		
	}

}