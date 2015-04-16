<?php

//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';



/** READ POST DATA */
$red   = $_POST['red'];
$green = $_POST["green"];
$blue  = $_POST["blue"];

/** LOAD SERIAL CLASS */
$serial = new Serial();



/** LOAD SERIAL CLASS */
$serial = new Serial();

// M701 S[0-255] - Ambient Light, Set Red
// M702 S[0-255] - Ambient Light, Set Green
// M703 S[0-255] - Ambient Light, Set Blue

$command_value = 'M701 S'.$red.PHP_EOL.'M702 S'.$green.PHP_EOL.'M703 S'.$blue.PHP_EOL;


$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial->sendMessage($command_value);
$reply = $serial->readPort();
$serial->serialflush();
$serial->deviceClose();


$_response_items['command'] = $command_value;
$_response_items['response'] = $reply;

header('Content-Type: application/json');
echo json_encode($_response_items);

?>