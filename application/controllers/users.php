<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Users Controller
 *
 * Admin management
 *
 */
class Users_Controller extends Base_Controller
{
	public $section_name		= 'Users';
	public $section_url			= 'users';
	
	public $tabs = array(
		0 => 'Basic'
	);
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_authenticated();
		
		if(!Auth::instance()->logged_in('admin')){
			url::redirect(url::base(), 301);
		}
		
		$this->model = new Users_Model();
		
		// Set some model properties
		$this->model->section_name = $this->section_name;
		$this->model->section_url = $this->section_url;
	}
	
	/**
	 * 
	 * List page
	 * 
	 */
	public function index()
	{
		$view = $this->start_view('list');
		
		list($pagination, $data) = $this->list_data();
		
		$view->set_global('data', $data);
		$view->set_global('pagination', $pagination);
		
		// Setup columns to show
		$view->columns = array(
			'email'		=> 'Email',
			'username'	=> 'Username',
			'role'		=> 'Type',
			'logins'	=> 'Logins'
		);
		
		// Set general info
		$view->set_global($this->section_details());
		
		// Set Breadcrumb items
		$view->breadcrumbs = array(
			$this->section_url => $this->section_name,
			'' => 'Listing'
		);
		
		$this->render_view($view);
	}
	
	public function add(){
		$view = $this->start_view('form');
		
		// The form's default values
		$fields = $this->model->fields();
		
		// Form field errors
		$errors = array();
		
		// Check for post
		if ($this->input->post()){
			list($validation, $error_tab) = $this->process_post('add');
			
			$errors = $validation->errors($this->section_url);
			
			// Repopulate form fields with posted data
			$fields = misc::field_values($fields, $validation->as_array());
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
		
		$view->edit = false;
		
		$this->render_view($view);
	}
	
	public function edit($id = false){
		if(!$id || !is_numeric($id) || !($entry = $this->model->get($id))){
			url::redirect($this->section_url,301);
		} else {
			$this->id = $id;
			$this->item_name = $entry['username'];
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
			list($validation, $tabId) = $this->process_post('edit',$id);
			
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