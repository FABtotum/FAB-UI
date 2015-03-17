<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


/** GET POST DATA */

$_type = $_POST['type'];


/** LOAD DB */
$db    = new Database();

/** ADD TASK */
$_task_data['controller'] = 'updates';
$_task_data['type']       = $_type;
$_task_data['status']     = 'running';
$_task_data['attributes'] = array();
$_task_data['start_date'] = 'now()';
$_task_data['user']       = $_SESSION['user']['id'];

/** ADD TASK RECORD TO DB */ 
$id_task = $db->insert('sys_tasks', $_task_data);

//call socket
shell_exec('sudo php '.SCRIPT_PATH.'/notifications.php &');


/** CREATING TASK FILES */
$_time               = time();
$_destination_folder = TASKS_PATH.'update_'.$_type.'_'.$id_task.'_'.$_time.'/';
//$_monitor_file       = $_destination_folder.'update_'.$_type.'_'.$id_task.'_'.$_time.'.monitor';
$_monitor_file       = TEMP_PATH.'task_monitor.json';
$_debug_file         = $_destination_folder.'update_'.$_type.'_'.$id_task.'_'.$_time.'.debug';
//$_uri_monitor        = '/tasks/update_'.$_type.'_'.$id_task.'_'.$_time.'/'.'update_'.$_type.'_'.$id_task.'_'.$_time.'.monitor';
$_uri_monitor        = '/temp/task_monitor.json';

mkdir($_destination_folder, 0777);            
/** create print monitor file */
write_file($_monitor_file, '', 'w');
chmod($_monitor_file, 0777);


/** SET DOWNLOAD SCRIPT */
switch($_type){
    case 'fabui':
        $_script = 'download_install_fabui.php';
        break;
    case 'marlin':
        $_script = 'download_install_marlin.php';
        break;
}

$_command          = 'sudo php '.FABUI_PATH.'script/'.$_script.' '.$id_task.' '.$_destination_folder.' '.$_monitor_file.' 2>'.$_debug_file.' > /dev/null & echo $!';
$_response_command = shell_exec ( $_command);
$_pid              = trim(str_replace('\n', '', $_response_command));



/** UPDATE TASKS ATTRIBUTES */
$_attributes_items['pid']         =  $_pid;
$_attributes_items['monitor']     =  $_monitor_file;
$_attributes_items['uri_monitor'] =  $_uri_monitor;
$_attributes_items['folder']      =  $_destination_folder;

$_data_update['attributes']= json_encode($_attributes_items);
/** UPDATE TASK INFO TO DB */
$db->update('sys_tasks', array('column' => 'id', 'value' => $id_task, 'sign' => '='), $_data_update);
$db->close();


$_response_items['pid']      = $_pid;
$_response_items['command']  = $_command;
$_response_items['json_uri'] = $_uri_monitor;
$_response_items['id_task']  = $id_task;

header('Content-Type: application/json');
echo json_encode($_response_items); 




?>