<?php

class Database {

	protected $_hostname;
	protected $_username;
	protected $_password;
	protected $_database;
	protected $_db;
	protected $_result;
	protected $_num_rows;
	//protected $_log;

	/**
	 *
	 *
	 */
	public function __construct() {
		$this -> _init();
	}

	/**
	 *
	 *
	 */
	public function _init() {

		$this -> _hostname = DB_HOSTNAME;
		$this -> _username = DB_USERNAME;
		$this -> _password = DB_PASSWORD;
		$this -> _database = DB_DATABASE;
		$this -> _num_rows = 0;

	

		$this -> _db = new mysqli($this -> _hostname, $this -> _username, $this -> _password, $this -> _database);

		if (mysqli_connect_errno()) {
		}

		$this -> _db -> set_charset("utf8");

	}

	/**
	 *
	 *
	 */
	public function query($query) {

		$this -> _result = $this -> _db -> query($query);

		if (!$this -> _result) {
				
			//echo $query.PHP_EOL;
			//echo $this->_db->error;
			//error_log($this -> _db->error, 3, '/var/log/apache2/error.log');
			return false;

		} else {

			if (is_object($this -> _result)) {

				$this -> _num_rows = $this -> _result -> num_rows;

				if ($this -> _result -> num_rows) {

					$rows = array();

					while ($row = $this -> _result -> fetch_assoc()) {
						$rows[] = $row;
					}

					return $rows;

				} else {
					return false;
				}

			}

		}
	}

	public function close() {
		$this -> _db -> close();
	}

	public function insert($table_name, $data) {

		$_query = 'insert into ' . $table_name . ' ';

		$_columns = '(';

		foreach ($data as $key => $value) {
			$_columns .= $table_name . '.' . $key . ',';
		}

		$_columns .= ')';

		$_columns = str_replace(',)', ')', $_columns);

		$_query .= ' ' . $_columns . ' values ';

		$_values = '(';

		foreach ($data as $key => $value) {

			if (is_array($value)) {

				$_val = mysqli_real_escape_string($this -> _db, implode(' ', $value));
			} else {
				$_val = mysqli_real_escape_string($this -> _db, $value);
			}

			$_values .= $this -> quote_value($_val) . ',';

		}

		$_values .= ')';

		$_values = str_replace(',)', ')', $_values);

		$_query .= $_values;

		$_query .= ';';

		$this -> query($_query);

		return $this -> _db -> insert_id;

	}

	public function update($table_name, $condition, $data) {

		$_query = 'update ' . $table_name . ' set ';

		foreach ($data as $key => $value) {

			$_val = mysqli_real_escape_string($this -> _db, $value);

			$_query .= ' ' . $table_name . '.' . $key . ' = ';

			$_query .= $this -> quote_value($value) . ',';

		}

		$_query = rtrim($_query, ",");

		$_query .= ' where ' . $condition['column'] . ' ' . $condition['sign'];

		$_query .= is_numeric($condition['value']) ? $condition['value'] : '\'' . $condition['value'] . '\' ';

		$this -> query($_query);

	}

	public function get_num_rows() {
		return $this -> _num_rows;
	}

	private function quote_value($value) {

		if (is_numeric($value)) {
			return $value;
		}

		if (strtolower($value) == 'now()') {
			return $value;
		}

		if (strcmp(trim(strtolower($value)), 'now()') == 0) {
			return $value;
		}

		return '\'' . $value . '\'';

	}

	public function last_insert_id() {
		return $this -> _db -> insert_id;
	}

}
?>