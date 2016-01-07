<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


/** GET POST DATA */
$_mode = $_POST['mode'];


$_time = time();
$_destination_trace    = TEMP_PATH.'macro_trace';
$_destination_response = TEMP_PATH.'macro_response';


write_file($_destination_trace, '', 'w');
//chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
//chmod($_destination_response, 0777);


$_macro_name = 'probe_setup_'.$_mode;


if($_mode == 'prepare'){
	$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py  raise_bed '.$_destination_trace.' '.$_destination_response.' > /dev/null';
	$_output_command = shell_exec ( $_command );	
}

/** EXEC COMMAND */
$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py '.$_macro_name.' '.$_destination_trace.' '.$_destination_response.' > /dev/null';
$_output_command = shell_exec ( $_command );
$_pid            = trim(str_replace('\n', '', $_output_command));



$_response_items['command'] = $_command;
$_response_items['trace']  = file_get_contents($_destination_trace);
$_response_items['response'] = file_get_contents($_destination_response);

//unlink($_destination_trace);
//unlink($_destination_response);

header('Content-Type: application/json');
echo json_encode($_response_items); 

?>