<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';



/** GET DATA FROM POST */
$task_id = $_POST['task_id'];



/** LOAD DATABASE */
$db = new Database();

$task = $db->query('select * from sys_tasks where id='.$task_id);


if($task){
		
	$command = 'sudo php '.FABUI_PATH.'script/finalize.php '.$task['id'].' '.$task['type'].' stopped';
	$response = shell_exec($command);
	
	
	$_response_items['command']  = $command;
	$_response_items['response'] = $response;
	
	sleep(1);
    /** RESPONSE */
    header('Content-Type: application/json');
    echo minify(json_encode($_response_items));
}


?>