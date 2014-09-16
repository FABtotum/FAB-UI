<?php
/***
 *
*
*
*
*
*/
error_reporting(E_ALL);
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', true );

require_once("/var/www/recovery/update/inc/init.php");
require_once("/var/www/recovery/update/inc/utilities.php");

$_type      = $_POST['type'];
$_version   = $_POST['version'];

switch($_type){

	case 'myfab':
		$_file_name = MYFAB_DOWNLOAD_TARGET_FILE.$_type.'.zip';
		$_url       = MYFAB_DOWNLOAD_URL.MYFAB_DOWNLOAD_FILE;
		$_monitor   = MYFAB_DOWNLOAD_MONITOR_FILE;
		
		break;

	case 'marlin':
		
		$_file_name = MARLIN_DOWNLOAD_TARGET_FILE.$_type.'.hex';
		$_url       = MARLIN_DOWNLOAD_URL.MARLIN_DOWNLOAD_FILE;
		$_monitor   = MARLIN_DOWNLOAD_MONITOR_FILE;
		break;

}

/** CRATE MONITOR FILE */
//$_handler = fopen($_monitor, 'w') or die("can't open file: ".$_monitor);
//fclose($_handler);
write_file($_monitor, '', 'w');



$_command          = 'sudo php /var/www/recovery/update/lib/download_command.php '.$_file_name.' '.$_url.' '.$_monitor.' > /dev/null & echo $!';
$_response_command = shell_exec ( $_command);

//sleep(2);

$_response_items['file_name'] = $_file_name;
$_response_items['url'] = $_url;
$_response_items['monitor'] = $_monitor;
$_response_items['command'] = $_command;
$_response_items['status'] = 'ok';

sleep(1);

/*
$_json_status = file_get_contents($_monitor, FILE_USE_INCLUDE_PATH);
$status = json_encode($_json_status);

while($_json_status == ''){
    
    $_json_status = file_get_contents($_monitor, FILE_USE_INCLUDE_PATH);
    $status = json_encode($_json_status);   
}
*/

header('Content-Type: application/json');
echo json_encode($_response_items); 

?>