<?php defined('SYSPATH') OR die('No direct access allowed.');

class Router extends Router_Core {

	/**
	 * Router setup routine. Automatically called during Kohana setup process.
	 *
	 */
	public static function setup()
	{
		if ( ! empty($_SERVER['QUERY_STRING']))
		{
			// Set the query string to the current query string
			self::$query_string = '?'.trim($_SERVER['QUERY_STRING'], '&/');
		}

		if (self::$routes === NULL)
		{
			// Load routes
			self::$routes = Kohana::config('routes');
		}

		// Default route status
		$default_route = FALSE;

		if (self::$current_uri === '')
		{
			// Make sure the default route is set
			if ( ! isset(self::$routes['_default']))
				throw new Kohana_Exception('core.no_default_route');

			// Use the default route when no segments exist
			self::$current_uri = self::$routes['_default'];

			// Default route is in use
			$default_route = TRUE;
		}

		// Make sure the URL is not tainted with HTML characters
		self::$current_uri = html::specialchars(self::$current_uri, FALSE);

		// Remove all dot-paths from the URI, they are not valid
		self::$current_uri = preg_replace('#\.[\s./]*/#', '', self::$current_uri);

		// At this point segments, rsegments, and current URI are all the same
		self::$segments = self::$rsegments = self::$current_uri = trim(self::$current_uri, '/');

		// Set the complete URI
		self::$complete_uri = self::$current_uri.self::$query_string;

		// Explode the segments by slashes
		self::$segments = ($default_route === TRUE OR self::$segments === '') ? array() : explode('/', self::$segments);

		if ($default_route === FALSE AND count(self::$routes) > 1)
		{
			// Custom routing
			self::$rsegments = self::routed_uri(self::$current_uri);
		}

		// The routed URI is now complete
		self::$routed_uri = self::$rsegments;

		// Routed segments will never be empty
		self::$rsegments = explode('/', self::$rsegments);

		// Prepare to find the controller
		$controller_path = '';
		$method_segment  = NULL;

		// Paths to search
		$paths = Kohana::include_paths();
		
		foreach (self::$rsegments as $key => $segment)
		{
			// Add the segment to the search path
			$controller_path .= $segment;

			$found = FALSE;
			foreach ($paths as $dir)
			{
				// Search within controllers only
				$dir .= 'controllers/';
			
				// First check if admin URL, if so check for be_ controller
				if(array_key_exists(0, self::$segments) && self::$segments[0] == Kohana::config('admin.url'))
				{
					if(!self::check_path($dir,'be_'.$controller_path,$key,$segment,$method_segment,$found))
					{
						// Give up and check for normal controller
						self::check_path($dir,$controller_path,$key,$segment,$method_segment,$found);
					}
				}
				else
				{
					if(!self::check_path($dir,'fe_'.$controller_path,$key,$segment,$method_segment,$found))
					{
						// Give up and check for normal controller
						self::check_path($dir,$controller_path,$key,$segment,$method_segment,$found);
					}
				}
				
				if($found){
					break;
				}
			}

			if ($found === FALSE)
			{
				// Maximum depth has been reached, stop searching
				break;
			}

			// Add another slash
			$controller_path .= '/';
		}

		if ($method_segment !== NULL AND isset(self::$rsegments[$method_segment]))
		{
			// Set method
			self::$method = self::$rsegments[$method_segment];

			if (isset(self::$rsegments[$method_segment + 1]))
			{
				// Set arguments
				self::$arguments = array_slice(self::$rsegments, $method_segment + 1);
			}
		}

		// Last chance to set routing before a 404 is triggered
		Event::run('system.post_routing');

		if (self::$controller === NULL)
		{
			// No controller was found, so no page can be rendered
			Event::run('system.404');
		}
	}

	private function check_path($dir,$controller_path,$key,$segment,&$method_segment,&$found)
	{
		if (is_file($dir.$controller_path.EXT))
		{
			// The controller must be a file that exists with the search path
			if ($c = str_replace('\\', '/', realpath($dir.$controller_path.EXT)) AND is_file($c) AND strpos($c, $dir) === 0)
			{
				// Valid path
				$found = TRUE;
				
				// Set controller name
				self::$controller = $controller_path;
				
				// Change controller path
				self::$controller_path = $c;
				
				// Set the method segment
				$method_segment = $key + 1;
				
				// Stop searching
				return;
			}
		}
	}

}
