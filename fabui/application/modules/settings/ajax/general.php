<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/serial.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


/** GET DATA FROM POST */
$_red         							= $_POST['red'];
$_green       							= $_POST['green'];
$_blue        							= $_POST['blue'];
$_safety_door 							= $_POST['safety_door'];
$_switch                                = $_POST['switch'];
$_feeder_disengage                      = $_POST['feeder_disengage_feeder'];
$_milling_sacrificial_layer_offset      = $_POST['milling_sacrificial_layer_offset'];

$_feeder_extruder_steps_per_unit_a_mode = $_POST['feeder_extruder_steps_per_unit_a_mode'];
$_feeder_extruder_steps_per_unit_e_mode = $_POST['feeder_extruder_steps_per_unit_e_mode'];

$_units['milling']['layer-offset']      = $_milling_sacrificial_layer_offset;
$_both_y_endstops                       = $_POST['both_y_endstops'];
$_both_z_endstops                       = $_POST['both_z_endstops'];
$_upload_api_key                        = $_POST['upload_api_key'];

$_zprobe                             	= $_POST['zprobe'];
$_zmax									= $_POST['zmax'];

$_colors['r'] = $_red;
$_colors['g'] = $_green;
$_colors['b'] = $_blue;

$_feeder['disengage-offset'] = $_feeder_disengage;


shell_exec('sudo chmod 0777 ' . FABUI_PATH.'config/');

/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);

if(!file_exists(FABUI_PATH.'config/custom_config.json')){
	write_file(FABUI_PATH.'config/custom_config.json', json_encode($_units), 'w');
}

$_custom_units = json_decode(file_get_contents(FABUI_PATH.'config/custom_config.json'), TRUE);

/** SET NEW COLOR */
$_units['color']                                = $_custom_units['color'] = $_colors;
$_units['safety']['door']                       = $_custom_units['safety']['door'] = $_safety_door;
$_units['switch']                               = $_custom_units['switch']  = $_switch;
$_units['feeder'] ['disengage-offset']          = $_custom_units['feeder']['disengage-offset'] = $_feeder_disengage;
$_units['milling']['layer-offset']              = $_custom_units['milling']['layer-offset'] = $_milling_sacrificial_layer_offset;
$_units['e'] 		                            = $_feeder_extruder_steps_per_unit_e_mode;
$_units['a'] 		                            = $_feeder_extruder_steps_per_unit_a_mode;
$_units['bothy']	                            = $_custom_units['bothy'] = $_both_y_endstops;
$_units['bothz']	                            = $_custom_units['bothz'] = $_both_z_endstops;
$_units['api']['keys'][$_SESSION['user']['id']] = $_custom_units['api']['keys'][$_SESSION['user']['id']] = $_upload_api_key;

$_units['zprobe']['disable']                    = $_custom_units['zprobe']['disable'] = $_zprobe;
$_units['zprobe']['zmax']                    	= $_custom_units['zprobe']['zmax'] = $_zmax;


file_put_contents(FABUI_PATH.'config/config.json', json_encode($_units));
file_put_contents(FABUI_PATH.'config/custom_config.json', json_encode($_custom_units));

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