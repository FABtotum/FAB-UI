<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** SAVE POST PARAMETERS */
$_type        = $_POST['type'];
$_file        = $_POST['file'];
$_preset      = $_POST['preset'];
$_output      = $_POST['output'];
$_output_type = $_POST['output_type'];
$_object      = $_POST['object'];
$_id_file     = $_POST['id_file'];


switch($_type){
    
    case 'gcode':
        create_gcode($_object, $_id_file, $_file, $_preset, $_output.$_output_type);
        break;
    case 'stl':
        create_stl($_object, $_id_file, $_file, $_output.'.stl');
}


/** FUNCTION CREATE GCODE */
function create_gcode($_object, $_id_file,  $file, $configuration, $_output){
	
	
	
	$configuration = str_replace('myfabtotum', 'fabui', $configuration);

    /** LOAD DB */
    $db    = new Database();
   
   /** ADD TASK */
    $_task_data['user']       = $_SESSION['user']['id'];
    $_task_data['controller'] = 'objectmanager';
    $_task_data['type']       = 'slice';
    $_task_data['status']     = 'running';
    //$_task_data['attributes'] = json_encode(array('id_object'=>$_object, 'id_file' => $_id_file));
    $_task_data['start_date'] = 'now()';
    
    /** ADD TASK RECORD TO DB */ 
    $id_task = $db->insert('sys_tasks', $_task_data);
    
    
    
    /** ADD RECORD FOR THE OUTPUT FILE */
    $_id_new_file = $db->insert('sys_files', array());
    
    
    /** CREATING TASK FILES */
    $_time               = time();
    $_destination_folder = TASKS_PATH.'slice_'.$id_task.'_'.$_time.'/';
    $_monitor_file       = $_destination_folder.'slice_'.$id_task.'_'.$_time.'.monitor';
    $_trace_file         = $_destination_folder.'slice_'.$id_task.'_'.$_time.'.trace';
    $_debug_file         = $_destination_folder.'log.debug';
	$_slicer_config      = $_destination_folder.'slice_config_'.$id_task.'_'.$_time.'.ini';
    
    mkdir($_destination_folder, 0777);
	chmod($_destination_folder, 0777);            
    /** create print monitor file */
    write_file($_monitor_file, '', 'w');
    chmod($_monitor_file, 0777);
    /** create print trace file */
    write_file($_trace_file, '', 'w');
    chmod($_trace_file, 0777);
	
	
	/** CREATE TEMP SLICER CONFIG FILE */
	 write_file($_slicer_config, $configuration, 'w');
	 chmod($_slicer_config, 0777);
    
    
    /** START PROCESS */
    $_command        = 'sudo python '.PYTHON_PATH.'slic3r_wrapper.py -t'.$_trace_file.' -l'.$_monitor_file.' -i'.$file.' -o'.$_destination_folder.$_output.' -c'.$_slicer_config.' -k'.$id_task.' 2>'.$_debug_file.' > /dev/null & echo $!';
    $_output_command = shell_exec ( $_command );
    $_wrapper_pid    = trim(str_replace('\n', '', $_output_command));
    sleep(10);
	

    $_json_monitor = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
    $_monitor      = json_encode($_json_monitor);
    
    while($_json_monitor == ''){
    
        $_json_monitor = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
        $_monitor      = json_encode($_json_monitor);   
    }
	
	
	
	$task = $db->query('select * from sys_tasks where id='.$id_task);
	
	
	$_attributes_items = json_decode($task['attributes'], TRUE);
	
	
    /** UPDATE TASKS ATTRIBUTES */
    $_attributes_items['pid']           =  $_wrapper_pid;
    $_attributes_items['monitor']       =  $_monitor_file;
    $_attributes_items['trace']         =  $_trace_file;
    $_attributes_items['debug']         =  $_debug_file;
    $_attributes_items['output']        =  $_destination_folder.$_output;
    $_attributes_items['folder']        =  $_destination_folder;
    $_attributes_items['id_object']     =  $_object;
    $_attributes_items['id_file']       =  $_id_file;
    $_attributes_items['id_new_file']   =  $_id_new_file;
    //$_attributes_items['configuration'] =  json_encode($configuration);
    
     
    $_data_update['attributes']= json_encode($_attributes_items);
    /** UPDATE TASK INFO TO DB */
    $db->update('sys_tasks', array('column' => 'id', 'value' => $id_task, 'sign' => '='), $_data_update);
	$db->close();
	

	$_response_items['slicer_pid'] = $_attributes_items['slicer_pid'];
	$_response_items['perl_pid']   = $_attributes_items['perl_pid'];
    $_response_items['monitor_json'] = $_monitor;
    $_response_items['pid']          = $_wrapper_pid;
    $_response_items['monitor']      = $_monitor_file;
    $_response_items['trace']        = $_trace_file;
    $_response_items['command']      = $_command;
    $_response_items['monitor_uri']  = '/tasks/slice_'.$id_task.'_'.$_time.'/slice_'.$id_task.'_'.$_time.'.monitor';
    $_response_items['trace_uri']    = '/tasks/slice_'.$id_task.'_'.$_time.'/slice_'.$id_task.'_'.$_time.'.trace';
    $_response_items['id_new_file']  = $_id_new_file;
	$_response_items['task_id']  = $id_task;
     
    sleep(1);
    /** RESPONSE */
    header('Content-Type: application/json');
    echo minify(json_encode($_response_items));
    
}







function create_stl($_object, $_id_file,  $file, $_output){
    
    
    
    /** LOAD DB */
    $db    = new Database();
    
    /** ADD TASK */
    $_task_data['user']       = $_SESSION['user']['id'];
    $_task_data['controller'] = 'objectmanager';
    $_task_data['type']       = 'meshlab';
    $_task_data['status']     = 'running';
    //$_task_data['attributes'] = json_encode(array('id_object'=>$_object, 'id_file' => $_id_file));
    $_task_data['start_date'] = 'now()';
    
    
    /** ADD TASK RECORD TO DB */ 
    $id_task = $db->insert('sys_tasks', $_task_data);
    
    /** ADD RECORD FOR THE OUTPUT FILE */
    $_id_new_file = $db->insert('sys_files', array());
    
    
    $_new_output_name = str_replace(' ', '_', $_output);
    
    
     /** CREATING TASK FILES */
    $_time               = time();
    $_destination_folder = TASKS_PATH.'mesh_'.$id_task.'_'.$_time.'/';
    $_monitor_file       = $_destination_folder.'mesh_'.$id_task.'_'.$_time.'.json';
    $_trace_file         = $_destination_folder.'mesh_'.$id_task.'_'.$_time.'.trace';
    $_debug_file         = $_destination_folder.'log.debug';
   
   
    mkdir($_destination_folder, 0777);            
    /** create print monitor file */
    write_file($_monitor_file, '', 'w');
    chmod($_monitor_file, 0777);
    /** create print trace file */
    write_file($_trace_file, '', 'w');
    chmod($_trace_file, 0777);
    
    
    /** START PROCESS */
    $_command        = 'sudo python '.PYTHON_PATH.'meshlab_wrapper.py -t'.$_trace_file.' -l'.$_monitor_file.' -i'.$file.' -o'.$_destination_folder.$_new_output_name.' -s/root/meshlab_script.mlx -k'.$id_task.' 2>'.$_debug_file.' > /dev/null & echo $!';
    $_output_command = shell_exec ( $_command );
    $_mesh_pid       = intval(trim(str_replace('\n', '', $_output_command)))+1;
    
    
    
    /** UPDATE TASKS ATTRIBUTES */
    $_attributes_items['pid']           =  $_mesh_pid;
    $_attributes_items['monitor']       =  $_monitor_file;
    $_attributes_items['trace']         =  $_trace_file;
    $_attributes_items['debug']         =  $_debug_file;
    $_attributes_items['output']        =  $_destination_folder.$_new_output_name;
    $_attributes_items['output_raw']    =  $_output;
    $_attributes_items['folder']        =  $_destination_folder;
    $_attributes_items['id_object']     =  $_object;
    $_attributes_items['id_file']       =  $_id_file;
    $_attributes_items['id_new_file']   =  $_id_new_file;
    
    
    $_data_update['attributes']= json_encode($_attributes_items);
    /** UPDATE TASK INFO TO DB */
    $db->update('sys_tasks', array('column' => 'id', 'value' => $id_task, 'sign' => '='), $_data_update);
    $db->close();
    
    
    $_json_monitor = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
    $_monitor      = json_encode($_json_monitor);
    
    while($_json_monitor == ''){
    
        $_json_monitor = file_get_contents($_monitor_file, FILE_USE_INCLUDE_PATH);
        $_monitor      = json_encode($_json_monitor);   
    }
	
	
    
    $_response_items['monitor_json'] = $_monitor;
    $_response_items['pid']          = $_mesh_pid;
    $_response_items['monitor']      = $_monitor_file;
    $_response_items['trace']        = $_trace_file;
    $_response_items['command']      = $_command;
    $_response_items['monitor_uri']  = '/tasks/mesh_'.$id_task.'_'.$_time.'/mesh_'.$id_task.'_'.$_time.'.json';
    $_response_items['trace_uri']    = '/tasks/mesh_'.$id_task.'_'.$_time.'/mesh_'.$id_task.'_'.$_time.'.trace';
    $_response_items['id_new_file']  = $_id_new_file;

    $_response_items['object'] = $_object;
    $_response_items['id_file'] = $_id_file;
    $_response_items['file'] = $file;
    $_response_items['output'] = $_output;
    
    
    sleep(1);
    /** RESPONSE */
    header('Content-Type: application/json');
    echo minify(json_encode($_response_items));
    
    
}


?>