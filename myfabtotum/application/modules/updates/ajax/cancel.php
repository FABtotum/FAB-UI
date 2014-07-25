<?php
require_once '/var/www/myfabtotum/ajax/config.php';
require_once '/var/www/myfabtotum/ajax/lib/utilities.php';
require_once '/var/www/myfabtotum/ajax/lib/database.php';


/** GET POST DATA */
$_id_task = $_POST['id_task'];


/** LOAD DB */
$db    = new Database();
$_task = $db->query('select * from sys_tasks where id='.$_id_task);
$_task = $_task[0];

/** UPDATE TASK */
$_data_update = array();
$_data_update['status']      = 'canceled';
$_data_update['finish_date'] = 'now()';

$db->update('sys_tasks', array('column' => 'id', 'value' => $_id_task, 'sign' => '='), $_data_update);

$db->close();



$_attributes = json_decode($_task['attributes'], TRUE);




/** KILL PROCESS */
$_command = 'sudo kill '.$_attributes['pid'];
shell_exec($_command);

/** REMOVE ALL TEMPORARY FILES */
shell_exec('sudo rm -rf '.$_attributes['folder']);


$_response_items['response'] = true;

header('Content-Type: application/json');
echo minify(json_encode($_response_items)); 







?>