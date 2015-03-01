<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * The Base Controller, all Controllers extend this.
 *
 * Contains global methods and basic setup (__construct)
 *
 */
class Base_Controller extends Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		// Make session available
		$this->session = Session::instance();
		
		$this->model = new Base_Model();
		
		// Don't save last_url for these URL's
		$exlude_segment_1 = array();
		$exlude_segment_2 = array('account');
		
		// Store current URL including query string (To be used as last URL)
		if(!in_array($this->uri->segment(1),$exlude_segment_1) && !in_array($this->uri->segment(2),$exlude_segment_2)){
			$this->session->set('last_url', url::current(TRUE));
		}
	}
	
	/**
	 * Create new view object, load template from $template param
	 * 
	 */
	protected function start_view($template){
		// Load the view
		$view = new View($template);
		
		// Global vars go here
		//$view->set_global('var_name', 'var_data');
		
		return $view;
	}
	
	/**
	 * Render view
	 * 
	 */
	protected function render_view(&$view){
		return $view->render(true);
	}
	
}