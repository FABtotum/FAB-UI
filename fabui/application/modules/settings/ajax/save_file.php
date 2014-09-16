<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

$_file_path    = $_POST["file_path"];
$_file_content = urldecode($_POST["file_content"]);


/** SAVE FILE */
file_put_contents($_file_path, $_file_content, FILE_USE_INCLUDE_PATH);

$_response_items['success'] = true;

/** GET TYPE OF PRINT */
$_print_type = print_type($_file_path);


/** SAVE NEW SIZE TO DB */
$db = new Database();

/** UPDATE DATA INFO */
$_data_update['file_size']  = $_file_size;
$_data_update['print_type'] = $_print_type;

$db->update('sys_files', array('column' => 'id', 'value' => $_file_id, 'sign' => '='), $_data_update);
$db->close();


/** JSON RESPONSE */
echo json_encode($_response_items);
 
?>