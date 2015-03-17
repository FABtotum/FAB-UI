<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


$_obj_id         = $_POST['obj_id'];
$_files_selected = $_POST['files_selected'];
$_output_name    = $_POST['output'];


$_files = array();

/** LOAD DB */
$db = new Database();

/** PREPARING JOIN COMMAND */
$_string_files = '';

foreach($_files_selected as $_file_id){

    $_file_temp = $db->query('select * from sys_files where id='.$_file_id);

    if(isset($_file_temp[0])){
        
        $_file_temp = $_file_temp[0];
        $_string_files .= $_file_temp['full_path'].' ';
        
    }
    
}

if($_output_name == ''){
    
    $_output_name = 'merge_output_file_'.time();
    
}


$_output_name .= '.asc';


$_output_file = TEMP_PATH.$_output_name;


/** EXEC JOING COMMAND */
$_command_join = 'sudo python '.PYTHON_PATH.'join.py '.$_output_file.' '.$_string_files;
$_shell_response = shell_exec($_command_join);




/** MOVING AND INSERTING INTO DB NEW FILE CREATED */
$_output_file_name          = get_name($_output_name);
$_output_extension          = get_file_extension($_output_file_name);
$_output_folder_destination = '/var/www/upload/'.str_replace('.', '', $_output_extension).'/';
$_output_file_name          = set_filename($_output_folder_destination, $_output_file_name);


/** MOVE TO FINALLY FOLDER */
$_command = 'sudo cp '.$_output_file.' '.$_output_folder_destination.$_output_file_name;
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
$data_file['note']       = 'Merged on '.date("F j, Y, g:i a");
$data_file['insert_date'] = 'now()';

/** ADD FILE RECORD TO DB */     
$id_file = $db->insert('sys_files', $data_file);

/** ADD ASSOCIATION OBJ FILE */
$data['id_obj']  = $_obj_id;
$data['id_file'] = $id_file;

$id_ass = $db->insert('sys_obj_files', $data);

$db->close();


/** DELETE TEMPORARY FILE */
unlink($_output_file);


$_response_items = array();

$_response_items['file_id']  = $id_file;
$_response_items['command']  = $_command_join;
$_response_items['response'] = $_shell_response;

sleep(5);
/** RESPONSE */
header('Content-Type: application/json');
echo minify(json_encode($_response_items));

?>