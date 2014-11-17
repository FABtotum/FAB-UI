<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/fabui/ajax/lib/utilities.php';

/** SAVE DATA FROM POST */
$_action    = $_POST['action'];
$_value     = $_POST['value'];
$_pid       = $_POST['pid'];
$_data_file = $_POST['data_file'];
$_id_task   = $_POST['id_task']; 
$_progress  = $_POST['progress'];
switch($_action) {

	case 'stop' :
		$_command = '!kill';
		$_message = 'Command <b>KILL</b> sent';
		break;
	case 'play' :
		$_command = '!resume';
		$_message = 'Command <b>RESUME</b> sent';
		break;
	case 'pause' :
		$_command = '!pause';
		$_message = 'Command <b>PAUSE</b> sent';
		break;
	case 'temp1' :
		$_command = 'M104 S' . $_value;
		$_message = 'Command for the <b>Extruder temperature</b> sent. Value: '.$_value;
		break;
	case 'temp2' :
		$_command = 'M140 S' . $_value;
		$_message = 'Command for the Bed temperature sent. Value: '.$_value;
		break;
	case 'velocity' :
		$_command = 'M220 S' . $_value;
		$_message = 'Speed changed to '.$_value.'%';
		break;
	case 'light-on' :
		$_command = 'M706 S255';
		$_message = 'Command <b>Light on</b> sent';
		break;
	case 'light-off' :
		$_command = 'M706 S0';
		$_message = 'Command <b>Light off</b> sent';
		break;
	case 'turn-off' :
		$_command = $_value == 'yes' ? '!shutdown_on' : '!shutdown_off';
		$_message = 'Command <b>Shutdown</b> sent';
		break;
	case 'photo' :
		$_command = $value == 'yes' ? '!photo_yes' : '!photo_no';
		break;
	case 'rpm' :
		$_command = 'M3 S' . $_value;
		$_message = 'Command for the RPM speed sent'.$_value;
		break;
	case 'send-mail-true':
		$_command = '';
		$_message = 'A mail will be send at the end of the print';
		break;
	case 'send-mail-false':
		$_command = '';
		$_message = 'No mail will be send at the end of the print';
		break;
	case 'zup':
		$_command = '!z_plus';
		$_message = 'Command for moving down the bed sent';
		break;
	case 'zdown':
		$_command = '!z_minus';
		$_message = 'Command for moving up the bed sent';
		break;
		
}

/** WRITE TO DATA FILE */
if($_command != ''){
	$_write_return = write_file($_data_file, $_command . PHP_EOL, 'a+');
}



if($_action == 'velocity' || $_action == 'send-mail-false' || $_action == 'send-mail-true' || $_action == 'rpm'){
	
	$db    = new Database();
	$_task = $db->query('select * from sys_tasks where id='.$_id_task);
	
	$_attributes = json_decode($_task['attributes'], TRUE);
	
	
	switch($_action){
		
		case 'velocity':
			$_column = 'speed';
			break;
		case 'send-mail-false':
			$_column = 'mail';
			$_value = 0;
			break;
		case 'send-mail-true':
			$_column = 'mail';
			$_value = 1;
			break;
		case 'rpm':
			$_column = 'rpm';
			break;
		
	}
	
	$_attributes[$_column] = $_value;
	
	$_data_update['attributes'] = json_encode($_attributes);
    $db->update('sys_tasks', array('column' => 'id', 'value' => $_id_task, 'sign' => '='), $_data_update);
    $db->close();  
	
}


if($_action == 'stop' && ($_progress >= 0 && $_progress <= 0.1) ){
	
	
	shell_exec('sudo kill '.$_pid);
	
	shell_exec('sudo php /var/www/fabui/script/finalize.php '.$_id_task.' print stopped');
	
	$_response_items['step']  = 'entrato qua';
	
	
}


$_response_items['status']  = 200;
$_response_items['command'] = $_command;
$_response_items['action']  = $_action;
$_response_items['value']   = $_value;
$_response_items['file']    = $_data_file;
$_response_items['return']  = $_write_return;
$_response_items['message'] = $_message;

header('Content-Type: application/json');
echo minify(json_encode($_response_items));
?>