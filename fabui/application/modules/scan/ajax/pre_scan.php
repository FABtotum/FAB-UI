<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** CREATE LOG FILES */
$_time                 = time();
$_destination_trace    = TEMP_PATH.'scan_check_'.$_time.'.trace';
$_destination_response = TEMP_PATH.'scan_response_'.$_time.'.log';

write_file($_destination_trace, '', 'w');
chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
chmod($_destination_response, 0777);

/** EXEC COMMAND */
$_command        = 'sudo python '.PYTHON_PATH.'gmacro.py check_pre_scan '.$_destination_trace.' '.$_destination_response.' > /dev/null';
$_output_command = shell_exec ( $_command );
$_pid            = trim(str_replace('\n', '', $_output_command));

/** WAIT JUST 1 SECOND */
sleep(1);

$_response = file_get_contents($_destination_response);
$_trace    = file_get_contents($_destination_trace);
$_trace    = str_replace(PHP_EOL, '<br>', $_trace);

unlink($_destination_response);
unlink($_destination_trace);



/** RESPONSE */
$_response_items['check_trace']        = $_destination_trace;
$_response_items['check_response']     = $_destination_response;
$_response_items['command']            = $_command;
$_response_items['pid']                = $_pid;
$_response_items['url_check_response'] = host_name().'temp/response_'.$_time.'.log';
$_response_items['response']           = str_replace(PHP_EOL, '', $_response) == 'true' ? true : false;
$_response_items['trace']              = $_trace;
//$_response_items['status']             = $_response_items['response']  == true ? 200 : 500;
$_response_items['status']             = 200;


header('Content-Type: application/json');
echo minify(json_encode($_response_items));

?>