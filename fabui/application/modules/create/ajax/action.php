<?php
//require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/ajax/lib/utilities.php';

/** SAVE DATA FROM POST */
$_action = $_POST['action'];
$_value = $_POST['value'];
$_pid = $_POST['pid'];
$_data_file = $_POST['data_file'];
$_id_task = $_POST['id_task'];

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
		$_message = 'Command for the speed sent. Value: '.$_value;
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
}

/** WRITE TO DATA FILE */
$_write_return = write_file($_data_file, $_command . PHP_EOL, 'a+');

$_response_items['status'] = 200;
$_response_items['command'] = $_command;
$_response_items['action'] = $_action;
$_response_items['value'] = $_value;
$_response_items['file'] = $_data_file;
$_response_items['return'] = $_write_return;
$_response_items['message'] = $_message;
header('Content-Type: application/json');
echo minify(json_encode($_response_items));
?>