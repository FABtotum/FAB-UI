<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';


/** LOAD SERIAL CLASS */
$serial = new Serial();

$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();


$_command = 'M114';


$serial->sendMessage($_command."\r\n");
$response = $serial->readPort();
$serial->serialflush();

$serial->deviceClose();




$position = str_replace('ok', '', $response);
            
$p = explode(' ', $position);

$pos['planner']['x'] = str_replace('X:', '', $p[0]);
$pos['planner']['y'] = str_replace('Y:', '', $p[1]);
$pos['planner']['z'] = str_replace('Z:', '', $p[2]);
$pos['planner']['e'] = str_replace('E:', '', $p[3]);

$pos['stepper']['x'] = $p[6];
$pos['stepper']['y'] = str_replace('Y:', '', $p[7]);
$pos['stepper']['z'] = str_replace('Z:', '', $p[8]);


$_response_items['x'] = $pos['planner']['x'];
$_response_items['y'] = $pos['planner']['y'];
$_response_items['z'] = $pos['planner']['z'];

header('Content-Type: application/json');
echo json_encode($_response_items);



?>