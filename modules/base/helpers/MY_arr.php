<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Some of these were taken from Kohana 3 * 
 * 
 */
class arr extends arr_Core {
	
	/**
	 * @var  string  default delimiter for path()
	 */
	public static $delimiter = '.';
	
	public function map_post_keys($data){
		$return = array();
		foreach ($data as $k => $v)
		{
			foreach($v as $k2 => $v2) {
				if($v2 != ''){
					$return[$k2][$k] = $v2;
				}
			}
		}

		return $return;
	}

	/**
	 * Remove array key prefixes
	 * 
	 */
	static function remove_prefix($array, $prefix)
	{			
		return array_combine(
			array_map(
				function($k,$prefix){
					return preg_replace("/^$prefix/", '', $k);
				},
				array_keys($array),
				array_fill(0 , count($array) , $prefix)
			),
			$array
		);
	}

	/**
	 * Add array key prefixes
	 * 
	 */
	static function add_prefix($array, $prefix)
	{
		return array_combine(
			array_map(
				function($k,$prefix){
					return $prefix.$k;
				},
				array_keys($array),
				array_fill(0 , count($array) , $prefix)
			),
			$array
		);
	}
	
	/**
	 * Retrieves multiple paths from an array. If the path does not exist in the
	 * array, the default value will be added instead.
	 *
	 *     // Get the values "username", "password" from $_POST
	 *     $auth = Arr::extract($_POST, array('username', 'password'));
	 *
	 *     // Get the value "level1.level2a" from $data
	 *     $data = array('level1' => array('level2a' => 'value 1', 'level2b' => 'value 2'));
	 *     Arr::extract($data, array('level1.level2a', 'password'));
	 *
	 * @param   array  $array    array to extract paths from
	 * @param   array  $paths    list of path
	 * @param   mixed  $default  default value
	 * @return  array
	 */
	public static function extract2($array, array $paths, $default = NULL)
	{
		$found = array();
		foreach ($paths as $path)
		{
			Arr::set_path($found, $path, Arr::path($array, $path, $default));
		}

		return $found;
	}
	
	/**
	* Set a value on an array by path.
	*
	* @see Arr::path()
	* @param array   $array     Array to update
	* @param string  $path      Path
	* @param mixed   $value     Value to set
	* @param string  $delimiter Path delimiter
	*/
	public static function set_path( & $array, $path, $value, $delimiter = NULL)
	{
		if ( ! $delimiter)
		{
			// Use the default delimiter
			$delimiter = Arr::$delimiter;
		}

		// The path has already been separated into keys
		$keys = $path;
		if ( ! is_array($path))
		{
			// Split the keys by delimiter
			$keys = explode($delimiter, $path);
		}

		// Set current $array to inner-most array path
		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{
				// Make the key an integer
				$key = (int) $key;
			}

			if ( ! isset($array[$key]))
			{
				$array[$key] = array();
			}

			$array = & $array[$key];
		}

		// Set key on inner-most array
		$array[array_shift($keys)] = $value;
	}
	
	/**
	 * Gets a value from an array using a dot separated path.
	 *
	 *     // Get the value of $array['foo']['bar']
	 *     $value = Arr::path($array, 'foo.bar');
	 *
	 * Using a wildcard "*" will search intermediate arrays and return an array.
	 *
	 *     // Get the values of "color" in theme
	 *     $colors = Arr::path($array, 'theme.*.color');
	 *
	 *     // Using an array of keys
	 *     $colors = Arr::path($array, array('theme', '*', 'color'));
	 *
	 * @param   array   $array      array to search
	 * @param   mixed   $path       key path string (delimiter separated) or array of keys
	 * @param   mixed   $default    default value if the path is not set
	 * @param   string  $delimiter  key path delimiter
	 * @return  mixed
	 */
	public static function path($array, $path, $default = NULL, $delimiter = NULL)
	{
		if ( ! Arr::is_array($array))
		{
			// This is not an array!
			return $default;
		}

		if (is_array($path))
		{
			// The path has already been separated into keys
			$keys = $path;
		}
		else
		{
			if (array_key_exists($path, $array))
			{
				// No need to do extra processing
				return $array[$path];
			}

			if ($delimiter === NULL)
			{
				// Use the default delimiter
				$delimiter = Arr::$delimiter;
			}

			// Remove starting delimiters and spaces
			$path = ltrim($path, "{$delimiter} ");

			// Remove ending delimiters, spaces, and wildcards
			$path = rtrim($path, "{$delimiter} *");

			// Split the keys by delimiter
			$keys = explode($delimiter, $path);
		}

		do
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{
				// Make the key an integer
				$key = (int) $key;
			}

			if (isset($array[$key]))
			{
				if ($keys)
				{
					if (Arr::is_array($array[$key]))
					{
						// Dig down into the next part of the path
						$array = $array[$key];
					}
					else
					{
						// Unable to dig deeper
						break;
					}
				}
				else
				{
					// Found the path requested
					return $array[$key];
				}
			}
			elseif ($key === '*')
			{
				// Handle wildcards

				$values = array();
				foreach ($array as $arr)
				{
					if ($value = Arr::path($arr, implode('.', $keys)))
					{
						$values[] = $value;
					}
				}

				if ($values)
				{
					// Found the values requested
					return $values;
				}
				else
				{
					// Unable to dig deeper
					break;
				}
			}
			else
			{
				// Unable to dig deeper
				break;
			}
		}
		while ($keys);

		// Unable to find the value requested
		return $default;
	}

	/**
	 * Test if a value is an array with an additional check for array-like objects.
	 *
	 *     // Returns TRUE
	 *     Arr::is_array(array());
	 *     Arr::is_array(new ArrayObject);
	 *
	 *     // Returns FALSE
	 *     Arr::is_array(FALSE);
	 *     Arr::is_array('not an array!');
	 *     Arr::is_array(Database::instance());
	 *
	 * @param   mixed   $value  value to check
	 * @return  boolean
	 */
	public static function is_array($value)
	{
		if (is_array($value))
		{
			// Definitely an array
			return TRUE;
		}
		else
		{
			// Possibly a Traversable object, functionally the same as an array
			return (is_object($value) AND $value instanceof Traversable);
		}
	}
}