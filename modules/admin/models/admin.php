<?php

/**
 * Admin base model
 *
 * 
 */
class Admin_Model extends Base_Model
{
	public $editing = false;
	public $id;
	
	public $db_table;
	public $db_primary_key;
	public $db_column_prefix;
	
	public $section_name;
	public $section_url;
	
	public $debug = false;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function fields()
	{
		return array();	
	}
	
	public function list_data($count, $limit = false, $offset = false, $filter = false, $order = false)
	{		
		// Select all fields
		$this->list_data_select();
		
		// Limit
		$this->list_data_limit($limit,$offset);
		
		// Filter
		$this->list_data_filter($filter);

		// Order
		$this->list_data_order($order);
		
		// Group by
		$this->list_data_groupby();
		
		// Joins
		$this->list_data_joins();
		
		if($count){
			return $this->db->get($this->db_table)->count();
		} else {
			return $this->db->get($this->db_table)->result(false)->as_array();
		}
	}

	public function list_data_select(){
		$this->db->select(array($this->db_table.'.*'));
	}
	
	public function list_data_limit($limit,$offset){
		if($limit !== FALSE && $offset !== FALSE ){
			$this->db->limit($limit, $offset);
		}
	}
	
	public function list_data_filter($filter){
		if($filter){
			$filter = explode('-',$filter);
			
			if($filter && is_array($filter)){
				$this->db->where(array($this->db_column_prefix.$filter[0] => $filter[1]));
				
				if($filter[0] != 'status' && $filter[0] != 'deleted'){
					$this->db->where(array($this->db_column_prefix.'status' => 1));
				}
				if($filter[0] != 'deleted'){
					$this->db->where(array($this->db_column_prefix.'deleted' => 0));
				}
			}
		} else {
			$this->db->where(array($this->db_column_prefix.'status' => 1));
			$this->db->where(array($this->db_column_prefix.'deleted' => 0));
		}
	}
	
	public function list_data_order($order){
		if($order){
			$this->db->orderby($order);
		} else {
			$this->db->orderby($this->db_column_prefix.'id','ASC');
		}
	}
	
	public function list_data_groupby(){
		$this->db->groupby($this->db_primary_key);
	}

	public function list_data_joins(){}

	public function getExtraData($result){
		return $result;
	}

	/**
	 * Validation
	 * 
	 * Perform validation based on basic rules
	 * 
	 */
	public function validation($validation = null){
		!$validation && $validation = new Validation($_POST + $_FILES);
				
		foreach($this->fields() as $key => $v){
			if($v['type'] == 'input' || $v['type'] == 'textarea'){
				$validation->pre_filter('trim', $key);
			}
				
			if($v['required']){
				$validation->add_rules($key, 'required');
			}
			
			if(isset($v['maxlength'])){
				$validation->add_rules($key, 'length[0,'.$v['maxlength'].']');
			}
		}
		
		return $validation;
	}

	/**
	 * Maps data (Usually $_POST) to the database fields
	 * 
	 */
	public function map_post($data){
		$fields = array();		
		foreach($this->fields() as $key => $v){
			if($v['map_post']){
				$fields[] = $key;
			}
		}

		return arr::extract2($data, $fields);
	}
	
	/**
	 * Perform processing on certain field types
	 * 
	 */
	public function process_fields(&$data){
		foreach($this->fields() as $key => $v){
			if($v['type'] == 'date'){
				$data[$key] = date('Y-m-d H:i:s',strtotime($data[$key]));
			}
		}
	}
	
	/**
	 * Checks that field for unique entries
	 *
	 * 
	 */
	public function check_unique(Validation $post, $field){
		// If add->rules validation found any errors, get me out of here!
		if (array_key_exists($field, $post->errors()))
			return;
		
		// Only run on provided data
		if (empty($post->$field))
			return;
		
		// Build query
		$query = array(
			$field => $post->$field
		);
		
		// Exclude current record if we're editing
		if($this->editing){
			$query[$this->db_primary_key.' !='] = $this->id;
		}
		
		if($this->query($query,true)){
			// Add a validation error, this will cause $post->validate() to return FALSE
			$post->add_error( $field, 'check_unique');
		}
		
		return;
	}
	
	/**
	 * General query method
	 *
	 * 
	 */
	public function query($where, $single = false){
		$this->db->where($where);
		
		if($single){
			$result = $this->db->get($this->db_table)->result(false)->current();
		} else {
			$result = $this->db->get($this->db_table)->result(false)->as_array();
		}
		
		return $result;
	}

	public function get_all($active = true, $where = false, $order = false){		
		$this->db->select(array($this->db_table.'.*'));
		
		if($active){
			$this->db->where(array($this->db_column_prefix.'status' => 1));
		}
		
		if($where){
			$this->db->where($where);
		}
		
		if($order){
			$this->db->orderby($order);
		}
		
		$result = $this->db->get($this->db_table)->result(false)->as_array();
		
		if($this->debug){
			echo Kohana::debug($result,$this->db->last_query());exit;
		}
		
		return $result;
	}
	
	public function get($id){
		$this->db->select(array($this->db_table.'.*'));
		
		$result = $this->db->getwhere($this->db_table, array($this->db_primary_key => $id))->result(false)->current();

		return $result;
	}
	
	/**
	 * Insert an entry
	 * 
	 */
	public function insert($data){
		// Remove fields that are to be processed after insert
		$post_data = $this->pre_process($data);
		
		//Add in extra info
		$data[$this->db_column_prefix.'created_date'] = date('Y-m-d H:i:s');
		$data[$this->db_column_prefix.'last_editor'] = Auth::instance()->get_user() ? Auth::instance()->get_user()->id : null;
			
		$id = $this->db->insert($this->db_table, $data)->insert_id();
		
		if($id){
			$this->id = $id;
			
			$this->post_process($post_data);
			
			return $id;
		} else {
			return false;
		}
	}
	
	/**
	 * Update an entry
	 * 
	 */
	public function update($data,$process = true){
		// Remove fields that are to be processed after insert
		if($process) $post_data = $this->pre_process($data);
		
		//Add in extra info
		$data[$this->db_column_prefix.'updated_date'] = date('Y-m-d H:i:s');
		$data[$this->db_column_prefix.'last_editor'] = Auth::instance()->get_user()->id;
			
		$update = $this->db->update($this->db_table, $data, array($this->db_primary_key => $this->id));
		
		if($update){
			if($process) $this->post_process($post_data);
			
			return $this->id;
		} else {
			return false;
		}
	}
	
	/**
	 * Remove certain fields from data, ready for post processing
	 * 
	 */
	public function pre_process(&$data){
		$post_data = array();
		foreach($this->fields() as $key => $v){
			if(isset($v['post_process']) && $v['post_process'] && array_key_exists($key, $data)){
				$post_data[$key] = $data[$key];
				unset($data[$key]);
			}
		}
		
		return $post_data;
	}
	
	/**
	 * Perform post processing on certain fields
	 * 
	 */
	public function post_process($data){
		return;
	}
	
	/**
	 * Removes entry from DB
	 * 
	 */
	public function perm_delete(){
		return $this->db->delete($this->db_table,array($this->db_primary_key => $this->id));
	}

}