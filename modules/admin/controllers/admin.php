<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Admin Dashboard Controller
 *
 *
 */
class Admin_Controller extends Base_Controller
{
	
	public $section_name;
	public $section_url;
	public $list_columns;
	public $row_name_field;
	public $id;
	public $item_name;
	public $hide_tabs = array();
	public $active_tab = 0;

	public function __construct()
	{
		parent::__construct();
		
		$this->auth = new Auth();
		
		$this->_authenticated();
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
			'' => 'Add'
		);
		
		$view->edit = false;
		
		$this->render_view($view);
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

	/**
	 * Sets some basic variables that should always be used for use 
	 * in admin area templates
	 * 
	 */
	public function section_details(){
		return array(
			'section_name'	=> $this->section_name,
			'section_url'	=> $this->section_url,
			'list_columns'	=> $this->list_columns,
			'column_prefix'	=> $this->model->db_column_prefix,
			'tabs'			=> $this->tabs,
			'hide_tabs'		=> $this->hide_tabs,
			'actions'		=> misc::getActionsDropdowns(),
			'filters'		=> misc::getFiltersDropdowns(),
			'per_page'		=> misc::getPerPageDropdowns(),
			'id'			=> $this->id,
			'item_name'		=> $this->item_name,
			'active_tab'	=> isset($_GET['active_tab']) ? $_GET['active_tab'] : ($this->session->get('active_tab') ? $this->session->get_once('active_tab') : $this->active_tab)
		);
	}
	
	/**
	 * Handles listing pages
	 * 
	 */
	public function list_data(){
		// Per page
		$per_page = $this->list_data_per_page();
		
		// Filtering
		$filter = $this->list_data_filter();
		
		// Ordering
		$order = $this->list_data_order();
		
		// Count total for pagination calc
		$total = $this->model->list_data(true, false, false, $filter);
		
		// Pagination
		$pagination = $this->list_data_pagination($total,$per_page);
		
		// Get results
		$results = $this->model->list_data(false, $per_page, $pagination->sql_offset, $filter, $order);
		
		// Get extra data for the list view. By default this does nothing unless a method override is present
		if($results){
			$results = $this->model->getExtraData($results);
		}
		
		return array($pagination, $results);
	}
	
	public function list_data_per_page(){
		return $this->input->get('per_page') ? (int)$this->input->get('per_page') : 50;
	}
	
	public function list_data_filter(){
		return $this->input->get('filter') ? $this->input->get('filter') : false;
	}
	
	public function list_data_order(){
		return $this->input->get('order') ? $this->input->get('order') : false;
	}
	
	public function list_data_pagination($total,$per_page){
		return new Pagination(array(
			'query_string'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'extended'
		));
	}
	
	/**
	 * Record various actions performed in the admin
	 * 
	 * @TODO
	 */
	protected function audit_record($action = false,$area = false,$id = false,$data = false){
		return;
	}
	
	/**
	 * Processes the post data, perform:
	 * 
	 * - Validation checks
	 * - Insert / Update / Cancel
	 * - Audit log
	 * - Tab checks
	 * 
	 */
	public function process_post($type, $id = null){
		$action = $_POST['action'];
		
		if($action == 'cancel'){
			url::redirect($this->section_url);
		}
		
		if($action == 'delete'){
			if(Auth::instance()->logged_in('admin')){
				if($this->model->update(array($this->model->db_column_prefix.'status' => 0,$this->model->db_column_prefix.'deleted' => 1,$this->model->db_column_prefix.'deleted_date' => date('Y-m-d H:i:s')))){
					$this->session->set('alert',array('type' => 'success', 'message' => 'Entry deleted'));
				} else {
					$this->session->set('alert',array('type' => 'danger', 'message' => 'Something went wrong'));
				}
			} else {
				$this->session->set('alert',array('type' => 'danger', 'message' => 'Sorry, you don\'t have permission to do that.'));
			}
			
			url::redirect($this->section_url);
		}
		
		$fields = $this->model->fields();
		
		// Remove default field values
		foreach ($fields as $key => $field){
			if (isset($_POST[$key]) && $field['type'] != 'select' && $field['type'] != 'radio' && $field['type'] != 'checkbox' && $_POST[$key] == $field['value']) {
				$_POST[$key] = '';
			}
		}
		
		$validation = $this->model->validation();
	    
	    if ($validation->validate()){
	    	// Pull out fields we want from POST.
			$data = $this->model->map_post($this->input->post());

			// Format data/fields 
			$this->model->process_fields($data);
			
	        // Add or Edit
	        if($type == 'add'){
	            $id = $this->model->insert($data);
	        } elseif($type == 'edit') {
	            $this->model->update($data);
	        }
			
			// Process files
			$this->process_files($id);
	        
	        // Audit trace
	        $this->audit_record('edit',$this->section_url,$id,$data);
	        
	        // Handle redirect and success message
	        if($action == 'save'){
	            $this->session->set('alert',array('type' => 'success', 'message' => 'Saved'));
				
	            url::redirect($this->section_url);
	        } else {
	            $this->session->set('alert',array('type' => 'success', 'message' => 'Updated'));
				
	            url::redirect(Kohana::config('admin.url').'/'.$this->section_url.'/edit/'.$id);
	        }	        
	    } else {	    	
			$this->session->set('alert',array('type' => 'danger', 'message' => 'There are form errors'));

	    	return array($validation,0);
		}
	}

	/**
	 * Process uploaded files from jQuery File Upload (Multiple table)
	 * 
	 */
	public function process_files($id){
		if(isset($_POST['files']) && !empty($_POST['files']) && is_array($_POST['files'])){
			$files = arr::map_post_keys($_POST['files']);
			
			if($files){
				foreach($files as $k => $file){
					if(empty($file['id']) && file_exists(DOCROOT.'uploads/'.$file['system_name'])){							
						file::insert(
							$id,
							$this->section_url,
							$file['type'],
							$file['name'],
							$file['system_name'],
							$file['mime_type'],
							$file['size'],
							$file['extension']
						);
					}
				}
			}
		}

		return;
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
	
	/**
	 * Check person is authenticated, else redirect them to login
	 * 
	 */
	protected function _authenticated()
	{
		if(!$this->_logged_in()){
			
			$this->session->set('alert',array('type' => 'danger', 'message' => 'Please login'));
				
			url::redirect(Kohana::config('admin.url').'/account/login', 301);
		}
	}

	/**
	 * Checks Auth logged in status
	 * 
	 */
	protected function _logged_in()
	{
		$authentic = Auth::instance();
		
		if ($authentic->logged_in())
		{
			return true;
		}
		else
		{
			return false;	
		}
	}

	public function __call($function, $segments)
	{
		
	}

}