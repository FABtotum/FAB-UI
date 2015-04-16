<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$task_id    = $argv[1];
$slicer_pid = $argv[2];

/** LOAD DB */
$db = new Database();

$task = $db->query('select * from sys_tasks where id='.$task_id);

if($task){
	
	$attributes              = json_decode($task['attributes'], TRUE);
	$attributes['slicer_pid'] = $slicer_pid == '' ? '-' : $slicer_pid;
	$attributes['perl_pid']   = intval(str_replace(PHP_EOL, '', trim(shell_exec('sudo pidof perl'))));
	/** UPDATE TASK */
	$_data_update = array();
	$_data_update['attributes']  = json_encode($attributes);
	$db->update('sys_tasks', array('column' => 'id', 'value' => $task_id, 'sign' => '='), $_data_update);
	
	
		
}
$db->close();
?>