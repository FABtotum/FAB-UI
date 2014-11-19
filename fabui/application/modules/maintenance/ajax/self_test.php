<?php
@session_start();
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';
require_once '/var/www/fabui/ajax/lib/database.php';


/** GET POST DATA */
$_remote = $_POST['remote'];

$_type = 'self_test';


/** LOAD DB */
$db    = new Database();

/** ADD TASK */
$_task_data['user']       = $_SESSION['user']['id'];
$_task_data['controller'] = 'maintenance';
$_task_data['type']       = 'self_test';
$_task_data['status']     = 'running';
$_task_data['attributes'] = array();
$_task_data['start_date'] = 'now()';

/** ADD TASK RECORD TO DB */ 
$id_task = $db->insert('sys_tasks', $_task_data);


/** CREATING TASK FILES */
$_time               = time();
$_destination_folder = TASKS_PATH.'settings_'.$_type.'_'.$id_task.'_'.$_time.'/';
$_monitor_file       = $_destination_folder.'settings_'.$_type.'_'.$id_task.'_'.$_time.'.json';
$_trace_file         = $_destination_folder.'settings_'.$_type.'_'.$id_task.'_'.$_time.'.trace';
$_uri_monitor        = '/tasks/settings_'.$_type.'_'.$id_task.'_'.$_time.'/'.'settings_'.$_type.'_'.$id_task.'_'.$_time.'.json';
$_uri_trace          = '/tasks/settings_'.$_type.'_'.$id_task.'_'.$_time.'/'.'settings_'.$_type.'_'.$id_task.'_'.$_time.'.trace';
$_debug_file         = $_destination_folder.'settings_'.$_type.'_'.$id_task.'_'.$_time.'.debug';

mkdir($_destination_folder, 0777);            
/** create print monitor file */
write_file($_monitor_file, '', 'w');
chmod($_monitor_file, 0777);

/** create print trace file */
write_file($_trace_file, '', 'w');
chmod($_trace_file, 0777);

$fabui_version = '';
/** GET TASK FROM DB */
$fabui_version = $db->query('select sys_configuration.value from sys_configuration where sys_configuration.key="fabui_version"');
//$fabui_version = $fabui_version[0];
$fabui_version = $fabui_version['value'];

$_command          = 'sudo python '.RECOVERY_PATH.'python/self_test.py '.$_trace_file.' '.$_monitor_file.' '.$_remote.' 0 '.$id_task.' '.$fabui_version.' 2>'.$_debug_file.' > /dev/null & echo $!';
$_response_command = shell_exec ($_command);
$_pid              = trim(str_replace('\n', '', $_response_command));



/** UPDATE TASKS ATTRIBUTES */
$_attributes_items['pid']         =  $_pid;
$_attributes_items['monitor']     =  $_monitor_file;
$_attributes_items['trace']       =  $_trace_file;
//$_attributes_items['uri_console'] =  $_uri_console;
$_attributes_items['uri_monitor'] =  $_uri_monitor;
$_attributes_items['uri_trace']   =  $_uri_trace;
$_attributes_items['folder']      =  $_destination_folder;

$_data_update['attributes']= json_encode($_attributes_items);
/** UPDATE TASK INFO TO DB */
$db->update('sys_tasks', array('column' => 'id', 'value' => $id_task, 'sign' => '='), $_data_update);
$db->close();


$_response_items['pid']         = $_pid;
$_response_items['command']     = $_command;
$_response_items['json_uri']    = $_uri_monitor;
$_response_items['trace_uri']   = $_uri_trace;
$_response_items['id_task']     = $id_task;
//$_response_items['uri_console'] =  $_uri_console;

header('Content-Type: application/json');
echo json_encode($_response_items); 




?>