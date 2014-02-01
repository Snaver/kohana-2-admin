<?php defined('SYSPATH') or die('No direct script access.');
 
class users_Core {

	public static function getUsername($user_id){
		$users_model = new Users_Model();
		
		$user = $users_model->get($user_id);
		
		if($user){
			return $user['username'];
		} else {
			return '';
		}
	}
	
	public static function dropdown()
	{
		$model = new Users_Model();
		
		$users = $model->get_all();
		
		$return = array();
		
		if($users){
			foreach($users as $k => $user){
				$return[$user['id']] = $user['username'];
			}
			
			asort($return);
		}
		
		return $return;
	}

}