<?php
require_once '/var/www/lib/config.php';

/** FORCE RESET CONTROLLER */
$_command = 'sudo python '.PYTHON_PATH.'gmacro.py shutdown '.TEMP_PATH.'macro_trace '.TEMP_PATH.'macro_response'; 
shell_exec($_command);

/** SHUTDOWN */
$_command = 'sudo php '.SCRIPT_PATH.'shutdown.php';
shell_exec($_command);

//close session
session_destroy();

shell_exec('sudo poweroff');
$_response_items['status'] = true;


header('Content-Type: application/json');
echo json_encode($_response_items);

?>
