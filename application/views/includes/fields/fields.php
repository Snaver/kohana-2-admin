<?php defined('SYSPATH') OR die('No direct access allowed.');

	foreach($fields as $key => $field){
		if($field['tab'] == $tab){
			switch($field['type']){
				case "checkbox":
					echo new View('includes/fields/checkbox',array('field' => $key));
				break;
				case "date":
					echo new View('includes/fields/input_date',array('field' => $key));
				break;
				case "file":
					echo new View('includes/fields/file',array('field' => $key));
				break;
				case "input":
					echo new View('includes/fields/input',array('field' => $key));
				break;
				case "number":
					echo new View('includes/fields/input_number',array('field' => $key));
				break;
				case "password":
					echo new View('includes/fields/input_password',array('field' => $key));
				break;
				case "radio":
					echo new View('includes/fields/radio',array('field' => $key));
				break;
				case "select":
					echo new View('includes/fields/select',array('field' => $key));
				break;			
				case "textarea":
					echo new View('includes/fields/textarea',array('field' => $key));
				break;
			}
		}
	}