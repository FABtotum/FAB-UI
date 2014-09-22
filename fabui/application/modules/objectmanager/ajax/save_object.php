<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

$object_id     = $_POST['object_id'];
$name          = $_POST["name"];
$description   = $_POST['description'];
$private       = $_POST['private'] == 1 ? 0 : 1;

/** SAVE TO DB */
$db = new Database();

/** UPDATE DATA INFO */
$_data_update['obj_name']        = $name;
$_data_update['obj_description'] = $description;
$_data_update['private']         = $private;
$_data_update['date_updated']    = 'now()';

$db->update('sys_objects', array('column' => 'id', 'value' => $object_id, 'sign' => '='), $_data_update);
$db->close();


$_response_items['success'] = true;

/** JSON RESPONSE */
header('Content-Type: application/json');
echo minify(json_encode($_response_items));
 

?>