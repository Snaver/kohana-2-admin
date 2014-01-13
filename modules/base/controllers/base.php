<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * The Base Controller, all Controllers extend this.
 *
 * Contains global methods
 *
 */
class Base_Controller extends Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		$this->session = Session::instance();
		
		$this->model = new Base_Model();
		
		// Don't save last_url for these URL's
		$exlude_segment_1 = array('account');
		
		// Store current URL including query string (To be used as last URL)
		if(!in_array($this->uri->segment(1),$exlude_segment_1)){
			$this->session->set('last_url', url::current(TRUE));
		}
	}
	
}