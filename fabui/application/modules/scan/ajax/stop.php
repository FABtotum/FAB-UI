<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
    

/** GET DATA FROM POST */
$_task_id = $_POST['task_id'];

/** LOAD DB */
$db = new Database();

/** GET TASK FROM DB */
$_task = $db->query('select * from sys_tasks where id='.$_task_id);
$_task = $_task[0];

$_attributes = json_decode($_task['attributes'], true);

/** KILLING PROCESSES 
$_command_kill = 'sudo kill '.$_attributes['scan_pid'];
shell_exec ( $_command_kill );

if(isset($_attributes['pprocess_pid'])){
        
    $_command_kill = 'sudo kill '.$_attributes['pprocess_pid'];
    shell_exec ( $_command_kill );
    
}


*/
/** FINALIZE  ---------------------------------------------- */
$_command_finalize = 'sudo php '.FABUI_PATH.'script/finalize.php '.$_task_id. ' scan stopped';
$_output_command   = shell_exec ( $_command_finalize );


$_response_items['command'] = $_command_finalize;
$_response_items['status'] = 'ok';

header('Content-Type: application/json');
echo json_encode($_response_items);    
?>