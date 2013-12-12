<?php defined('SYSPATH') OR die('No direct access allowed.');

class Validation extends Validation_Core {
	
	/**
	 * Remove a rule that has already been set
	 *
	 * @param   string    field name
	 * @return  bool
	 */
	public function remove_rule($field)
	{
		return;
	}
	
}
