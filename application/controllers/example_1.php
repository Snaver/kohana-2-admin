<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 1 Controller
 *
 *
 */
class Example_1_Controller extends Base_Controller
{
	public $section_name = 'Example 1';
	public $section_url = 'example_1';
	
	public $tabs = array(
		0 => 'Basic'
	);
	
	public $list_columns = array(
		'example_1_name' => 'Name'
	);
	
	public $row_name_field = 'example_1_name';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_authenticated();
			
		$this->model = new Example_1_Model();
		
		// Set some model properties
		$this->model->section_name = $this->section_name;
		$this->model->section_url = $this->section_url;
	}
	
}