<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Account Controller
 *
 *
 */
class Account_Controller extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->model = new Base_Model();
	}

	public function login()
	{
		if(Auth::instance()->logged_in()){
			url::redirect($this->session->get('last_url') ? $this->session->get('last_url') : '/', 301);
		}
		
		$view = $this->start_view('account/login');

		// Create a new user
		$user = ORM::factory('user');
		
		// If there is a post and $_POST is not empty
		if ($this->input->post())
		{
			//Pull out fields we want from POST.
			$data = arr::extract($this->input->post(), 'username', 'password', 'remember_me');
			
			// If successful login redirect
			$user->login($data, $this->session->get('last_url') ? $this->session->get('last_url') : '/');
			
			// If we've got here then there were login errors
			$errors = '';
			foreach ($data->errors('account') as $key => $val)
			{
				$errors .= $val.'<br />';
			}
			$this->session->set('alert',array('type' => 'danger', 'message' => $errors));
		}

		$this->render_view($view);
	}
	
	public function logout()
	{
		Auth::instance()->logout();
	
		$this->session->set('alert',array('type' => 'success', 'message' => 'Successfully logged out.'));

		url::redirect('/');
	}
	
	public function register(){
		exit;
		
		// Load the view
		$view = new View('account/register');
		
		// Create a new user
		$user = ORM::factory('user');
		
		// If there is a post and $_POST is not empty
		if ($this->input->post())
		{
			//Pull out fields we want from POST.
			$data = arr::extract($this->input->post(), 'email', 'username', 'password', 'password_confirm');

			if ($user->validate($data)){
				
				$user->add(ORM::factory('role', 'login'));
				$user->add(ORM::factory('role', 'admin'));
				
				$user->save();
						
				url::redirect('/');
				
			} else {
				$errors = '';
				foreach ($data->errors('account') as $key => $val)
				{
					$errors .= $val.'<br />';
				}
				$this->session->set('alert',array('type' => 'danger', 'message' => $errors));
			}		
		
		}
		
		$view->render(true);
	}

}