<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

// get data from post
$mode = $_POST['mode'];

$macro_name = 'probe_setup_'.$mode;

if($mode == 'prepare'){
	
	$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m raise_bed  > /dev/null';
	$output = shell_exec ($command);
	sleep(1);
	$macroResponse = analizeMacroResponse();
	if($macroResponse['response'] == false) output($macroResponse);
}

$command = 'sudo python '.PYTHON_PATH.'gmacro_new.py -m '.$macro_name.' > /dev/null';
$output = shell_exec ($command);
$macroResponse = analizeMacroResponse();
output($macroResponse);
?>
