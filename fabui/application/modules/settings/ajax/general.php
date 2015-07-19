<?php

/* Session start */
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/serial.php';

/* Get data from post */
$_red = $_POST['red'];
$_green = $_POST['green'];
$_blue = $_POST['blue'];
$_safety_door = $_POST['safety_door'];
$_switch = $_POST['switch'];
$_feeder_disengage = $_POST['feeder_disengage_feeder'];
/*
$_feeder_extruder_steps_per_unit_a_mode = $_POST['feeder_extruder_steps_per_unit_a_mode'];
$_feeder_extruder_steps_per_unit_e_mode = $_POST['feeder_extruder_steps_per_unit_e_mode'];
*/
$_milling_sacrificial_layer_offset = $_POST['milling_sacrificial_layer_offset'];
$_both_y_endstops = $_POST['both_y_endstops'];
$_both_z_endstops = $_POST['both_z_endstops'];
$_upload_api_key = $_POST['upload_api_key'];

$_colors['r'] = $_red;
$_colors['g'] = $_green;
$_colors['b'] = $_blue;

$_feeder['disengage-offset'] = $_feeder_disengage;

/* Get units */
$_units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), true);

/* Set new color */
$_units['color'] = $_colors;
/* Set safety door warning */
$_units['safety']['door'] = $_safety_door;
/* Set X axis home position */
$_units['switch'] = $_switch;
/* Set filament feeder offset */
$_units['feeder'] = $_feeder;
/*
$_feeder_extruder_steps_per_unit_a_mode = $_POST['feeder_extruder_steps_per_unit_a_mode'];
$_feeder_extruder_steps_per_unit_e_mode = $_POST['feeder_extruder_steps_per_unit_e_mode'];
*/
/* Set Milling */
$_units['milling']['layer-offset'] = $_milling_sacrificial_layer_offset;

/* Set Endstop error handling */
$_units['bothy'] = $_both_y_endstops;
$_units['bothz'] = $_both_z_endstops;

/* Set User API key */
$_units['api']['keys'][$_SESSION['user']['id']] = $_upload_api_key;

/* Save printer config json file */
file_put_contents(FABUI_PATH.'config/config.json', json_encode($_units));

/* Load serial class */
$serial = new Serial();
/* Set up comm with Totumduino */
$serial->deviceSet(PORT_NAME);
$serial->confBaudRate(BOUD_RATE);
$serial->confParity('none');
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

/* Push safety door warning */
$_command = 'M732 S'.$_safety_door;
$serial->sendMessage($_command."\r\n");
$response = $serial->readPort();

/* Push X axis home position */
$_command = 'M714 S'.$_switch;
$serial->sendMessage($_command."\r\n");
$response = $serial->readPort();

/* Close comm to Totumduino */
$serial->serialflush();
$serial->deviceClose();

echo json_encode(array('result' => true));

?>
