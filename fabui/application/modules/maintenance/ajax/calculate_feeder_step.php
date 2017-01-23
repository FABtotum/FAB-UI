<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/jog_factory.php';

$action = $_POST['action'];

if($action == 'calculate'){
		
	$actual_step         = $_POST['actual_step'];
	$filament_to_extrude = $_POST['filament_to_extrude'];
	$filament_extruded   = $_POST['filament_extruded'];
	$new_step_value       = floatval($actual_step) / ( floatval($filament_extruded) / floatval($filament_to_extrude)) ;

	$response['old_step']            = $actual_step;
	$response['filament_to_extrude'] = $filament_to_extrude;
	$response['filament_extruded']   = $filament_extruded;
	
	
}elseif($action == 'change'){
	$new_step_value = $_POST['new_step'];
}elseif($action == 'extrude'){
	$filament_to_extrude = $_POST['filament_to_extrude'];
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m extrude -p1 '.$filament_to_extrude;
	shell_exec($command);
	$response['command'] = $command;
	echo json_encode($response);
	exit();
}

if($new_step_value > 2000){
	$m203E = "12.00";
}else{
	$m203E = "23.00";
}


$response['new_step'] = $new_step_value;
$response['python']   = json_decode(shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "M92 E'.$new_step_value.'-M203 E'.$m203E.'-M500"'));

/*
$jogFactory = new JogFactory();
$jogFactory -> mdi('M92 E'.$new_step_value.PHP_EOL.'M500');
*/

/** GET UNITS */
$_units = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);
$_custom_units = json_decode(file_get_contents(FABUI_PATH.'config/custom_config.json'), TRUE);

$_units['e']        = $new_step_value;
$_custom_units['e'] = $new_step_value;

file_put_contents(FABUI_PATH.'config/config.json',        json_encode($_units));
file_put_contents(FABUI_PATH.'config/custom_config.json', json_encode($_custom_units));

echo json_encode($response);

?>


