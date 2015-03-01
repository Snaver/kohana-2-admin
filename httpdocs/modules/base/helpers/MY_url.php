<?php defined('SYSPATH') OR die('No direct access allowed.');

class url extends url_Core {

	public function order($key)
	{
		$order = array(
			$key => (isset($_GET['order'][$key]) && $_GET['order'][$key] == 'asc') ? 'desc' : 'asc'
		);

		return '?'. http_build_query(array('order' => $order) + $_GET);
	}

}