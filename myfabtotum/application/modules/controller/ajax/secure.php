<?php
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/application/libraries/Serial.php';


/** LOAD SERIAL CLASS */
$serial = new Serial();

$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();


$_command = 'M105';


$serial->sendMessage('M730'.PHP_EOL.'M999'.PHP_EOL);
$response = $serial->readPort();
$serial->serialflush();

$serial->deviceClose();

$_response_items['response'] = $response;

header('Content-Type: application/json');
echo json_encode($_response_items);



?>