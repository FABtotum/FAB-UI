<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

$ini_array = parse_ini_file(SERIAL_INI);


/** GET DATA FROM POST */
$_red         							= $_POST['red'];
$_green       							= $_POST['green'];
$_blue        							= $_POST['blue'];
$_safety_door 							= $_POST['safety_door'];
$_switch                                = $_POST['switch'];
$_feeder_disengage                      = isset($_POST['feeder_disengage_feeder']) ? $_POST['feeder_disengage_feeder'] : '';
$_milling_sacrificial_layer_offset      = $_POST['milling_sacrificial_layer_offset'];

$_feeder_extruder_steps_per_unit_a_mode = $_POST['feeder_extruder_steps_per_unit_a_mode'];
//$_feeder_extruder_steps_per_unit_e_mode = $_POST['feeder_extruder_steps_per_unit_e_mode'];

$_print_preheating_extruder             = $_POST['print_preheating_extruder'];
$_print_preheating_bed                  = $_POST['print_preheating_bed'];
$_print_calibration                     = $_POST['print_calibration'];

$_units['milling']['layer-offset']      = $_milling_sacrificial_layer_offset;
$_both_y_endstops                       = $_POST['both_y_endstops'];
$_both_z_endstops                       = $_POST['both_z_endstops'];
$_upload_api_key                        = $_POST['upload_api_key'];

$_zprobe                             	= $_POST['zprobe'];
$_zmax									= $_POST['zmax'];

$_collision_warning                     = $_POST['collision_warning'];



$_colors['r'] = $_red;
$_colors['g'] = $_green;
$_colors['b'] = $_blue;



$_feeder['disengage-offset'] = $_feeder_disengage;


shell_exec('sudo chmod 0777 ' . FABUI_PATH.'config/*');

/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);


/** SET NEW COLOR */
$_units['color']                                = $_custom_units['color'] = $_colors;
$_units['safety']['door']                       = $_custom_units['safety']['door'] = $_safety_door;
$_units['safety']['collision-warning']          = $_custom_units['safety']['collision-warning'] = $_collision_warning;
$_units['switch']                               = $_custom_units['switch']  = $_switch;
$_units['feeder'] ['disengage-offset']          = $_custom_units['feeder']['disengage-offset'] = $_feeder_disengage;
$_units['milling']['layer-offset']              = $_custom_units['milling']['layer-offset'] = $_milling_sacrificial_layer_offset;
//$_units['e'] 		                            = $_feeder_extruder_steps_per_unit_e_mode;
$_units['a'] 		                            = $_feeder_extruder_steps_per_unit_a_mode;
$_units['bothy']	                            = $_custom_units['bothy'] = $_both_y_endstops;
$_units['bothz']	                            = $_custom_units['bothz'] = $_both_z_endstops;
$_units['api']['keys'][$_SESSION['user']['id']] = $_custom_units['api']['keys'][$_SESSION['user']['id']] = $_upload_api_key;

$_units['zprobe']['disable']                    = $_custom_units['zprobe']['disable'] = $_zprobe;
$_units['zprobe']['zmax']                    	= $_custom_units['zprobe']['zmax'] = $_zmax;

$_units['print']['pre-heating']['extruder'] =  $_print_preheating_extruder;
$_units['print']['pre-heating']['bed']      =  $_print_preheating_bed;
$_units['print']['calibration']             =  $_print_calibration;

file_put_contents(FABUI_PATH.'config/config.json', json_encode($_units));


$response['python']   = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "M732 S'.$_safety_door.'-M734 S'.$_collision_warning.'-M714 S'.$_switch.'-M500-M300"'));
$response['result'] = true;


echo json_encode($response);

?>