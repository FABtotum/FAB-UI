<?php
/*
 * Simple class to control the Raspi Cam
 * @author: FABtotum Dev Team - Krios Mane
 * @package: pi_camera
 * 
 */

require dirname(__FILE__) . '/Parameter.php';


class Camera {

	private $_current_folder       = '';                         // set current folder
	private $_parameters_file      = '';                         // set parameters file
	private $_parameters_file_name = 'raspicam_parameters.json'; // set parameters file name
	private $_command              = '';                         // set the command to execute
	public $_name                  = 'picture';                  // set the name of image


	/**
	 * Constructor - The constructor can be passed an array of config values
	 * @access public
	 * @return void
	 */
	public function __construct($config = array()) {

		$this -> _current_folder = dirname(__FILE__).'/';	
		$this -> _parameters_file = $this -> _current_folder . $this -> _parameters_file_name;

		if (!file_exists($this -> _parameters_file)) {
			$this -> create_parameters_file();
		}

		foreach (json_decode(file_get_contents($this -> _parameters_file), TRUE) as $key => $val) {
			$this -> {strtolower($key)} = new Parameter($key, $val['code'], $val['description']);
		}

		if (count($config) > 0) {
			$this -> initialize($config);
		}
	}
	
	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	public function initialize($config = array()) {

		foreach ($config as $key => $val) {
			if (isset($this -> $key)) {
				$this -> $key -> setValue($val);

			}
		}
		
		

	}
	
	/**
	 * Create the image
	 *
	 * @access	public
	 * @return	void
	 */
	public function doImage() {
		
		//check if is set encoding
		if($this->encoding->getValue() == ''){
			$this->encoding->setValue('jpg');
		}
		
		//check if is set output
		if($this->output->getValue() == ''){
			$this->output->setValue($this->_current_folder.$this->_name.'.'.$this->encoding->getValue());
		}

		$this -> prepare_command();
		shell_exec($this -> _command);

	}
	
	/**
	 * Prepare the command for the execution
	 *
	 * @access	private
	 * @return	void
	 */
	private function prepare_command() {

		$this -> _command = 'sudo raspistill -n -t 1  ';

		foreach ($this as $key => $val) {
			
			
			
			if (is_object($this -> $key)) {
				if (get_class($this -> $key) == 'Parameter') {
					if ($this -> $key -> getValue() != '') {
						$this -> _command .= ' ' . $this -> $key -> getCode() . ' ' . $this -> $key -> getValue();
					}
				}
			}
		}

	}
	
	/**
	 * Return the command
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_command() {
		return $this -> _command;
	}
	
	
	
	/**
	 * Create the parameters file using raspistill help & manual
	 *
	 * @access	private
	 * @return	void
	 */
	private function create_parameters_file() {

		//current_folder permissions
		$permissions = substr(sprintf('%o', fileperms($this -> _current_folder)), -4);
		$file_output = $this -> _current_folder . 'raspistill_info.txt';

		//check if is writable, if not set writable
		if (!is_writable($this -> _current_folder)) {
			shell_exec('sudo chmod 0777 ' . $this -> _current_folder);
		}

		//create help file
		$command_raspistill_help = 'sudo raspistill -? 2>&1 | tee > ' . $file_output;
		shell_exec($command_raspistill_help);

		$help = file($file_output, FILE_SKIP_EMPTY_LINES);

		//delete temporary file
		shell_exec('rm ' . $file_output);

		$parameters = array();

		foreach ($help as $row) {

			if (trim($row) != '') {

				if ($this -> starts_with($row, '-')) {
					$split = explode(':', $row);
					$second_split = explode(',', $split[0]);
					$parameters[trim(str_replace('--', '', $second_split[1]))] = array('code' => $second_split[0], 'description' => trim($split[1]));

				}
			}

		}
		
		
		//add both flip parameter
		$parameters['bflip'] = array('code'=>'-vf -hf', 'description'=>'Set horizontal and vertical flip');
		
		
		$this -> write_file($this -> _parameters_file, json_encode($parameters), 'w');

		//reset permission
		shell_exec('sudo chmod ' . $permissions . ' ' . $this -> _current_folder);
	}

	function starts_with($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE) {

		if (!$fp = @fopen($path, $mode)) {
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);

		return TRUE;
	}

}
?>