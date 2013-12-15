<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 2 model
 *
 * 
 */
class Example_2_Model extends Base_Model
{
	// Table constants
	const DB_TABLE 			= 'example_2';
	const DB_PRIMARY_KEY	= 'example_2_id';
	const DB_COLUMN_PREFIX 	= 'example_2_';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db_table = self::DB_TABLE;
		$this->db_primary_key = self::DB_PRIMARY_KEY;
		$this->db_column_prefix = self::DB_COLUMN_PREFIX;
	}
	
	public function fields()
	{
		return arr::add_prefix(array(
			'name' => array(
				'tab'		=> 0,
				'type'		=> 'input',
				'label'		=> 'Name',
				'value'		=> '',
				'map_post'	=> true,
				'maxlength'	=> 255,
				'required'	=> true
			),
			'email' => array(
				'tab'		=> 0,
				'type'		=> 'input',
				'label'		=> 'Email',
				'value'		=> '',
				'map_post'	=> true,
				'maxlength'	=> 100,
				'required'	=> true
			),
			'file' => array(
				'tab'		=> 0,
				'type'		=> 'file',
				'label'		=> 'File',
				'value'		=> '',
				'map_post'	=> true,
				'required'	=> false,
				'post_process' => true
			),
			'text' => array(
				'tab'		=> 0,
				'type'		=> 'textarea',
				'label'		=> 'Text',
				'value'		=> '',
				'map_post'	=> true,
				'required'	=> false
			),
			'dropdown' => array(
				'tab'		=> 0,
				'type'		=> 'select',
				'options'	=> array(
					''	=> '-- Select --',
					0	=> 'Option 0',
					1	=> 'Option 1',
					2	=> 'Option 2',
					3	=> 'Option 3'
				),
				'label'		=> 'Dropdown',
				'value'		=> '',
				'map_post'	=> true,
				'required'	=> true
			),
			'status' => array(
				'tab'		=> 0,
				'type'		=> 'select',
				'options'	=> array(
					''	=> '-- Select --',
					0	=> 'Inactive',
					1	=> 'Active'
				),
				'label'		=> 'Status',
				'value'		=> 1,
				'map_post'	=> true,
				'required'	=> true
			)
		), $this->db_column_prefix);
	}

	/**
	 * Add custom validation rules, run parent::validation() to run existing rules
	 * 
	 */
	public function validation($validation = null){
		$validation = parent::validation($validation);
				
		$validation->add_rules($this->db_column_prefix.'email', 'valid::email');

		return $validation;
	}
	
	/**
	 * Run after insert/update - process single upload
	 * 
	 */
	public function post_process($data){
				
		if(array_key_exists($this->db_column_prefix.'file', $data)){
			if(!empty($data[$this->db_column_prefix.'file']['file_system_name']) && file_exists(DOCROOT.'uploads/'.$data[$this->db_column_prefix.'file']['file_system_name'])){	
				
				// Mark any previous files as inactive
				file::inactive($this->id,$this->section_url,$this->section_url);
				
				// Insert
				$token = file::insert(
					$this->id,
					$this->section_url,
					$this->section_url,
					$data[$this->db_column_prefix.'file']['file_name'],
					$data[$this->db_column_prefix.'file']['file_system_name'],
					$data[$this->db_column_prefix.'file']['file_mime_type'],
					$data[$this->db_column_prefix.'file']['file_size'],
					$data[$this->db_column_prefix.'file']['file_extension']
				);
				
				$this->update(
					array($this->db_column_prefix.'file' => $token),
					false
				);
				
			}
		}
		
	}

	/**
	 * Run normal get method, however get single file upload details
	 * 
	 */
	public function get($id){
		$result = parent::get($id);
		
		if($result[$this->db_column_prefix.'file']){
			$file = file::get($id,$this->section_url,$this->section_url);
			
			if($file){
				$file['field'] = $this->db_column_prefix.'file';
				
				$result[$this->db_column_prefix.'file'] = $file;
			}
		}

		return $result;
	}
	
}