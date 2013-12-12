<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * The Base Controller, all Controllers extend this.
 *
 * Contains global methods
 *
 */
class Base_Controller extends Controller
{
	public $section_name;
	public $section_url;
	public $id;
	public $item_name;
	public $hide_tabs = array();
	public $active_tab = 0;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->session = Session::instance();
		
		$this->auth = new Auth();
		
		$this->model = new Base_Model();
		
		// Don't save last_url for these URL's
		$exlude_segment_1 = array('account');
		
		// Store current URL including query string (To be used as last URL)
		if(!in_array($this->uri->segment(1),$exlude_segment_1)){
			$this->session->set('last_url', url::current(TRUE));
		}
	}

	/**
	 * Sets some basic section details for use in admin area templates
	 * 
	 */
	public function section_details(){
		return array(
			'section_name'	=> $this->section_name,
			'section_url'	=> $this->section_url,
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
				
	            url::redirect($this->section_url.'/edit/'.$id);
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
				
			url::redirect('account/login', 301);
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

}