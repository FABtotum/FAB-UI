<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

/** CREATE LOG FILES */
$_time                 = $_POST['time'];
//$_destination_trace    = TEMP_PATH.'pre_jog_'.$_time.'.trace';
//$_destination_response = TEMP_PATH.'pre_jog_'.$_time.'.log';

$_destination_trace    = TEMP_PATH.'macro_trace';
$_destination_response = TEMP_PATH.'macro_response';

write_file($_destination_trace, '', 'w');
chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
chmod($_destination_response, 0777);


/** EXEC COMMAND */
$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py jog_setup '.$_destination_trace.' '.$_destination_response.' ';
$_output_command = shell_exec ( $_command );
$_pid            = trim(str_replace('\n', '', $_output_command));


//unlink($_destination_response);
//unlink($_destination_trace);


/** RESPONSE */
$_response_items['command']            = $_command;
$_response_items['pid']                = $_pid;


/** WAIT JUST 1 SECOND */
header('Content-Type: application/json');
echo minify(json_encode($_response_items));



?>