<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


$type = isset($_POST['type']) ? $_POST['type'] : '';
$post = $_POST;


switch($type){
	case 'additive':
		preparingAdditiveTask($post);
		break;
	case 'subtractive':
		preparingSubtractiveTask($post);
		break;
	case 'laser':
		preparingLaserTask($post);
		break;
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * preparing additive task
 */
function preparingAdditiveTask($data)
{
	$settings = json_decode(file_get_contents(FABUI_PATH.'config/config.json'), TRUE);
	$engage_feeder = isset($data['engage_feeder']) && $data['engage_feeder'] == 1 ? true : false;
	
	$ext_temp = isset($configs['print']['pre-heating']['extruder']) ? $configs['print']['pre-heating']['extruder'] : 150;
	$bed_temp = isset($configs['print']['pre-heating']['bed']) ? $configs['print']['pre-heating']['bed'] : 50;
	//pre-heating bed and nozzle
	shell_exec('sudo python '.PYTHON_PATH.'serial_factory.py -m send -c "M104 S'.$ext_temp.'-M140 S'.$bed_temp.' &"');
	//raise macro
	$raise_macro =  $engage_feeder == true ? 'raise_bed_no_g27' : 'raise_bed';
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m '.$raise_macro.' > /dev/null';
	$output = shell_exec ($command);
	sleep(1);
	$macroResponse = analizeMacroResponse();
	$macroResponse['command'] = $command;
	if($macroResponse['response'] == false) output($macroResponse);
	//pre-print macro
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m check_pre_print > /dev/null';
	$output = shell_exec ($command);
	$macroResponse['command'] = $command;
	$macroResponse = analizeMacroResponse();
	output($macroResponse);
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * preparing subtractive task
 */
function preparingSubtractiveTask($data)
{
	//4th_axis_mode macro
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m 4th_axis_mode > /dev/null';
	$output = shell_exec ($command);
	$macroResponse = analizeMacroResponse();
	if($macroResponse['response'] == false) output($macroResponse);
	//pre-print macro
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m check_pre_mill > /dev/null';
	$output = shell_exec ($command);
	$macroResponse = analizeMacroResponse();
	$macroResponse['command'] = $command;
	output($macroResponse);
	

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * preparing laser task
 */
function preparingLaserTask($data)
{
	//pre-laser macro
	$restart = $data['restart'] == 'true' ? 1 : 0;
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m pre_laser -p1 '.$restart.' > /dev/null';
	$output = shell_exec ($command);
	$macroResponse = analizeMacroResponse();
	$macroResponse['command'] = $command;
	output($macroResponse);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
