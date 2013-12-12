<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Dashboard Controller
 *
 * Essentially the homepage
 *
 */
class Dashboard_Controller extends Base_Controller
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