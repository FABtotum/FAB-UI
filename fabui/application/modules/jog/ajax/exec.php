<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/jog_factory.php';

/** READ POST DATA */
$function = isset($_POST['function']) ? $_POST['function']  : "";
$value = isset($_POST['value']) ? $_POST['value']  : "";
$time = isset($_POST['time']) ? $_POST['time']  : "";
$step = isset($_POST['step']) ? $_POST['step']  : "";
$zstep = isset($_POST['z_step']) ? $_POST['z_step']  : "";
$feedrate = isset($_POST['feedrate']) ? $_POST['feedrate']  : ""; 
$macro = isset($_POST['macro']) ? $_POST['macro']  : 'false'; 
$macro = $macro == 'false' ? false : true;



if (!$macro) {
	$jogFactory = new JogFactory($feedrate, $step, $zstep);
	if (method_exists($jogFactory, $function)) {
		echo $jogFactory -> $function($value);
	}
}else{
	
	$macros['home_all_axis'] = 'home_all';
	$macros['bed-align'] = 'auto_bed_leveling';
	
	if(!in_array($function, $macros)){
		
	}
	
	$_destination_trace    = TEMP_PATH.'macro_trace';
	$_destination_response = TEMP_PATH.'macro_response';
	
	
	write_file($_macro_trace, '', 'w');
	chmod($_macro_trace, 0777);

	write_file($_macro_response, '', 'w');
	chmod($_macro_response, 0777);

	$_command_macro = 'sudo python '.PYTHON_PATH.'gmacro.py ' . $macros[$function] . ' ' . $_destination_trace . ' ' . $_destination_response . ' & echo $!';
	$_output_macro = shell_exec($_command_macro);
	$_pid_macro = trim(str_replace('\n', '', $_output_macro));


	$data['command'] =  $macros[$function];
	$data['command_macro'] = $_command_macro;
	$data['response'] = file_get_contents($_macro_response, FILE_USE_INCLUDE_PATH);
	$_response_items['type'] = 'serial';
	$_response_items['data'] = $data;
	
	header('Content-Type: application/json');
	echo minify(json_encode($_response_items));
}
?>