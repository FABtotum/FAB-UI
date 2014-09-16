<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** CREATE LOG FILES */
$_time                 = $_POST['time'];
$_destination_trace    = '/var/www/temp/pre_jog'.$_time.'.trace';
$_destination_response = '/var/www/temp/pre_jog'.$_time.'.log';



write_file($_destination_trace, '', 'w');
chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
chmod($_destination_response, 0777);


/** EXEC COMMAND */
$_command        = 'sudo python /var/www/fabui/python/gmacro.py jog_setup '.$_destination_trace.' '.$_destination_response.' > /dev/null & echo $!';
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