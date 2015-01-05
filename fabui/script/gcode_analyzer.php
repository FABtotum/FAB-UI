<?php
require_once '/var/www/fabui/script/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$file_id = $argv[1];

/** LOAD DB */
$db = new Database();

$file = $db->query('select * from sys_files where id='.$file_id);

if($file){
	
	
	$file_path = $file['full_path'];
	/** COMMAND */
	$command = 'sudo python '.PYTHON_PATH.'printrun/gcoder.py '.$file_path.' j';
	$response = shell_exec($command);

	
	/** UPDATE TASK */
	$_data_update = array();
	$_data_update['attributes']  = $response;
	
	$db->update('sys_files', array('column' => 'id', 'value' => $file_id, 'sign' => '='), $_data_update);
	
}

$db->close();
?>