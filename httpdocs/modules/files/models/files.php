<?php

/**
 * Files model
 *
 * 
 */
class Files_Model extends Admin_Model
{
	// Table constants
	const DB_TABLE 			= 'files';
	const DB_PRIMARY_KEY	= 'file_id';
	const DB_COLUMN_PREFIX 	= 'file_';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db_table = self::DB_TABLE;
		$this->db_primary_key = self::DB_PRIMARY_KEY;
		$this->db_column_prefix = self::DB_COLUMN_PREFIX;
	}
	
	public function get_file($active = true, $token = false, $item_id = false, $area = false, $type = false){	
		if($active){
			$this->db->where(array($this->db_column_prefix.'status' => 1));
		}
		
		if($token){
			$this->db->where(array($this->db_column_prefix.'token' => $token));
		}
		
		if($item_id){
			$this->db->where(array('fileLinksItem_id' => $item_id));
		}
		
		if($area){
			$this->db->where(array('fileLinksItem_area' => $area));
		}
		
		if($type){
			$this->db->where(array($this->db_column_prefix.'type' => $type));
		}
		
		$this->db->join('fileLinks','fileLinksFile_id',$this->db_primary_key);	
		$result = $this->db->get($this->db_table)->result(false)->current();
		
		// Remove database column prefixes
		if($result){
			$result = arr::remove_prefix($result, $this->db_column_prefix);
				
			$result['size'] = (int)$result['size'];
		}
		
		return $result;
	}
	
	public function get_files($active = true, $area, $area_item_id, $type){		
		$this->db->select(array($this->db_table.'.*'));
		
		if($active){
			$this->db->where(array($this->db_column_prefix.'status' => 1));
		}
		
		$this->db->where(array(
			'fileLinksItem_area'	=> $area,
			'fileLinksItem_id'		=> $area_item_id,
			'file_type'				=> $type
		));
		
		$this->db->join('fileLinks','fileLinksFile_id',$this->db_primary_key);
			
		$result = $this->db->get($this->db_table)->result(false)->as_array();

		// Remove database column prefixes
		if($result){
			foreach($result as $k => &$v){	
				$v = arr::remove_prefix($v, $this->db_column_prefix);
				
				$v['size'] = (int)$v['size'];
			}
		}

		return $result;
	}

	// Insert link table
	public function insertLink($data){		
		return $this->db->insert('fileLinks', $data)->insert_id();
	}

}