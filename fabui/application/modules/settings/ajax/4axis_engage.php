<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/utilities.php';

/** CREATE LOG FILES */
$_time                 = $_POST['time'];
$_destination_trace    = TEMP_PATH.'4axis_engage_'.$_time.'.trace';
$_destination_response = TEMP_PATH.'4axis_engage_'.$_time.'.json';


write_file($_destination_trace, '', 'w');
chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
chmod($_destination_response, 0777);


/** EXEC COMMAND */
$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py engage_4axis '.$_destination_trace.' '.$_destination_response.' > /dev/null';
$_output_command = shell_exec ( $_command );
$_pid            = trim(str_replace('\n', '', $_output_command));


sleep(1);
/** RESPONSE */
$_response_items['status'] = 200;

header('Content-Type: application/json');
echo minify(json_encode($_response_items));

