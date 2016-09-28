<?php

require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';

$action                           = $_POST['action'];
$settings_type                    = $_POST['type'];
$feeder_extruder_steps_per_unit_a = $_POST['feeder_extruder_steps_per_unit_a'];
//$feeder_extruder_steps_per_unit_e = $_POST['feeder_extruder_steps_per_unit_e'];
$show_feeder                      = $_POST['show_feeder'] == 'yes' ? true : false;
$invert_x_endstop_logic           = $_POST['invert_x_endstop_logic'] == 'yes' ? true : false;
$custom_ovverrides                = $_POST['custom_overrides'];


shell_exec('sudo chmod 0777 ' . FABUI_PATH.'config/');

if(!file_exists(FABUI_PATH.'config/custom_config.json')){
	write_file(FABUI_PATH.'config/custom_config.json', '', 'w');
	shell_exec('sudo chmod 0777 ' . FABUI_PATH.'config/custom_config.json');
}

if(!file_exists(FABUI_PATH.'config/custom_overrides.txt')){
	write_file(FABUI_PATH.'config/custom_overrides.txt', '', 'w');
	shell_exec('sudo chmod 0777 ' . FABUI_PATH.'config/custom_overrides.txt');

}



/** LOAD UNITS */
$units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);
$units['settings_type']  = $settings_type;
/** SAVE UNITS */
file_put_contents(FABUI_PATH.'config/config.json', json_encode($units));



$units['settings_type']          = $settings_type;
//$units['e']                      = $feeder_extruder_steps_per_unit_e;
$units['a']                      = $feeder_extruder_steps_per_unit_a;
$units['feeder']['show']         = $show_feeder;
$units['invert_x_endstop_logic'] = $invert_x_endstop_logic;
$units['custom_overrides']       = FABUI_PATH.'config/custom_overrides.txt';


if($settings_type == 'custom'){
	
	write_file(FABUI_PATH.'config/custom_overrides.txt', strtoupper($custom_ovverrides), 'w');
	write_file(FABUI_PATH.'config/custom_config.json', json_encode($units));
}


if($action == 'exec'){
	shell_exec('python '.PYTHON_PATH.'boot.py');
}

$response['response'] = true;
$response['message'] = $action == 'exec' ? 'Custom settings saved and executed' : 'Custom settings saved';

echo json_encode($response);

?>