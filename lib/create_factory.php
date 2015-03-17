<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';
require_once '/var/www/lib/utilities.php';
require_once '/var/www/lib/database.php';

class CreateFactory {

	private $_function;

	private $_action;
	private $_value;
	private $_data_file;
	private $_id_task;
	private $_progress;

	private $_estimated_time;
	private $_progress_steps;
	private $_stats_file;

	private $_command;
	private $_message;

	public function __construct($param) {


		foreach ($param as $key => $value) {

			$key = "_" . $key;
			$this -> $key = $value;

		}

		$this -> _command = "";
		$this -> _message = "";
	}

	public function run() {

		if ($this -> _function == "operation") {
			
			$this -> setCommandMessage();
			$this -> writeDataFile();
			$this -> updateDB();
			$this -> stop();

			$_response_items['status'] = 200;
			$_response_items['command'] = $this -> _command;
			$_response_items['action'] = $this -> _action;
			$_response_items['value'] = $this -> _value;
			$_response_items['file'] = $this -> _data_file;
			$_response_items['return'] = true;
			$_response_items['message'] = $this -> _message;

		}

		if ($this -> _function == "update") {
			$this->update();
			$_response_items['status'] = 200;

		}
		
		return json_encode($_response_items);

	}

	public function setCommandMessage() {

		switch($this->_action) {

			case 'stop' :
				$_command = '!kill';
				$_message = 'Command <b>KILL</b> sent';
				break;
			case 'play' :
				$_command = '!resume';
				$_message = 'Command <b>RESUME</b> sent';
				break;
			case 'pause' :
				$_command = '!pause';
				$_message = 'Command <b>PAUSE</b> sent';
				break;
			case 'temp1' :
				$_command = 'M104 S' . $this -> _value;
				$_message = 'Command for the <b>Extruder temperature</b> sent. Value: ' . $this -> _value;
				break;
			case 'temp2' :
				$_command = 'M140 S' . $this -> _value;
				$_message = 'Command for the Bed temperature sent. Value: ' . $this -> _value;
				break;
			case 'velocity' :
				$_command = 'M220 S' . $this -> _value;
				$_message = 'Speed changed to ' . $this -> _value . '%';
				break;
			case 'light-on' :
				$_command = 'M706 S255';
				$_message = 'Command <b>Light on</b> sent';
				break;
			case 'light-off' :
				$_command = 'M706 S0';
				$_message = 'Command <b>Light off</b> sent';
				break;
			case 'turn-off' :
				$_command = $this -> _value == 'yes' ? '!shutdown_on' : '!shutdown_off';
				$_message = 'Command <b>Shutdown</b> sent';
				break;
			case 'photo' :
				$_command = $this -> _value == 'yes' ? '!photo_yes' : '!photo_no';
				break;
			case 'rpm' :
				$_command = 'M3 S' . $this -> _value;
				$_message = 'Command for the RPM speed sent ' . $this -> _value;
				break;
			case 'send-mail-true' :
				$_command = '';
				$_message = 'A mail will be send at the end of the print';
				break;
			case 'send-mail-false' :
				$_command = '';
				$_message = 'No mail will be send at the end of the print';
				break;
			case 'zup' :
				$_command = '!z_plus';
				$_message = 'Command for moving down the bed sent';
				break;
			case 'zdown' :
				$_command = '!z_minus';
				$_message = 'Command for moving up the bed sent ';
				break;
			case 'fan':
				if($this->_value > 0){
					$this->_value = ($this->_value / 100) * 255;
					$_command = 'M106 S'.$this->_value;
					$_message = 'Command for the fan speed sent';
				}else{
					$command = "M107";
					$_message = 'Command for turn off the fan sent';
				}
				break;
			case 'flow-rate':
				$_command = 'M221 S'.$this->_value;
				$_message = 'Extruder factor override command sent ' . $this -> _value . '%';
				break;
				
				
		}

		$this -> _command = $_command;
		$this -> _message = $_message;

	}

	public function writeDataFile() {

		if ($this -> _command != '') {
			write_file($this -> _data_file, $this -> _command . PHP_EOL, 'a+');
		}

	}

	public function updateDB() {

		if ($this -> _action == 'velocity' || $this -> _action == 'send-mail-false' || $this -> _action == 'send-mail-true' || $this -> _action == 'rpm' || $this->_action == 'flow-rate' || $this->_action == 'fan') {

			$db = new Database();
			$_task = $db -> query('select * from sys_tasks where id=' . $this->_id_task);

			$_attributes = json_decode($_task['attributes'], TRUE);

			switch($this->_action) {

				case 'velocity' :
					$_column = 'speed';
					$_value = $this->_value;
					break;
				case 'send-mail-false' :
					$_column = 'mail';
					$_value = 0;
					break;
				case 'send-mail-true' :
					$_column = 'mail';
					$_value = 1;
					break;
				case 'rpm' :
					$_column = 'rpm';
					$_value = $this->_value;
					break;
				case 'fan':
					$_column = 'fan';
					$_value = $this->_value;
					break;
				case 'flow-rate':
					$_column = 'flow_rate';
					$_value = $this->_value;
					break;
			}	

			//echo $this->_action.PHP_EOL;
			//kill echo $_column.PHP_EOL;
			$_attributes[$_column] = $_value;

			$_data_update['attributes'] = json_encode($_attributes);
			$db -> update('sys_tasks', array('column' => 'id', 'value' => $this->_id_task, 'sign' => '='), $_data_update);
			$db -> close();

		}

	}

	public function stop() {
		
		if ($this -> _action == 'stop' && ($this -> _progress >= 0 && $this -> _progress <= 0.1)) {
			//shell_exec('sudo kill '.$_pid);
			shell_exec('sudo php ' . SCRIPT_PATH . 'finalize.php ' . $this -> _id_task . ' print stopped');
		}
	}

	public function update() {

		/** UPDATE ATTRIBUTES VALUES */
		$_attributes['estimated_time'] = $this->_estimated_time;
		$_attributes['progress_steps'] = $this->_progress_steps;

		/** UPDATE STATS FILE JSON */
		if ($this->_stats_file != "" && file_exists($this->_stats_file)) {
			file_put_contents($this->_stats_file, json_encode($_attributes, JSON_NUMERIC_CHECK), FILE_USE_INCLUDE_PATH);
		}

	}

}
?>