<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';
    

/** GET DATA FROM POST */
$_task_id = $_POST['task_id'];


/** LOAD DB */
$db = new Database();

/** GET TASK FROM DB */
$_task = $db->query('select * from sys_tasks where id='.$_task_id);
$_task = $_task[0];

$_attributes = json_decode($_task['attributes'], true);


/** KILLING PROCESSES */
$_command_kill = 'sudo kill '.$_attributes['scan_pid'];
shell_exec ( $_command_kill );

if(isset($_attributes['pprocess_pid'])){
        
    $_command_kill = 'sudo kill '.$_attributes['pprocess_pid'];
    shell_exec ( $_command_kill );
    
}


/** EXEC MACRO END_SCAN --------------------------- */

/** CREATE LOG FILES */
$_time                 = time();
$_destination_trace    = '/var/www/temp/end_scan'.$_time.'.trace';
$_destination_response = '/var/www/temp/end_scan'.$_time.'.log';

write_file($_destination_trace, '', 'w');
chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
chmod($_destination_response, 0777);

/** EXEC */      
$_command        = 'sudo python /var/www/fabui/python/gmacro.py end_scan '.$_destination_trace.' '.$_destination_response;
$_output_command = shell_exec ( $_command );


/** FINALIZE  ---------------------------------------------- */
$_command_finalize = 'sudo php /var/www/fabui/script/finalize.php '.$_task_id. ' scan stopped';
$_output_command   = shell_exec ( $_command_finalize );


$_response_items['status'] = 'ok';

header('Content-Type: application/json');
echo json_encode($_response_items);    
?>