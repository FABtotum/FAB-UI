<?php

/**
 * @author FABTeam Dev Team - Krios Mane
 * 
 */
 
// -----------------------------------------

require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';


 /*
  * 
  * JogFactory Class
  * 
  * Simple class to interface the most commons commands used in the jog
  * 
  */
  

class JogFactory {

	private $_serial;         // Serial object used for the communications with the serial port
	private $_feedrate = 300; // feedrate
	private $_step     = 10;  // X,Y Step
	private $_z_step   = 10;  // Z step
	private $_command;        // command sent to the serial port
	private $_response;       // serial port response
	private $_type;           // type
	private $_extruder_feedrate = 300;
	
	private $_detail_response = '';
	
	

	/**
	 * Constructor - Sets default valuue
	 */
	public function __construct($feedrate = '', $step = '', $zstep = '', $extrude_feedrate = '') {

		$this -> _serial = new Serial();
		$this -> _feedrate = $feedrate;
		$this -> _step = $step;
		$this -> _z_step = $zstep;
		$this ->_extruder_feedrate = $extrude_feedrate;
		$this->_type = 'serial';

	}


	/**
	 * Get the response of the serial port
	 *
	 * @access	public
	 * @return	json string
	 */
	public function returnResponse() {

		$response_items['type'] = $this->_type;
		
		$data = array();
		
		$data['command']  = $this->_command;
		$data['response'] = $this->_response;
		$data['detail']   = $this->_detail_response;
		
		$response_items['data'] = $data;

		return json_encode($response_items);

	}


	/**
	 * Exec the command
	 *
	 * @access	public
	 * @return	void
	 */
	public function exec() {
		
		$this -> _serial -> deviceSet(PORT_NAME);
		$this -> _serial -> confBaudRate(BOUD_RATE);
		$this -> _serial -> confParity("none");
		$this -> _serial -> confCharacterLength(8);
		$this -> _serial -> confStopBits(1);
		$this -> _serial -> deviceOpen();
		
		$this -> _serial -> sendMessage($this -> _command . PHP_EOL);
		$this -> _response = $this -> _serial -> readPort();
		
		$this -> _serial -> serialflush();
		$this -> _serial -> deviceClose();
		

	}
	
	
	/**
	 * Exec move direction
	 *
	 * @access	public
	 * @return	json string
	 */
	public function directions($value) {


		$dir['up'] = 'G0 Y+%.2f F%.2f';
		$dir['up-right'] = 'GO Y+%1$.2f X+%1$.2f F%2$.2f';
		$dir['up-left'] = 'G0 Y+%1$.2f X-%1$.2f F%2$.2f';
		$dir['down'] = 'G0 Y-%.2f F%.2f';
		$dir['down-right'] = 'G0 Y-%1$.2f X+%1$.2f F%2$.2f';
		$dir['down-left'] = 'GO Y-%1$.2f X-%1$.2f F%2$.2f';
		$dir['left'] = 'GO X-%.2f F%.2f';
		$dir['right'] = 'GO X+%.2f F%.2f';

		$command = sprintf($dir[$value], $this -> _step, $this -> _feedrate);
		$this -> _command = 'G91' . PHP_EOL . $command.PHP_EOL.'G90';
		$this -> exec();

		return $this -> returnResponse();

	}
	
	
	/**
	 * Move Z down
	 *
	 * @access	public
	 * @return	json string
	 */
	public function zdown(){
		
		$command = 'G0 Z-'.$this->_z_step.' F'.$this->_feedrate;
		$this -> _command = 'G91' . PHP_EOL . $command;		
		$this -> exec();

		return $this -> returnResponse();
		
	}
	
	
	/**
	 * Move Z up
	 *
	 * @access	public
	 * @return	json string
	 */
	public function zup(){
		
		$command = 'G0 Z+'.$this->_z_step.' F'.$this->_feedrate;
		$this -> _command = 'G91' . PHP_EOL . $command;
		$this -> exec();

		return $this -> returnResponse();
		
	}
	
	
	/**
	 * Exec general command
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function mdi($value){
	
		$this->_command = strtoupper($value);
		$this->exec();
		return $this -> returnResponse();
		
	}
	
	
	
	/**
	 * Set extruder value (in e mode)
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function extruder_e($value){
		
		$command = 'G0 E'.$value.' F'.$this ->_extruder_feedrate;
		$this -> _command = 'G91' . PHP_EOL . $command;		
		$this -> exec();
		return $this -> returnResponse();	
	}
	
	
	/**
	 * Set extruder mode
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function extruder_mode($value){
		
		$_units = file_exists(CUSTOM_CONFIG_UNITS) ? json_decode(file_get_contents(CUSTOM_CONFIG_UNITS), TRUE) : json_decode(file_get_contents(CONFIG_UNITS), TRUE);
		
		$_mode['a'] = 'M92 E'.$_units['a'].PHP_EOL.'G92 E0';
		$_mode['e'] = 'M92 E'.$_units['e'].PHP_EOL.'G92 E0';
		
		$this->_command = $_mode[$value];
		
		$this -> exec();

		return $this -> returnResponse();
		
	}
	
	
	/**
	 * get Nozzle temperature
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function ext_temp($value){
		
		$this->_command = 'M104 S'.$value;
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	
	/**
	 * get bed temperature
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function bed_temp($value){
		
		$this->_command = 'M140 S'.$value;
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	
	/**
	 * get tempertures
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function get_temperature(){
		
		$this->_type = 'temperature';
		
		$this->_command = 'M105';
		
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	/**
	 * set zero all
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function zero_all(){
		$this->_command = "G92 X0 Y0 Z0 E0";
		$this -> exec();
		return $this -> returnResponse();
	}
	
	
	/**
	 * Get Position
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function position(){
	
		$this->_command = 'M114';
		$this -> exec();
		return $this -> returnResponse();
			
	}
	
	
	/**
	 * Set Lights On/Off (on => 255, off => 0)
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function lights($value){
			
		$_mode['on'] = 'M706 S255';
		$_mode['off'] = 'M706 S0';
		
		$this->_command = $_mode[$value];
		
		$this -> exec();

		return $this -> returnResponse();
	}
	
	
	/**
	 * Set Motors On/Off
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function motors($value){
			
		$_mode['on'] = 'M17';
		$_mode['off'] = 'M18';
		
		$this->_command = $_mode[$value];
		
		$this -> exec();

		return $this -> returnResponse();
	}
	
	
	/**
	 * Rotate
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function rotation($value){
		
		$command = "G90".PHP_EOL."G0 E";
		$this->_command = $command.$value;
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	
	/**
	 * Set zero all before start a subtractive print
	 *
	 * @access	public
	 * @return	json string
	 */
	public function zero_all_pre_mill(){
			
		$this->_command = "G92 X0 Y0 Z0 E0" . PHP_EOL . "G90";
		$this -> exec();
		return $this -> returnResponse();
	}
	
	
	
	/**
	 * Secure
	 *
	 * @param  $mode
	 * @access	public
	 * @return	json string
	 */
	public function secure($mode){
		
		$command = $mode == true ? 'M730'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL : 'M730'.PHP_EOL.'M731'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL;
		
		$this->_command = $command;
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	
	/**
	 * Set Fan On/Off
	 *
	 * @param  $value
	 * @access	public
	 * @return	json string
	 */
	public function fan($value){
			
		$_mode['on'] = 'M106';
		$_mode['off'] = 'M107';
		
		$this->_command = $_mode[$value];
		$this ->_detail_response = 'Fan '.$value;
		
		$this -> exec();

		return $this -> returnResponse();
	}
	
	
	/**
	 * Read EEPROM setting
	 *
	 * @access	public
	 * @return	json string
	 */
	public function eeprom(){
			
		$this->_command = 'M503';
		$this -> exec();
		return $this -> returnResponse();
	}
	
	
	

}
?>