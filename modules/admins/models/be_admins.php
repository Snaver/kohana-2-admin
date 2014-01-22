<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Admins admin model
 *
 * 
 */
class Be_admins_Model extends Admin_Model
{
	// Table constants
	const DB_TABLE 			= 'users';
	const DB_PRIMARY_KEY	= 'id';
	const DB_COLUMN_PREFIX 	= '';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db_table = self::DB_TABLE;
		$this->db_primary_key = self::DB_PRIMARY_KEY;
		$this->db_column_prefix = self::DB_COLUMN_PREFIX;
	}
	
	public function fields()
	{
		return array(
			'role' => array(
				'tab'		=> 0,
				'type'		=> 'select',
				'options'	=> ORM::factory('role')->where(array('id !=' => 1))->select_list('id', 'nice_name'),
				'label'		=> 'User type',
				'value'		=> '',
				'map_post'	=> true,
				'required'	=> true,
				'post_process' => true
			),
			'email' => array(
				'tab'		=> 0,
				'type'		=> 'input',
				'label'		=> 'Email',
				'value'		=> '',
				'map_post'	=> true,
				'maxlength'	=> 127,
				'required'	=> true
			),
			'username' => array(
				'tab'		=> 0,
				'type'		=> 'input',
				'label'		=> 'Username',
				'value'		=> '',
				'map_post'	=> true,
				'maxlength'	=> 32,
				'required'	=> true
			),
			'password' => array(
				'tab'		=> 0,
				'type'		=> 'password',
				'label'		=> 'Password',
				'value'		=> '',
				'map_post'	=> true,
				'maxlength'	=> 50,
				'required'	=> $this->editing ? false : true
			),
			'password_confirm' => array(
				'tab'		=> 0,
				'type'		=> 'password',
				'label'		=> 'Password confirm',
				'value'		=> '',
				'map_post'	=> false,
				'maxlength'	=> 50,
				'required'	=> $this->editing ? false : true
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
		);
	}

	public function validation($validation = null){
		$validation = parent::validation($validation);
				
		$validation->add_rules('email', 'length[4,127]', 'valid::email');
		$validation->add_rules('username', 'length[4,32]', 'chars[a-zA-Z0-9_.]');
		$validation->add_callbacks('username', array($this, 'check_unique'));

		$validation->add_rules('password', 'length[5,42]');
		$validation->add_rules('password_confirm', 'matches[password]');
		
		$validation->add_rules('status', 'numeric');

		return $validation;
	}
	
	public function map_post($data){
		$data = parent::map_post($data);
		
		// If editing and password field blank - exclude
		if(empty($data['password'])){
			unset($data['password']);
		} else {
			$data['password'] = Auth::instance()->hash_password($data['password']);
		}		
		
		return $data;
	}
	
	public function getExtraData($result){
		// Get role
		if($result){
			foreach($result as $k => &$v){
				$this->db->join('roles','roles.id','roles_users.role_id');
				$role = $this->db->getwhere('roles_users', array('user_id' => $v['id'],'role_id !=' => 1))->result(false)->current();

				$v['role'] = $role['nice_name'];
			}
		}

		return $result;
	}
	
	public function get($id){
		$result = parent::get($id);
		
		// Get role
		if($result){
			$result['role'] = $this->db->getwhere('roles_users', array('user_id' => $id,'role_id !=' => 1))->result(true)->current()->role_id;
		}
		
		return $result;
	}
	
	public function post_process($data){	
		// Process roles
		$this->processRoles($this->id,$data['role']);
	}
	
	public function processRoles($user_id,$role){
		// Remove previous entries
		$this->db->delete('roles_users',array('user_id' => $user_id));
		
		// Except login role - users always need the login role
		$this->db->insert('roles_users', array('user_id' => $user_id,'role_id' => 1));
		
		// The selected role
		$this->db->insert('roles_users', array('user_id' => $user_id,'role_id' => $role));
		
		return;
	}

}