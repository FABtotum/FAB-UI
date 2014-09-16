<?php
//error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** SAVE POST PARAMS */
$_object_id  = $_POST['object'];
$_file_id    = $_POST['file'];
$_print_type = $_POST['print_type'];
$_skip_abl   = $_POST['skip'] == 0 ? false : true;
$_time       = $_POST['time'];
$_calibration = $_POST['calibration'];


/** IF PRINT IS ADDITIVE */
if($_print_type ==  'additive'){
	
	
	$_macro_trace    = '/var/www/temp/print_check_'.$_time.'.trace';
	
	switch($_calibration){
		
		case 'homing':
			$_macro_function = 'home_all';
			$_macro_response = '/var/www/temp/calibration_homing_'.$_time.'.log';
			break;
		case 'abl':
			$_macro_function = 'auto_bed_leveling';
			$_macro_response = '/var/www/temp/auto_bed_leveling'.$_time.'.log';
			break;
		
	}
	
	/** CRAETE TEMPORARY FILES */
	write_file($_macro_trace, '', 'w');
	chmod($_macro_trace, 0777);

	write_file($_macro_response, '', 'w');
	chmod($_macro_response, 0777);
	
	
	/** START MACRO */
	$_command_macro  = 'sudo python /var/www/fabui/python/gmacro.py '.$_macro_function.' '.$_macro_trace.' '.$_macro_response;
	$_output_macro   = shell_exec ( $_command_macro );
	$_pid_macro      = trim(str_replace('\n', '', $_output_macro));
	
	/** WAIT MACRO TO FINISH */
	while(str_replace('<br>', '', file_get_contents($_macro_response)) == ''){   
		sleep(0.5);
	}
	
	
	/** CHECK MACRO RESPONSE */
	if(str_replace('<br>', '', file_get_contents($_macro_response)) != 'true'){
		header('Content-Type: application/json');
		echo json_encode(array('response' => false, 'message' => str_replace(PHP_EOL, '<br>', file_get_contents($_macro_trace)), 'response_text' => file_get_contents($_macro_response)));
		exit();
	}
	
	file_put_contents($_macro_response, '');
	
	$_command_start_print_macro  = 'sudo python /var/www/fabui/python/gmacro.py start_print '.$_macro_trace.' '.$_macro_response;
	$_output_start_print_macro   = shell_exec ( $_command_start_print_macro );
    $_pid_start_print_macro      = trim(str_replace('\n', '', $_output_start_print_macro));
	
	
	/** WAIT MACRO TO FINISH */
    while(str_replace('<br>', '', file_get_contents($_macro_response)) == ''){   
        sleep(0.5);
    }
	
	
	
	
	/** CHECK MACRO RESPONSE */
	if(str_replace('<br>', '', file_get_contents($_macro_response)) != 'true'){
		
		header('Content-Type: application/json');
		echo json_encode(array('response' => false, 'message' => str_replace(PHP_EOL, '<br>', file_get_contents($_macro_trace)), 'response_text' => file_get_contents($_macro_response)));
		exit();
	}
	
	
	
}



/** LOAD DB */
$db    = new Database();
/** LOAD FILE */
$_file = $db->query('select * from sys_files where id='.$_file_id);
$_file = $_file[0];

/** ADD TASK */
$_task_data['user']       = $_SESSION['user']['id'];
$_task_data['controller'] = 'create';
$_task_data['type']       = 'print';
$_task_data['status']     = 'running';
$_task_data['attributes'] = json_encode(array('id_object'=>$_object_id, 'id_file'=>$_file_id));
$_task_data['start_date'] = 'now()';

/** ADD TASK RECORD TO DB */ 
$id_task = $db->insert('sys_tasks', $_task_data);


/** CREATING TASK FILES */
$_time               = time();
$_destination_folder = '/var/www/tasks/print_'.$id_task.'_'.$_time.'/';
$_monitor_file       = $_destination_folder.'print_'.$id_task.'_'.$_time.'.monitor';
$_data_file          = $_destination_folder.'print_'.$id_task.'_'.$_time.'.data';
$_trace_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'.trace';
$_debug_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'.debug';
$_stats_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'_stats.json';
$_uri_monitor        = '/tasks/print_'.$id_task.'_'.$_time.'/'.'print_'.$id_task.'_'.$_time.'.monitor';
$_uri_trace          = '/tasks/print_'.$id_task.'_'.$_time.'/'.'print_'.$id_task.'_'.$_time.'.trace';

mkdir($_destination_folder, 0777);            
/** create print monitor file */
write_file($_monitor_file, '', 'w');
chmod($_monitor_file, 0777);
/** create print data file */
write_file($_data_file, '', 'w');
chmod($_data_file, 0777);
/** create print trace file */
write_file($_trace_file, '', 'w');
chmod($_trace_file, 0777);
/** create print stats file */
write_file($_stats_file, '', 'w');
chmod($_stats_file, 0777);

$_time_monitor = 2;


/** START PROCESS */
$_command        = 'sudo python /var/www/fabui/python/gpusher.py '.$_file['full_path'] .' '.$_monitor_file .' '.$_data_file.' '.$_time_monitor.' '.$_trace_file.' '.$id_task.' 2>'.$_debug_file.' > /dev/null & echo $!';
$_output_command = shell_exec ( $_command );
$_print_pid      = trim(str_replace('\n', '', $_output_command));


/** UPDATE TASKS ATTRIBUTES */
$_attributes_items['pid']         =  $_print_pid;
$_attributes_items['monitor']     =  $_monitor_file;
$_attributes_items['data']        =  $_data_file;
$_attributes_items['trace']       =  $_trace_file;
$_attributes_items['debug']       =  $_debug_file;
$_attributes_items['id_object']   =  $_object_id;
$_attributes_items['id_file']     =  $_file_id;
$_attributes_items['uri_monitor'] =  $_uri_monitor;
$_attributes_items['uri_trace']   =  $_uri_trace;
$_attributes_items['folder']      =  $_destination_folder;
$_attributes_items['stats']       =  $_stats_file;

$_data_update['attributes']= json_encode($_attributes_items);
/** UPDATE TASK INFO TO DB */
$db->update('sys_tasks', array('column' => 'id', 'value' => $id_task, 'sign' => '='), $_data_update);
$db->close();


sleep(2);
//$_json_status = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);

//$status  = json_decode($_json_status, TRUE);
//$percent = isset($status['print']['stats']['percent']) ? floatval($status['print']['stats']['percent']) : floatVal(0);

/** WAIT TRACE TO GET % > 0 
while($percent <= 0.1){
    sleep(2);
    //write_file('/var/www/tasks/log.txt', 'entro ciclo'.PHP_EOL, 'a+');
    $_json_status = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
    //write_file('/var/www/tasks/log.txt', $_json_status.PHP_EOL, 'a+');
    $status  = json_decode($_json_status, TRUE);
    $percent = isset($status['print']['stats']['percent']) ? floatval($status['print']['stats']['percent']) : floatval(0);
     
}
*/
$_json_status = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
$status = json_encode($_json_status);

while($_json_status == ''){
    
    $_json_status = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
    $status = json_encode($_json_status);   
}

/** DELETE TEMPORARY FILES */
//unlink($_macro_trace);
unlink($_start_print_macro_response);
unlink($_macro_response);
            
header('Content-Type: application/json');
echo minify(json_encode(array('response' => true, 'status'=>$status, 'id_task' => $id_task, 'monitor_file'=>$_monitor_file, 'data_file'=>$_data_file, 'trace_file' => $_trace_file, 'command' => $_command, 'uri_monitor'=>$_uri_monitor, 'uri_trace' => $_uri_trace, "stats" => $_stats_file, "folder"=>$_destination_folder)));

?>