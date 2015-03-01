<?php

/**
 * Base model
 *
 * 
 */
class Base_Model extends Model
{

	public function __construct()
	{
		parent::__construct();
		
		$this->session = Session::instance();
	}
	
}