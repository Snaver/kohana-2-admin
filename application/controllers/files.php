<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Files Controller
 *
 * Misc file functions, mostly taken from https://github.com/blueimp/jQuery-File-Upload/blob/master/server/php/UploadHandler.php, basically
 * stripped the image and deletion stuff.
 *
 * @TODO need to tidy this up and remove the un-needed crap
 */
class Files_Controller extends Base_Controller
{
	protected $options;
		
	protected $error_messages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded'
    );

	public function __construct()
	{
		parent::__construct();
		
		$this->model = new Files_Model();
		
		// Increase max upload file size and execution time
		ini_set( 'upload_max_size' , '150M' );
		ini_set( 'post_max_size', '150M');
		ini_set( 'memory_limit', '150M');
		ini_set( 'max_execution_time', '7200' ); // 2 hours
		sleep(30);
		// Set options
		$this->options = array(
            'script_url'			=> $this->get_full_url().'/',
            'upload_dir'			=> 'uploads/',
            'upload_url'			=> url::base().'uploads/',
            'user_dirs'				=> false,
            'mkdir_mode'			=> 0755,
            'param_name'			=> 'files',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
            	'POST'
            ),
            'access_control_allow_headers' => array(
				'Content-Type',
				'Content-Range',
				'Content-Disposition'
            ),
            'readfile_chunk_size'	=> 104857600, // 100 MB
            'inline_file_types'		=> '',
            'accept_file_types'	=> '/.+$/i',
            'max_file_size'			=> null,
            'min_file_size'			=> 1,
            'max_number_of_files'	=> null,
            'discard_aborted_uploads' => true
		);
		
	}

	public function upload()
	{
		$upload = isset($_FILES[$this->options['param_name']]) ? $_FILES[$this->options['param_name']] : null;
		
        // Parse the Content-Disposition header, if available:
        $file_name = $this->get_server_var('HTTP_CONTENT_DISPOSITION') ? rawurldecode(preg_replace('/(^[^"]+")|("$)/', '', $this->get_server_var('HTTP_CONTENT_DISPOSITION') )) : null;
            
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range = $this->get_server_var('HTTP_CONTENT_RANGE') ? preg_split('/[^0-9]+/', $this->get_server_var('HTTP_CONTENT_RANGE')) : null;
            
        $size = $content_range ? $content_range[3] : null;
        
        $files = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
        } else {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:
            $files[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null, $file_name ? $file_name : (isset($upload['name']) ? $upload['name'] : null),
                $size ? $size : (isset($upload['size']) ? $upload['size'] : $this->get_server_var('CONTENT_LENGTH')),
                isset($upload['type']) ? $upload['type'] : $this->get_server_var('CONTENT_TYPE'),
                isset($upload['error']) ? $upload['error'] : null,
                null,
                $content_range
            );
        }
		
		$this->generate_response(array($this->options['param_name'] => $files), true);
		
		exit;
	}
	
	public function download($token = false){
		if($token){
			$file = $this->model->get_file(true, $token);
			
			if($file && file_exists($file['path'])){
				download::force($file['name'],file_get_contents($file['path']),$file['name']);
			} else {
				header("HTTP/1.0 404 Not Found");
			}
		}
		
		exit;
	}
	
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Helper functions ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	protected function get_user_id() {
        session_start();
        
        return session_id();
    }
	
	protected function get_user_path() {
        if ($this->options['user_dirs']) {
            return $this->get_user_id().'/';
        }
		
        return '';
    }
	
	protected function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
        
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }
	
	protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }
	
	protected function get_singular_param_name() {
        return substr($this->options['param_name'], 0, -1);
    }
	
	protected function get_file_name_param() {
        $name = $this->get_singular_param_name();
        
        return isset($_GET[$name]) ? basename(stripslashes($_GET[$name])) : null;
    }
	
	// Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
		
        return $size;
    }
	
	protected function get_error_message($error) {
        return array_key_exists($error, $this->error_messages) ? $this->error_messages[$error] : $error;
    }
	
	protected function get_config_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
		
        return $this->fix_integer_overflow($val);
    }
	
	protected function get_file_objects($iteration_method = 'get_file_object') {
		$upload_dir = $this->get_upload_path();
		
		if (!is_dir($upload_dir)) {
			return array();
		}
		
		return array_values(array_filter(array_map(
			array($this, $iteration_method),
			scandir($upload_dir)
		)));
	}
	
	protected function count_file_objects() {
        return count($this->get_file_objects('is_valid_file_object'));
    }
	
	protected function get_upload_path($file_name = null, $version = null) {
        $file_name = $file_name ? $file_name : '';
        
        if (empty($version)) {
            $version_path = '';
        } else {
            $version_dir = @$this->options['image_versions'][$version]['upload_dir'];
            if ($version_dir) {
                return $version_dir.$this->get_user_path().$file_name;
            }
            $version_path = $version.'/';
        }
		
        return $this->options['upload_dir'].$this->get_user_path().$version_path.$file_name;
    }
	
	protected function validate($uploaded_file, $file, $error, $index) {
        if ($error) {
            $file->error = $this->get_error_message($error);
            
            return false;
        }
		
        $content_length = $this->fix_integer_overflow(intval($this->get_server_var('CONTENT_LENGTH')));
        $post_max_size = $this->get_config_bytes(ini_get('post_max_size'));
		
        if ($post_max_size && ($content_length > $post_max_size)) {
            $file->error = $this->get_error_message('post_max_size');
            
            return false;
        }
		
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = $this->get_error_message('accept_file_types');
            
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = $this->get_file_size($uploaded_file);
        } else {
            $file_size = $content_length;
        }
		
        if ($this->options['max_file_size'] && ($file_size > $this->options['max_file_size'] || $file->size > $this->options['max_file_size'])) {
            $file->error = $this->get_error_message('max_file_size');
            
            return false;
        }
		
        if ($this->options['min_file_size'] && $file_size < $this->options['min_file_size']) {
            $file->error = $this->get_error_message('min_file_size');
            
            return false;
        }
		
        if (is_int($this->options['max_number_of_files']) && ($this->count_file_objects() >= $this->options['max_number_of_files'])) {
            $file->error = $this->get_error_message('max_number_of_files');
            
            return false;
        }
		
        return true;
    }

	protected function handle_form_data($file, $index) {
        // Handle form data, e.g. $_REQUEST['description'][$index]
    }
	
	protected function get_file_size($file_path, $clear_stat_cache = false) {
        if ($clear_stat_cache) {
            clearstatcache(true, $file_path);
        }
		
        return $this->fix_integer_overflow(filesize($file_path));
    }
	
	protected function set_additional_file_properties($file) {
		
		$file->type = isset($_POST['type']) ? $_POST['type'] : null;
		$file->field = isset($_POST['field']) ? $_POST['field'] : null;
		
    }

	protected function get_unique_filename() {
        return file::get_random_name(true);
    }

	protected function get_file_name() {
        return $this->get_unique_filename();
    }
	
	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
        $file = new stdClass();
        
        $file->system_name = $this->get_file_name();
        $file->name = $name;
        $file->size = $this->fix_integer_overflow(intval($size));
        $file->extension = file::get_extension($name);
        $file->mime_type = $type;
        
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $upload_dir = $this->get_upload_path();
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            
            $file_path = $this->get_upload_path($file->system_name);
            $append_file = $content_range && is_file($file_path) && $file->size > $this->get_file_size($file_path);
            
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
			
            $file_size = $this->get_file_size($file_path, $append_file);
            if ($file_size === $file->size) {
                
            } else {
                $file->size = $file_size;
                if (!$content_range && $this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = 'abort';
                }
            }
			
            $this->set_additional_file_properties($file);
        }
        return $file;
    }

	protected function header($str) {
        header($str);
    }
	
	protected function body($str) {
        echo $str;
    }

	protected function send_content_type_header() {
        $this->header('Vary: Accept');
        if (strpos($this->get_server_var('HTTP_ACCEPT'), 'application/json') !== false) {
            $this->header('Content-type: application/json');
        } else {
            $this->header('Content-type: text/plain');
        }
    }

	protected function send_access_control_headers() {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header('Access-Control-Allow-Credentials: '.($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
        $this->header('Access-Control-Allow-Methods: '.implode(', ', $this->options['access_control_allow_methods']));
        $this->header('Access-Control-Allow-Headers: '.implode(', ', $this->options['access_control_allow_headers']));
    }

	public function head() {
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        
        if ($this->options['access_control_allow_origin']) {
            $this->send_access_control_headers();
        }
		
        $this->send_content_type_header();
    }

	protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            $json = json_encode($content);
            $redirect = isset($_REQUEST['redirect']) ? stripslashes($_REQUEST['redirect']) : null;
            
            if ($redirect) {
                $this->header('Location: '.sprintf($redirect, rawurlencode($json)));
                return;
            }
			
            $this->head();
            
            if ($this->get_server_var('HTTP_CONTENT_RANGE')) {
                $files = isset($content[$this->options['param_name']]) ? $content[$this->options['param_name']] : null;
                if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                    $this->header('Range: 0-'.($this->fix_integer_overflow(intval($files[0]->size)) - 1));
                }
            }
			
            $this->body($json);
        }
		
        return $content;
    }
}