<?php
//error_reporting(E_ALL);
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/serial.php';



$security_file['code'] = '';
$security_file['type'] = '';

file_put_contents('/var/www/temp/fab_ui_safety.json', json_encode($security_file));


$mode = $_POST['mode'] == 1 ? true : false;

/** LOAD SERIAL CLASS */
$serial = new Serial();
$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$command = $mode == true ? 'M730'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL : 'M730'.PHP_EOL.'M731'.PHP_EOL.'M999'.PHP_EOL.'M728'.PHP_EOL;
$serial->sendMessage($command);
$response = $serial->readPort();
$serial->serialflush();
$serial->deviceClose();




$_response_items['command']  = $command;
$_response_items['response'] = $response;

header('Content-Type: application/json');
echo json_encode($_response_items);



?>