<?php

require_once("/var/www/recovery/update/inc/init.php");
require_once("/var/www/recovery/update/inc/utilities.php");


$_command          = 'sudo php /var/www/recovery/update/lib/download_install_myfab.php  > /dev/null & echo $!';
$_response_command = shell_exec ( $_command);
$_pid      = trim(str_replace('\n', '', $_response_command));


$_response_items['pid']     = $_pid;
$_response_items['command'] = $_command;
$_response_items['json_uri'] = str_replace('/var/www', '', MYFAB_UPDATE_MONITOR_FILE);

header('Content-Type: application/json');
echo json_encode($_response_items); 















?>