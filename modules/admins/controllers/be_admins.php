<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Admins Controller
 *
 * Admin management
 *
 */
class Be_admins_Controller extends Admin_Controller
{
	public $section_name = 'Admins';
	public $section_url = 'admins';
	
	public $tabs = array(
		0 => 'Basic'
	);
	
	public $list_columns = array(
		'email'		=> 'Email',
		'username'	=> 'Username',
		'role'		=> 'Type',
		'logins'	=> 'Logins'
	);
	
	public $row_name_field = 'username';
	
	public function __construct()
	{
		parent::__construct();

		if(!Auth::instance()->logged_in('admin')){
			url::redirect(url::base(), 301);
		}
		
		$this->model = new Be_admins_Model();
		
		// Set some model properties
		$this->model->section_name = $this->section_name;
		$this->model->section_url = $this->section_url;
	}
	
	public function edit($id = false){
		if(!$id || !is_numeric($id) || !($entry = $this->model->get($id))){
			url::redirect($this->section_url,301);
		} else {
			$this->id = $id;
			
			if($this->row_name_field && array_key_exists($this->row_name_field, $entry)){
				$this->item_name = $entry[$this->row_name_field];
			}
		}
		
		$view = $this->start_view('form');
		
		// Set model properties for editing
		$this->model->id = $id;
		$this->model->editing = true;
		
		// The form's default values
		$fields = $this->model->fields();
		
		// Form field errors
		$errors = array();
		
		// Remove the password as it's useless (Encrypted)
		$entry['password'] = '';
		
		// Check for post
		if ($this->input->post()){
			list($validation, $error_tab_id) = $this->process_post('edit',$id);
			
			$errors = $validation->errors($this->section_url);
			
			// Repopulate form fields with posted data
			$fields = misc::field_values($fields, $validation->as_array());
		} else {
			$fields = misc::field_values($fields, $entry);
		}		
		
		// Pass entry info
		$view->set_global('fields',$fields);
		$view->set_global('errors',$errors);
		
		// Set general info
		$view->set_global($this->section_details());
		
		// Set Breadcrumb items
		$view->breadcrumbs = array(
			$this->section_url => $this->section_name,
			'' => 'Edit'
		);
		
		$view->edit = true;
		
		$this->render_view($view);
	}
	
}