<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 3 admin model
 *
 * 
 */
class Be_example_3_Model extends Admin_Model
{
	// Table constants
	const DB_TABLE 			= 'example_3';
	const DB_PRIMARY_KEY	= 'example_3_id';
	const DB_COLUMN_PREFIX 	= 'example_3_';
	
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
			'type' => array(
				'tab'		=> 0,
				'type'		=> 'select',
				'options'	=> array(''	=> '-- Select --')+Kohana::config('example_3.types'),
				'label'		=> 'Type',
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
	
	public function list_data($count, $limit = false, $offset = false, $filter = false, $order = false, $type = false)
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
		
		// Type
		if($type !== false){
			$this->db->where(array($this->db_column_prefix.'type' => $type));
		}
		
		if($count){
			return $this->db->get($this->db_table)->count();
		} else {
			return $this->db->get($this->db_table)->result(false)->as_array();
		}
	}
	
}