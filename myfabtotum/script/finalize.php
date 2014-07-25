<?php
require_once '/var/www/myfabtotum/script/config.php';
require_once '/var/www/myfabtotum/ajax/lib/database.php';
require_once '/var/www/myfabtotum/ajax/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_type    = $argv[2];

/** MACRO TO CALL FOR PRINT */
if($_type == 'print'){
    
    $_command_close = 'sudo python /var/www/myfabtotum/python/gmacro.py end_print /var/www/temp/kill.trace /var/www/temp/kill.log > /dev/null &';
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
$_data_update['status']      = 'performed';
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
    /** ADD PERMISSIONS */
    $_command = 'sudo chmod 746 '.$_output_folder_destination.$_output_file_name;
    shell_exec($_command);
    

    /** INSERT RECORD TO DB */
    //carico X class database
    $data_file['file_name']  = $_output_file_name;
    $data_file['file_path']  = $_output_folder_destination;
    $data_file['full_path']  = $_output_folder_destination.$_output_file_name;
    $data_file['raw_name']   = str_replace($_output_extension, '', $_output_file_name);
    $data_file['orig_name']  = $_output_file_name;
    $data_file['file_ext']   = $_output_extension;
    $data_file['file_size']  = filesize($_output_folder_destination.$_output_file_name);
    $data_file['print_type'] = print_type($_output_folder_destination.$_output_file_name);
    $data_file['note']       = 'Sliced on '.date("F j, Y, g:i a").' with config: '.get_name($_configuration);
    
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

/** WAIT FOR THE UI TO FINALIZE THE PROCESS */
sleep(7);
/** REMOVE ALL TEMPORARY FILES */
shell_exec('sudo rm -rf '.$_folder);

?>