<?php
//require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/lib/utilities.php';

/** SAVE DATA FROM POST */
$_action    = $_POST['action'];
$_value     = $_POST['value'];
$_pid       = $_POST['pid'];
$_data_file = $_POST['data_file'];
$_id_task   = $_POST['id_task'];

switch($_action){
    
    case 'stop':
        $_command = '!kill';
    	break;
    case 'play':
    	$_command = '!resume';
    	break;
    case 'pause':
    	$_command = '!pause';
    	break;
    case 'temp1':
    	$_command = 'M104 S'.$_value;
    	break;
    case 'temp2':
    	$_command = 'M140 S'.$_value;
    	break;
    case 'velocity':
    	$_command = 'M220 S'.$_value;
    	break;
    case 'light-on':
        $_command = 'M706 S255';
        break;
    case 'light-off':
        $_command = 'M706 S0';
    case 'turn-off':
        $_command = $_value == 'yes' ? '!shutdown_on' : '!shutdown_off';
        break;
    case 'photo':
        $_command = $value == 'yes' ? '!photo_yes' : '!photo_no';
}

/** WRITE TO DATA FILE */
$_write_return = write_file($_data_file, $_command.PHP_EOL, 'a+');
//$_write_return = file_put_contents($_data_file, $_command.PHP_EOL, FILE_USE_INCLUDE_PATH | FILE_APPEND | LOCK_EX); 


/** LOAD DB */
/*
$db    = new Database();
$_task = $db->query('select * from sys_tasks where id='.$_id_task);
$_task = $_task[0];

$_attributes = json_decode($_task['attributes'], TRUE);

/** UPDATE ATTRIBUTES VALUE */
/*
$_attributes[$_action]      = $_value;				
$_data_update['attributes'] = json_encode($_attributes);

$db->update('sys_tasks', array('column' => 'id', 'value' => $_id_task, 'sign' => '='), $_data_update);
$db->close();
*/
$_response_items['status']  = 200;
$_response_items['command'] = $_command;
$_response_items['action']  = $_action;
$_response_items['value']   = $_value;
$_response_items['file']    = $_data_file;
$_response_items['return']  = $_write_return;         
header('Content-Type: application/json');
echo minify(json_encode($_response_items)); 


?>