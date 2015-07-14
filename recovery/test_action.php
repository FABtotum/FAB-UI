<?php

include '/var/www/lib/config.php';
include "php_serial.class.php";



$destination_trace    = TEMP_PATH.'macro_trace';
$destination_response = TEMP_PATH.'macro_response';

$action = $_POST["action"];



/** EXEC COMMAND */
$command        = 'sudo python '.PYTHON_PATH.'gmacro.py '.$action.' '.$destination_trace.' '.$destination_response.' ';
$output_command = shell_exec ( $command );
$pid            = trim(str_replace('\n', '', $output_command));


/** RESPONSE */
$response_items['command']            = $command;
$response_items['pid']                = $pid;


/** WAIT JUST 1 SECOND */
header('Content-Type: application/json');
echo json_encode($response_items);



?>
