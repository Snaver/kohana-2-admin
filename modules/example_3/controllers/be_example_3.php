<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 3 Admin Controller
 *
 *
 */
class Be_example_3_Controller extends Admin_Controller
{
	public $section_name = 'Example 3';
	public $section_url = 'example_3';
	
	public $tabs = array(
		0 => 'Basic'
	);
	
	public $list_columns = array(
		'example_3_name' => 'Name'
	);
	
	public $row_name_field = 'example_3_name';
	
	public function __construct()
	{
		parent::__construct();

		$this->model = new Be_example_3_Model();
		
		// Set some model properties
		$this->model->section_name = $this->section_name;
		$this->model->section_url = $this->section_url;
	}
	
	/**
	 * List page
	 * 
	 * Override list/index method, customisations:
	 * - Custom View
	 * - Figure out type logic
	 * 
	 */
	public function index()
	{
		$view = $this->start_view($this->section_url.'/be/list');
		
		// Type
		if($this->input->get('type') !== false && array_key_exists($this->input->get('type'),Kohana::config('example_3.types'))){
			$type = $this->input->get('type');
		} else {
			$type = 1;
		}
		
		list($pagination, $data) = $this->list_data($type);
		
		$view->set_global('data', $data);
		$view->set_global('pagination', $pagination);
		
		// Set general info
		$view->set_global($this->section_details());
		
		// Type tab override
		$view->set_global('active_tab',$type);
		
		// Set Breadcrumb items
		$view->breadcrumbs = array(
			$this->section_url => $this->section_name,
			'' => 'Listing'
		);
		
		$this->render_view($view);
	}
	
	/**
	 * Handles listing page data
	 * 
	 * Override list_data method, customisations:
	 * - 
	 * 
	 */
	public function list_data($type){
		// Per page
		$per_page = $this->list_data_per_page();
		
		// Filtering
		$filter = $this->list_data_filter();
		
		// Ordering
		$order = $this->list_data_order();
		
		// Count total for pagination calc
		$total = $this->model->list_data(true, false, false, $filter, false, $type);
		
		// Pagination
		$pagination = $this->list_data_pagination($total,$per_page);
		
		// Get results
		$results = $this->model->list_data(false, $per_page, $pagination->sql_offset, $filter, $order, $type);
		
		// Get extra data for the list view. By default this does nothing unless a method override is present
		if($results){
			$results = $this->model->getExtraData($results);
		}
		
		return array($pagination, $results);
	}
	
}