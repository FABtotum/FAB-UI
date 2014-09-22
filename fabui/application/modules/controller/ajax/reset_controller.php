<?php

/** FORCE RESET CONTROLLER */
$_command = 'sudo python /var/www/fabui/python/force_reset.py';
shell_exec($_command);


$_response_items['status'] = true;

header('Content-Type: application/json');
echo json_encode($_response_items);


?>