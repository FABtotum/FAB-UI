<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/application/config/production/fabtotum.php';

$head = $_POST['head'];

$pid = $config['heads_pids'][$head];
$description = $config['heads_list'][$head];

if ($pid != '') {

	//init serial
	$serial = new Serial;
	$serial -> deviceSet(PORT_NAME);
	$serial -> confBaudRate(BOUD_RATE);
	$serial -> confParity("none");
	$serial -> confCharacterLength(8);
	$serial -> confStopBits(1);
	$serial -> deviceOpen();
	$serial -> serialflush();
	$serial -> serialflush();
	$serial -> sendMessage($pid . PHP_EOL);
	sleep(0.1);
	$serial -> sendMessage('M500' . PHP_EOL);
	$reply = $serial -> readPort();
	sleep(0.1);
	$serial->sendMessage('M730'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL);
	$serial -> deviceClose();
}

/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH . 'config/config.json'), TRUE);

$_units['hardware']['head']['type'] = $head;
$_units['hardware']['head']['description'] = $description;
$_units['hardware']['head']['max_temp'] = $config['heads_max_temp'][$head];

file_put_contents(FABUI_PATH . 'config/config.json', json_encode($_units));

if (file_exists(FABUI_PATH . 'config/custom_config.json')) {
	$_custom_units = json_decode(file_get_contents(FABUI_PATH . 'config/custom_config.json'), TRUE);
	$_custom_units['hardware']['head']['type'] = $head;
	$_custom_units['hardware']['head']['description'] = $description;
	$_custom_units['hardware']['head']['max_temp'] = $config['heads_max_temp'][$head];
	file_put_contents(FABUI_PATH . 'config/custom_config.json', json_encode($_custom_units));
}

echo json_encode(array('head' => $head, 'pid' => $pid, 'description'=>$description));
?>