<?php defined('SYSPATH') OR die('No direct access allowed.');

class form extends form_Core {
	
	/**
	 * Creates an HTML form select tag, or "dropdown menu".
	 * 
	 * Fix bug in type casting
	 *
	 * @param   string|array  input name or an array of HTML attributes
	 * @param   array         select options, when using a name
	 * @param   string        option key that should be selected by default
	 * @param   string        a string to be attached to the end of the attributes
	 * @return  string
	 */
	public static function dropdown($data, $options = NULL, $selected = NULL, $extra = '')
	{

		if ( ! is_array($data))
		{
			$data = array('name' => $data);
		}
		else
		{
			if (isset($data['options']))
			{
				// Use data options
				$options = $data['options'];
			}

			if (isset($data['selected']))
			{
				// Use data selected
				$selected = $data['selected'];
			}
		}

		$input = '<select'.form::attributes($data, 'select').' '.$extra.'>'."\n";
		foreach ((array) $options as $key => $val)
		{

			// Key should always be a string
			$key = (string) $key;

			// Selected must always be a string
			$selected =  $selected;

			if (is_array($val))
			{
				$input .= '<optgroup label="'.$key.'">'."\n";
				foreach ($val as $inner_key => $inner_val)
				{
					// Inner key should always be a string
					$inner_key = (string) $inner_key;

					if (is_array($selected))
					{
						$sel = in_array($inner_key, $selected, TRUE);
					}
					else
					{
						$sel = ($selected === $inner_key);
					}

					$sel = ($sel === TRUE) ? ' selected="selected"' : '';
					$input .= '<option value="'.$inner_key.'"'.$sel.'>'.$inner_val.'</option>'."\n";
				}
				$input .= '</optgroup>'."\n";
			}
			else
			{

				if (is_array($selected))
				{
					$sel = in_array($key, $selected, TRUE);
					$sel = ($sel === TRUE) ? ' selected="selected"' : '';
				}
				else
				{
					$selected = (string) $selected;
					$sel = ($selected === $key) ? ' selected="selected"' : '';

				}

				$input .= '<option value="'.$key.'"'.$sel.'>'.$val.'</option>'."\n";
			}
		}
		$input .= '</select>';

		return $input;
	}
	
}