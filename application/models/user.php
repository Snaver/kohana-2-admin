<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Model extends Auth_User_Model {
	
	protected $has_and_belongs_to_many = array('roles');
 
	public function unique_key($id = NULL)
	{
		if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id) )
		{
			return 'username';
		}
 
		return parent::unique_key($id);
	}
	
} // End User Model