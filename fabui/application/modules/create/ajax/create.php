<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

/** SAVE POST PARAMS */
$_object_id   = $_POST['object_id'];
$_file_id     = $_POST['file'];
$_print_type  = $_POST['print_type'];
//$_skip_abl    = $_POST['skip'] == 0 ? false : true;
$_time        = $_POST['time'];
$_calibration = $_POST['calibration'];



/** LOAD DB */
$db    = new Database();
/** LOAD FILE */
$_file = $db->query('select * from sys_files where id='.$_file_id);
$_file = $_file[0];


/** IF PRINT IS ADDITIVE */
if($_print_type ==  'additive'){
	$do_macro        = TRUE;
	
	switch($_calibration){
		
		case 'homing':
			$_macro_function = 'home_all';
			$_macro_response = TEMP_PATH.'calibration_homing_'.$_time.'.log';
			$do_macro        = FALSE;
			break;
		case 'abl':
			$_macro_function = 'auto_bed_leveling';
			$_macro_response = TEMP_PATH.'auto_bed_leveling'.$_time.'.log';
			$do_macro        = TRUE;
			break;
	}
	
	
	$_macro_response = TEMP_PATH.'macro_response';
	$_macro_trace    = TEMP_PATH.'macro_trace';
	
	
	/** CRAETE TEMPORARY FILES */
	write_file($_macro_trace, '', 'w');
	//chmod($_macro_trace, 0777);

	write_file($_macro_response, '', 'w');
	//chmod($_macro_response, 0777); 
	
	if($do_macro){
		
		/** START MACRO */
		$_command_macro  = 'sudo python '.PYTHON_PATH.'gmacro.py '.$_macro_function.' '.$_macro_trace.' '.$_macro_response;
		$_output_macro   = shell_exec ( $_command_macro );
		$_pid_macro      = trim(str_replace('\n', '', $_output_macro));
		
		/** WAIT MACRO TO FINISH */
		while(str_replace(PHP_EOL, '', file_get_contents($_macro_response)) == ''){   
			sleep(0.2);
		}
		
		
		/** CHECK MACRO RESPONSE */
		if(str_replace(PHP_EOL, '', file_get_contents($_macro_response)) != 'true'){
			header('Content-Type: application/json');
			echo json_encode(array('response' => false, 'message' => str_replace(PHP_EOL, '<br>', file_get_contents($_macro_trace)), 'response_text' => file_get_contents($_macro_response)));
			exit();
		}	
	}
	
	file_put_contents($_macro_response, '');
	
	/** GET TEMPERATURES FROM GCODE FILE */
	$temperatures = json_decode(shell_exec('sudo python '.PYTHON_PATH.'read_temperatures.py -f "'.$_file['full_path'].'"'), TRUE);
	
	
	$_command_start_print_macro  = 'sudo python '.PYTHON_PATH.'gmacro.py start_print '.$_macro_trace.' '.$_macro_response.' --ext_temp '.intval($temperatures['extruder']).' --bed_temp '.intval($temperatures['bed']);
	$_output_start_print_macro   = shell_exec ( $_command_start_print_macro );
    $_pid_start_print_macro      = trim(str_replace('\n', '', $_output_start_print_macro));
	
	
	/** WAIT MACRO TO FINISH */
    while(str_replace(PHP_EOL, '', file_get_contents($_macro_response)) == ''){   
        sleep(0.2);
    }
	
	/** CHECK MACRO RESPONSE */
	if(str_replace(PHP_EOL, '', file_get_contents($_macro_response)) != 'true'){
		
		header('Content-Type: application/json');
		echo json_encode(array('response' => false, 'message' => str_replace(PHP_EOL, '<br>', file_get_contents($_macro_trace)), 'response_text' => file_get_contents($_macro_response)));
		exit();
	}
	
	
	
}else{
	
	//if is subtractive model
	$_macro_response = TEMP_PATH.'macro_response';
	$_macro_trace    = TEMP_PATH.'macro_trace';
	
	/** CRAETE TEMPORARY FILES */
	write_file($_macro_trace, '', 'w');
	chmod($_macro_trace, 0777);

	write_file($_macro_response, '', 'w');
	chmod($_macro_response, 0777); 
	
	$_command_start_print_macro  = 'sudo python '.PYTHON_PATH.'gmacro.py start_subtractive_print '.$_macro_trace.' '.$_macro_response;
	$_output_start_print_macro   = shell_exec ( $_command_start_print_macro );
 		
	/** WAIT MACRO TO FINISH */
    while(str_replace(PHP_EOL, '', file_get_contents($_macro_response)) == ''){   
        sleep(0.2);
    }	
}



/** ADD TASK */
$_task_data['user']       = $_SESSION['user']['id'];
$_task_data['controller'] = 'make';
$_task_data['type']       = $_print_type ==  'additive' ? 'print' : 'mill';;
$_task_data['status']     = 'running';
$_task_data['id_object']  = $_object_id;
$_task_data['id_file']    = $_file_id;
$_task_data['attributes'] = '';
$_task_data['start_date'] = 'now()';

/** ADD TASK RECORD TO DB */ 
$id_task = $db->insert('sys_tasks', $_task_data);

//call socket
shell_exec('sudo php '.SCRIPT_PATH.'/notifications.php &');


/** CREATING TASK FILES */
$_time               = time();
$_destination_folder = TASKS_PATH.'print_'.$id_task.'_'.$_time.'/';

$_monitor_file       = TEMP_PATH.'task_monitor.json';

$_data_file          = $_destination_folder.'print_'.$id_task.'_'.$_time.'.data';

$_trace_file         = TEMP_PATH.'task_trace';

$_debug_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'.debug';
$_stats_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'_stats.json';
$_attributes_file    = $_destination_folder.'print_'.$id_task.'_'.$_time.'_attributes.json';

$_uri_monitor        = '/temp/task_monitor.json';
$_uri_trace          = '/temp/task_trace';


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

write_file($_attributes_file, '', 'w');
chmod($_attributes_file, 0777);
/** create temp gcode file */
//write_file($_gcode_file, '', 'w');
//chmod($_gcode_file, 0777);

$_time_monitor = 2;
//clean up memory
shell_exec('sudo sh -c "echo 1 >/proc/sys/vm/drop_caches"');
sleep(3);

/** START PROCESS */
if($_print_type == 'additive'){
	$_command = 'sudo python '.PYTHON_PATH.'gpusher_fast.py "'.$_file['full_path'].'"  '.$_data_file.' '.$id_task.' --ext_temp '.intval($temperatures['extruder']).' --bed_temp '.intval($temperatures['bed']).' 2>'.$_debug_file.' > /dev/null & echo $!';
}else{
	$_command = 'sudo python '.PYTHON_PATH.'g_mill.py "'.$_file['full_path'].'" '.$_data_file.' '.$id_task.' 2>'.$_debug_file.' > /dev/null & echo $!';
}



$_output_command = shell_exec ( $_command );
$_print_pid      = intval(trim(str_replace('\n', '', $_output_command))) + 1;


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
$_attributes_items['speed']       =  100;
$_attributes_items['print_type']  =  $_print_type;
$_attributes_items['z_override']  =  0;

file_put_contents($_attributes_file, json_encode($_attributes_items));

//$_data_update['attributes']= json_encode($_attributes_items);
$_data_update['attributes']= $_attributes_file;
/** UPDATE TASK INFO TO DB */
$db->update('sys_tasks', array('column' => 'id', 'value' => $id_task, 'sign' => '='), $_data_update);
$db->close();


sleep(2);

$_json_status = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
$status = json_encode($_json_status);

while($_json_status == ''){	
    $_json_status = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
    $status = json_encode($_json_status);   
}

header('Content-Type: application/json');
echo minify(json_encode(array('response' => true, 'status'=>$status, 'id_task' => $id_task, 'monitor_file'=>$_monitor_file, 'data_file'=>$_data_file, 'trace_file' => $_trace_file, 'command' => $_command, 'uri_monitor'=>$_uri_monitor, 'uri_trace' => $_uri_trace, "stats" => $_stats_file, "folder"=>$_destination_folder, 'attributes_file'=>$_attributes_file)));

?>