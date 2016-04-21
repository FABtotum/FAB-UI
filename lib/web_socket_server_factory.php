<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';
require_once '/var/www/lib/jog_factory.php';
require_once '/var/www/lib/create_factory.php';
require_once '/var/www/lib/notifications_factory.php';

class WebSocketServerFactory {

	private $_data;
	private $_type;

	public function __construct() {

		$this -> _response = array();
	}

	private function returnResponse() {

		$response_items['type'] = $this -> _type;
		$response_items['data'] = $this -> _data;

		return json_encode($response_items);

	}

	public function getTasks($data = '') {

		$notifications = new NotificationsFactory();

		$this -> _type = 'task';
		$this -> _data = $notifications -> getTasks();

		return $this -> returnResponse();

	}

	public function getInternet($data = '') {

		$this -> _type = 'internet';
		$this -> _data = is_internet_avaiable();

		return $this -> returnResponse();

	}

	public function serial($data = '') {

		if (!file_exists(LOCK_FILE)) {
			
			$JogFactory = new JogFactory($data['feedrate'], $data['step'], $data['z_step'], $data['extruderFeedrate']);

			$function = $data['func'];

			if (method_exists($JogFactory, $function)) {
				return $JogFactory -> $function($data['value']);
			} else {
				return '{"type": "error", "error": "Serial Unknown function "}';
			}
		}else{
			return '{"type": "warning", "message": "Printer now is busy"}';
		}

	}

	public function getTrace($param) {

		$content = '';
		$dataType = '';
		$type = '';

		switch($param['type']) {

			case 'task' :
				if (file_exists(TASK_TRACE)) {
					$content = file_get_contents(TASK_TRACE);
				}
				$dataType = 'trace';
				$type = 'task';
				break;
			case 'macro' :
				if (file_exists(MACRO_TRACE)) {
					$content = file_get_contents(MACRO_TRACE);
				}
				$dataType = 'trace';
				$type = 'macro';
				break;
		}

		$data['type'] = $dataType;
		$data['content'] = $content;

		$this -> _type = $type;
		$this -> _data = $data;

		return $this -> returnResponse();

	}

	function create($data) {

		$CreateFactory = new CreateFactory($data);

		$this -> _type = 'create';
		$this -> _data = $CreateFactory -> run();

		return $this -> returnResponse();
	}

	function secure($param) {

		$mode = $param['mode'] == 1 ? true : false;

		$JogFactory = new JogFactory();

		$response = $JogFactory -> secure($mode);

		$this -> _type = 'security';
		$this -> _data = $response;
		
		
		write_file('/var/www/temp/fab_ui_safety.json', '', 'w+');
		return $this -> returnResponse();
	}

	function getUsb() {

		$this -> _type = 'system';

		$data['type'] = 'usb';
		$data['status'] = is_usb_inserted();
		$data['alert'] = false;

		$this -> _data = $data;
		return $this -> returnResponse();

	}

}
?>