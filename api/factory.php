<?php
/**
 * 
 * @author Krios Mane
 * @version 0.1
 * @license https://opensource.org/licenses/GPL-3.0
 * 
 */
 
 
include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php'; 
include "/var/www/lib/serial.php";


$command = isset($_GET['code']) ? trim($_GET['code']) : '';
$callback = $_GET['callback'];


if($command == ''){
	header('Content-Type: application/json; charset=utf-8');
	echo $callback.'('.json_encode(array('command' => '', 'output' => 'Command not set')).')';
	exit();
}

//load serial class
$ini_array = parse_ini_file(SERIAL_INI);
$serial = new Serial();
$serial->deviceSet($ini_array['port']);
$serial->confBaudRate($ini_array['baud']);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
//send command
$serial->sendMessage($command.PHP_EOL);
$reply = $serial->readPort();
$serial->serialflush();
$serial->deviceClose();
header('Content-Type: application/json; charset=utf-8');
echo $callback.'('.json_encode(array('command' => $command, 'output' => $reply)).')';
?>