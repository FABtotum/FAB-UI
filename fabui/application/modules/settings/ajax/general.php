<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';

/** GET DATA FROM POST */
$_red         = $_POST['red'];
$_green       = $_POST['green'];
$_blue        = $_POST['blue'];
$_safety_door = $_POST['safety_door'];
$_switch      = $_POST['switch'];
$_feeder_disengage = $_POST['feeder_disengage_feeder'];
$_feeder_extruder_steps_per_unit = $_POST['feeder_extruder_steps_per_unit'];

$_colors['r'] = $_red;
$_colors['g'] = $_green;
$_colors['b'] = $_blue;

$_feeder['disengage-offset'] = $_feeder_disengage;

/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

/** SET NEW COLOR */
$_units['color']          = $_colors;
$_units['safety']['door'] = $_safety_door;
$_units['switch']         = $_switch;
$_units['feeder']         = $_feeder;
$_units['e'] 		  = $_feeder_extruder_steps_per_unit;

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

//switch
$_command = 'M714 S'.$_switch;
$serial->sendMessage($_command."\r\n");
$response = $serial->readPort();

$serial->serialflush();

$serial->deviceClose();

echo json_encode(array('result'=>true));

?>
