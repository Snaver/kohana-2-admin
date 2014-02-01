<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 2 Admin Controller
 *
 *
 */
class Be_example_2_Controller extends Admin_Controller
{
	public $section_name = 'Example 2';
	public $section_url = 'example_2';
	
	public $tabs = array(
		0 => 'Basic',
		1 => 'Files'
	);
	
	public $list_columns = array(
		'example_2_name'	=> 'Name',
		'example_2_email'	=> 'Email'
	);
	
	public $row_name_field = 'example_2_name';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = new Be_example_2_Model();
		
		// Set some model properties
		$this->model->section_name = $this->section_name;
		$this->model->section_url = $this->section_url;
	}

	/**
	 * Get multi-file upload files and assign them to template variable
	 * 
	 */
	public function section_details(){
		$details = parent::section_details();
		
		// Only get on edit
		if($this->id){
			
			// Get files
			$this->files_model = new Files_Model();
			$files = $this->files_model->get_files(true, $this->section_url, $this->id, 'files');
			$details['files'] = $files ? array('files' => $files) : false;
			
		}	
		
		return $details;
	}
	
}