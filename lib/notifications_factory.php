<?php

require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

class NotificationsFactory {

	private $_db;

	private $_rows;
	private $_number;
	private $_items;

	public function __construct() {

		$this -> _db = new Database();
		$this -> _number = 0;
		$this -> _items = array();

		$this -> run();

	}

	private function run() {

		$this -> _rows = $this -> _db -> query('select * from sys_tasks where status="running"');

		$this -> _number = $this -> _db -> get_num_rows();
		$this -> _db -> close();

		
		if ($this -> _number > 0) {

			foreach ($this->_rows as $t) {
  
 				$this -> _items[] = $t;
  			}

		}

	}

	public function getTasks() {

		return array('number' => $this -> _number, 'items' => $this -> _items, 'type' => 'notifications');
	}

	public function writeFile() {

		$data = $this -> getTasks();
		write_file(TASK_NOTIFICATIONS, json_encode($data), 'w');
	}

}
?>