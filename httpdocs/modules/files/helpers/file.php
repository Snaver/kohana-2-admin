<?php defined('SYSPATH') or die('No direct script access.');
 
class file_Core {
 
	public function recursive_mkdir($path, $mode = 0777) {
		$dirs = explode(DIRECTORY_SEPARATOR , $path);
		
		if(count($dirs)){
			$path = '.';
			foreach($dirs as $dir) {
				if(!empty($dir)){
					$path .= DIRECTORY_SEPARATOR.$dir;
					
					if (!is_dir($path) && !mkdir($path, $mode)) {
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	public function get_random_name($timestamp = false){
		$name = '';
		
		if($timestamp){
			$name .= time().'_';
		}
		
		$name .= text::random($type = 'alnum', $length = 20);
		
		return $name;
	}
	
	public function get_extension($name){
		return end(explode('.', $name));
	}
	
	/**
	 * Return file info from database
	 * 
	 * $item_id
	 * $area
	 * $type
	 * 
	 */
	public function get($item_id,$area,$type){
		$this->files_model = new Files_Model();
		
		return $this->files_model->get_file(true,false,$item_id,$area,$type);
	}
	
	/**
	 * Mark all items as inactive
	 * 
	 */
	public function inactive($item_id,$area,$type){
		$db = new Database();
		
		$db->join('fileLinks','fileLinksFile_id','file_id');
		$result = $db->getwhere('files', array('fileLinksItem_id' => $item_id,'fileLinksItem_area' => $area,'file_type' => $type,'file_status' => 1))->result(false)->as_array();
		
		$count = 0;
		if($result){
			foreach($result as $k => $v){
				$status = $db->update(
					'files',
					array(
						'file_updated_date'	=> date('Y-m-d H:i:s'),
						'file_last_editor'	=> Auth::instance()->get_user()->id,
						'file_status'		=> 0
					),
					array(
						'file_id' => $v['file_id']
					)
				);
				
				if($status){
					$count += count($status);
				}
			}
		}
 
		// Return how many rows were updated
		return $count;
	}
	
	/**
	 * Save to file system and insert file into files table
	 * 
	 * $item_id			- Item id
	 * $section			- Section
	 * $file_type		- File type
	 * $file_name		- Uploaded file name
	 * $file_location	- Uploaded file location
	 * $file_mime_type	- Uploaded file mime type
	 * $file_size		- Uploaded file size
	 * $file_extension	- Uploaded file extension
	 * 
	 * Return token
	 * 
	 */
	public function insert($item_id,$section,$file_type,$file_name,$file_location,$file_mime_type,$file_size,$file_extension){
		$this->files_model = new Files_Model();
			
		// Generate a unique file name to use when saving to file system
		$system_file_name = file::get_random_name(true);
		$token = explode('_',$system_file_name);
		
		// Save path in file system, i.e. media/%area%/yyyy/mm/dd/%timestamp%_%token%
		$path = 'media'.DIRECTORY_SEPARATOR.$section.DIRECTORY_SEPARATOR.date('Y').DIRECTORY_SEPARATOR.date('m').DIRECTORY_SEPARATOR.date('d').DIRECTORY_SEPARATOR;
		
		// Check path exists, if not create
		if(!file_exists($path)){
			file::recursive_mkdir($path);
		}
		
		$path .= $system_file_name;
		
		// Move file to media
		if(rename('uploads/'.$file_location, $path)){
			$data = array(
				'file_type'			=> $file_type,
				'file_token'		=> $token[1],
				'file_name'			=> $file_name,
				'file_name_system'	=> $system_file_name,
				'file_extension'	=> $file_extension,
				'file_path'			=> $path,
				'file_mime_type'	=> $file_mime_type,
				'file_size'			=> $file_size
			);
			
			// Save to db
			$file_id = $this->files_model->insert($data);
			
			if($file_id){
				$this->files_model->insertLink(array(
					'fileLinksFile_id'		=> $file_id,
					'fileLinksItem_area'	=> $section,
					'fileLinksItem_id'		=> $item_id
				));
				
				return $token[1];
			}
		}
		
		return false;
	}

}