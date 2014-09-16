<?php
require_once '/var/www/fabui/script/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_type    = $argv[2];
$_status  = isset($argv[3]) && $argv[3] != '' ? $argv[3] : 'performed';

/** MACRO TO CALL FOR PRINT */
if($_type == 'print'){
    
	
	$_macro_end_print_trace    = '/var/www/temp/end_print.trace';
	$_macro_end_print_response = '/var/www/temp/end_print.response';
	
	
	if(!file_exists($_macro_end_print_trace)){
		
		write_file($_macro_end_print_trace, '', 'w');
		chmod($_macro_end_print_trace, 0777);
	}
	
	if(!file_exists($_macro_end_print_response)){
		
		write_file($_macro_end_print_response, '', 'w');
		chmod($_macro_end_print_response, 0777);
	}
	
		
    $_command_close = 'sudo python /var/www/fabui/python/gmacro.py end_print '.$_macro_end_print_trace.' '.$_macro_end_print_response.' > /dev/null &';
    shell_exec($_command_close);
    sleep(2);
}


/** LOAD DB */
$db = new Database();

/** GET TASK FROM DB */
$_task = $db->query('select * from sys_tasks where id='.$_task_id);
$_task = $_task[0];

/** LOAD TASK'S ATTRIBUTES */
$_attributes = json_decode($_task['attributes'], TRUE);
$_folder     = $_attributes['folder'];


/** UPDATE TASK */
$_data_update = array();
$_data_update['status']      = $_status;
$_data_update['finish_date'] = 'now()';

$db->update('sys_tasks', array('column' => 'id', 'value' => $_task_id, 'sign' => '='), $_data_update);
$db->close();



if($_type == 'slice'){
    
    /** LOAD DB */
    $db = new Database();
    /** MOVE OUTPUT FILE TO OBJECT FOLDER */
    
    $_id_object     = $_attributes['id_object'];
    $id_file        = $_attributes['id_new_file'];
    $_output        = $_attributes['output'];
    $_configuration = $_attributes['configuration'];
    
    $_output_file_name          = get_name($_output);
    $_output_extension          = get_file_extension($_output);
    $_output_folder_destination = '/var/www/upload/'.str_replace('.', '', $_output_extension).'/';
    $_output_file_name          = set_filename($_output_folder_destination, $_output_file_name);
    
    /** MOVE TO FINALLY FOLDER */
    $_command = 'sudo cp '.$_output.' '.$_output_folder_destination.$_output_file_name;
    shell_exec($_command);
	
	echo $_command.PHP_EOL;
	
    /** ADD PERMISSIONS */
    $_command = 'sudo chmod 746 '.$_output_folder_destination.$_output_file_name;
    shell_exec($_command);
    

    /** INSERT RECORD TO DB */
    //carico X class database
    $data_file['file_name']   = $_output_file_name;
    $data_file['file_path']   = $_output_folder_destination;
    $data_file['full_path']   = $_output_folder_destination.$_output_file_name;
    $data_file['raw_name']    = str_replace($_output_extension, '', $_output_file_name);
    $data_file['orig_name']   = $_output_file_name;
    $data_file['file_ext']    = $_output_extension;
    $data_file['file_size']   = filesize($_output_folder_destination.$_output_file_name);
    $data_file['print_type']  = print_type($_output_folder_destination.$_output_file_name);
    $data_file['note']        = 'Sliced on '.date("F j, Y, g:i a").' with config: '.get_name($_configuration);
	$data_file['insert_date'] = 'now()';
	$data_file['file_type']   = 'text/plain';
      
    /** ADD TASK RECORD TO DB */ 
    
    $db->update('sys_files', array('column' => 'id', 'value' => $id_file, 'sign' => '='), $data_file);
    //$id_file = $db->insert('sys_files', $data_file);
    //unset($data_file);
    
    /** ADD ASSOCIATION OBJ FILE */
    $data['id_obj']  = $_id_object;
    $data['id_file'] = $id_file;
    
    $id_ass = $db->insert('sys_obj_files', $data);
    
    $db->close();
    
}



if($_type == 'scan_r' || $_type=="scan_s" || $_type=="scan_p"){
    
  
    /** LOAD DB */
    $db = new Database();
    
    $id_obj = $_attributes['id_obj'];
    
    if($_attributes['new'] == 'true'){
        
        /** CREATE & ADD OBJ */
        $_obj_data['obj_name']        = $_attributes['obj_name'] == '' ? 'No name object' : $_attributes['obj_name'];
        //$_obj_data['obj_name']        = 'scan_'.$_task_id.'_'.$_attributes['time'];
        $_obj_data['obj_description'] = 'Object created from scanning  on '.date('l jS \of F Y h:i:s A');
        $_obj_data['date_insert']     = 'now()'; 
        $id_obj = $db->insert('sys_objects', $_obj_data);    
    }
    
    /** INSERT ASC FILE RECORD TO DB */
    $_data_file['file_name']   = $_attributes['pprocess_file'];
	$_data_file['file_type']   = 'application/octet-stream';
	$_data_file['file_path']   = '/var/www/upload/asc/';
	$_data_file['full_path']   = '/var/www/upload/asc/'.$_attributes['pprocess_file'];
	$_data_file['raw_name']    = str_replace('.asc', '', $_attributes['pprocess_file']);
	$_data_file['orig_name']   = $_attributes['pprocess_file'];
	$_data_file['client_name'] = $_attributes['pprocess_file'];
	$_data_file['file_ext']    = '.asc';
	$_data_file['file_size']   = filesize($_attributes['folder'].$_attributes['pprocess_file']);
    $_data_file['insert_date'] = 'now()';
    $_data_file['note']        = 'Cloud data file obtained by scanning in '.ucfirst($_attributes['mode_name']).' mode on '.date('l jS \of F Y h:i:s A');
    
    $id_file = $db->insert('sys_files', $_data_file);
    
    /** MOVE ASC FILE TO UPLOAD/ASC */
	rename($_attributes['folder'].$_attributes['pprocess_file'], $_data_file['full_path']);
    
    
    /** ASSOCIATE FILE TO OBJECT */
    $_data_assoc['id_obj']  = $id_obj;
    $_data_assoc['id_file'] = $id_file;
    
    $id_assoc = $db->insert('sys_obj_files', $_data_assoc);
    
    
    /** UPDATE TASK */
    $_attributes['id_obj']  = $id_obj;
    $_attributes['id_file'] = $id_file;
    
    $_data_update['attributes'] = json_encode($_attributes);
    $db->update('sys_tasks', array('column' => 'id', 'value' => $_task_id, 'sign' => '='), $_data_update);
    $db->close();
    
    
      
    
}

/** MESH RECONSTRUCTION */
if($_type == 'mesh'){
    
    
    /** LOAD DB */
    $db = new Database();
    /** MOVE OUTPUT FILE TO OBJECT FOLDER */
    
    
    $_id_object     = $_attributes['id_object'];
    $id_file        = $_attributes['id_new_file'];
    $_output        = $_attributes['output'];
    
    $_output_file_name          = get_name($_output);
    $_output_extension          = get_file_extension($_output);
    $_output_folder_destination = '/var/www/upload/'.str_replace('.', '', $_output_extension).'/';
    $_output_file_name          = set_filename($_output_folder_destination, $_output_file_name);
    
    
    /** MOVE TO FINALLY FOLDER */
    $_command = 'sudo cp '.$_output.' '.$_output_folder_destination.$_output_file_name;
    shell_exec($_command);
    /** ADD PERMISSIONS */
    $_command = 'sudo chmod 746 '.$_output_folder_destination.$_output_file_name;
    shell_exec($_command);
    
    
    /** INSERT RECORD TO DB */
    //carico X class database
    $data_file['file_name']  = $_output_file_name;
    $data_file['file_path']  = $_output_folder_destination;
    $data_file['full_path']  = $_output_folder_destination.$_output_file_name;
    $data_file['raw_name']   = $_attributes['output_raw'];
    $data_file['orig_name']  = $_output_file_name;
    $data_file['file_ext']   = $_output_extension;
    $data_file['file_size']  = filesize($_output_folder_destination.$_output_file_name);
    $data_file['print_type'] = print_type($_output_folder_destination.$_output_file_name);
    $data_file['note']       = 'Reconstructed on '.date("F j, Y, g:i a");
    
    
    /** ADD TASK RECORD TO DB */ 
    $db->update('sys_files', array('column' => 'id', 'value' => $id_file, 'sign' => '='), $data_file);
    
    
    /** ADD ASSOCIATION OBJ FILE */
    $data['id_obj']  = $_id_object;
    $data['id_file'] = $id_file;
    
    $id_ass = $db->insert('sys_obj_files', $data);
    
    $db->close();    
}






/** FIRMWARE INSTALL/UPDATE */
if($_type == 'update_fw'){
    $_command_close = 'sudo python /var/www/fabui/python/gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log > /dev/null &';
    shell_exec($_command_close);
    sleep(2);
}


/** WAIT FOR THE UI TO FINALIZE THE PROCESS */
sleep(7);
/** REMOVE ALL TEMPORARY FILES */
shell_exec('sudo rm -rf '.$_folder);   

?>