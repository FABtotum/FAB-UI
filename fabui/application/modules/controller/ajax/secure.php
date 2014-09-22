<?php
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/libraries/Serial.php';


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