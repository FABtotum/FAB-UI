<?php

require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';

class JogFactory {

	private $_serial;
	private $_feedrate;
	private $_step;
	private $_z_step;

	private $_command;
	private $_response;
	
	private $_type;

	public function __construct($feedrate = '', $step = '', $zstep = '') {

		$this -> _serial = new Serial();

		$this -> _feedrate = $feedrate;
		$this -> _step = $step;
		$this -> _z_step = $zstep;
		
		$this->_type = 'serial';

	}

	public function returnResponse() {

		$response_items['type'] = $this->_type;
		
		$data = array();
		
		$data['command'] = $this->_command;
		$data['response'] = $this->_response;
		
		$response_items['data'] = $data;

		return json_encode($response_items);

	}

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

	public function directions($value) {

		$dir['up'] = 'G0 Y+%d F%d';
		$dir['up-right'] = 'GO Y+%1$d X+%1$d F%2$d';
		$dir['up-left'] = 'G0 Y+%1$d X-%1$d F%2$d';
		$dir['down'] = 'G0 Y-%d F%d';
		$dir['down-right'] = 'G0 Y-%1$d X+%1$d F%2$d';
		$dir['down-left'] = 'GO Y-%1$d X-%1$d F%2$d';
		$dir['left'] = 'GO X-%d F%d';
		$dir['right'] = 'GO X+%d F%d';

		$command = sprintf($dir[$value], $this -> _step, $this -> _feedrate);
		$this -> _command = 'G91' . PHP_EOL . $command;
		$this -> exec();

		return $this -> returnResponse();

	}
	
	
	public function zdown(){
		
		$command = 'G0 Z-'.$this->_z_step.' F'.$this->_feedrate;
		$this -> _command = 'G91' . PHP_EOL . $command;		
		$this -> exec();

		return $this -> returnResponse();
		
	}
	
	
	public function zup(){
		
		$command = 'G0 Z+'.$this->_z_step.' F'.$this->_feedrate;
		$this -> _command = 'G91' . PHP_EOL . $command;
		$this -> exec();

		return $this -> returnResponse();
		
	}
	
	public function mdi($value){
		
		
		
		$this->_command = strtoupper($value);
		$this->exec();
		return $this -> returnResponse();
		
	}
	
	
	
	public function extruder_e($value){
		
		$command = 'G0 E'.$value.' F'.$this->_feedrate;
		
		$this -> _command = 'G91' . PHP_EOL . $command;		
		$this -> exec();

		return $this -> returnResponse();	
	}
	
	
	public function extruder_mode($value){
		
		$_units = json_decode(file_get_contents(CONFIG_UNITS), TRUE);
		
		$_mode['a'] = 'M92 E'.$_units['a'];
		$_mode['e'] = 'M92 E'.$_units['e'].PHP_EOL.'G92 E0';
		
		$this->_command = $_mode[$value];
		
		$this -> exec();

		return $this -> returnResponse();
		
	}


	public function ext_temp($value){
		
		$this->_command = 'M104 S'.$value;
		$this -> exec();
		return $this -> returnResponse();
		
	}

	public function bed_temp($value){
		
		$this->_command = 'M140 S'.$value;
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	public function get_temperature(){
		
		$this->_type = 'temperature';
		
		$this->_command = 'M105';
		
		$this -> exec();
		return $this -> returnResponse();
		
	}
	
	
	public function zero_all(){
		$this->_command = "G92 X0 Y0 Z0 E0";
		$this -> exec();
		return $this -> returnResponse();
	}
	
	public function position(){
	
		$this->_command = 'M114';
		$this -> exec();
		return $this -> returnResponse();
			
	}
	
	
	public function lights($value){
			
		$_mode['on'] = 'M706 S255';
		$_mode['off'] = 'M706 S0';
		
		$this->_command = $_mode[$value];
		
		$this -> exec();

		return $this -> returnResponse();
	}
	
	
	public function motors($value){
			
		$_mode['on'] = 'M17';
		$_mode['off'] = 'M18';
		
		$this->_command = $_mode[$value];
		
		$this -> exec();

		return $this -> returnResponse();
	}
	
	public function rotation($value){
		
		
		$command = "G90".PHP_EOL."G0 E";
		
		$this->_command = $command.$value;
		
		$this -> exec();

		return $this -> returnResponse();
		
	}
	
	
	public function zero_all_pre_mill(){
			
		$this->_command = "G92 X0 Y0 Z0 E0" . PHP_EOL . "G90";
		$this -> exec();
		return $this -> returnResponse();
	}
	
	
	
	public function secure($mode){
		
		$command = $mode == true ? 'M730'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL : 'M730'.PHP_EOL.'M731'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL;
		
		$this->_command = $command;
		
		$this -> exec();
		
		return $this -> returnResponse();
		
	}

}
?>