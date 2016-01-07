<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$file_id = $argv[1];

/** LOAD DB */
$db = new Database();

$file = $db->query('select * from sys_files where id='.$file_id);

$file = $file[0];

if($file){
	
	
	$file_path = $file['full_path'];
	/** COMMAND */
	$command = 'sudo nice -n 19 python '.PYTHON_PATH.'printrun/gcoder.py '.$file_path.' j';
	$response = shell_exec($command);

	
	/** UPDATE TASK */
	$_data_update = array();
	$_data_update['attributes']  = $response;
	
	$db->update('sys_files', array('column' => 'id', 'value' => $file_id, 'sign' => '='), $_data_update);
	
}

$db->close();
?>