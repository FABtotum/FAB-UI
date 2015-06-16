<?php
require_once '/var/www/lib/config.php';

/** FORCE RESET CONTROLLER */
$_command = 'sudo python '.PYTHON_PATH.'force_reset.py';
shell_exec($_command);

/** SHUTDOWN */
$_command = 'sudo python '.PYTHON_PATH.'gmacro.py shutdown '.TEMP_PATH.'macro_trace '.TEMP_PATH.'macro_response'; 
shell_exec($_command);

sleep(5);
//close session
session_destroy();

shell_exec('sudo reboot');
$_response_items['status'] = true;

header('Content-Type: application/json');
echo json_encode($_response_items);

?>