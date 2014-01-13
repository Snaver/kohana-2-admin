<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Example 1 model
 *
 * 
 */
class Example_1_Model extends Admin_Model
{
	// Table constants
	const DB_TABLE 			= 'example_1';
	const DB_PRIMARY_KEY	= 'example_1_id';
	const DB_COLUMN_PREFIX 	= 'example_1_';
	
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
			'date' => array(
				'tab'		=> 0,
				'type'		=> 'date',
				'label'		=> 'Date',
				'value'		=> '',
				'map_post'	=> true,
				'required'	=> true
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
	
}