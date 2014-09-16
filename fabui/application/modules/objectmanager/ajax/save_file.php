<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

$_file_id      = $_POST['file_id'];
$_file_path    = $_POST["file_path"];
$_file_content = isset($_POST["file_content"]) ? urldecode($_POST["file_content"]) : false;
$_note         = urldecode($_POST["note"]);
$_name         = urldecode($_POST["name"]);


/** SAVE FILE */
if($_file_content != false){
    file_put_contents($_file_path, $_file_content, FILE_USE_INCLUDE_PATH);
}

$_file_size = filesize($_file_path);

$_response_items['success']   = true;
$_response_items['file_size'] = $_file_size;

/** GET TYPE OF PRINT */
$_print_type = print_type($_file_path);


/** SAVE NEW SIZE TO DB */
$db = new Database();

/** UPDATE DATA INFO */
$_data_update['file_size']  = $_file_size;
$_data_update['print_type'] = $_print_type;
$_data_update['note']       = $_note;
$_data_update['raw_name']   = $_name;

$db->update('sys_files', array('column' => 'id', 'value' => $_file_id, 'sign' => '='), $_data_update);
$db->close();


/** JSON RESPONSE */
header('Content-Type: application/json');
echo minify(json_encode($_response_items));
 


?>