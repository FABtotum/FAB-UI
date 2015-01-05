<?php
//error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/application/libraries/Serial.php';

/** GET DATA FROM POST */
$_red   = $_POST['red'];
$_green = $_POST['green'];
$_blue  = $_POST['blue'];
$_safety_door = $_POST['safety_door'];


$_colors['r'] = $_red;
$_colors['g'] = $_green;
$_colors['b'] = $_blue;


/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

/** SET NEW COLOR */
$_units['color']          = $_colors;
$_units['safety']['door'] = $_safety_door;
file_put_contents(FABUI_PATH.'config/config.json', json_encode($_units));


/** LOAD SERIAL CLASS */
$serial = new Serial();

$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

// safety door 
$_command = 'M732 S'.$_safety_door;

$serial->sendMessage($_command."\r\n");
$response = $serial->readPort();
$serial->serialflush();

$serial->deviceClose();

echo json_encode(array('result'=>true));

?>