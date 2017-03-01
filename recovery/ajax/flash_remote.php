<?php
include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php';

$fw_file = '/var/www/build/Marlin.cpp.hex';
$exists = file_exists($fw_file);

$_folder = '/var/www/build/';
$_marlin_remote_version  = marlin_get_remote_version();

//$_marlin_remote_version = '1.0.006';

$_file_name = $_folder . MARLIN_DOWNLOAD_FILE;

shell_exec('sudo rm -r ' . $_file_name);

$_url = MARLIN_DOWNLOAD_URL . $_marlin_remote_version . '/' . MARLIN_DOWNLOAD_FILE;

$_target_file = fopen($_file_name, 'w+') or die("can't open file");
$start = time();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
//curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_BUFFERSIZE, 64000);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_FILE, $_target_file);

$html = curl_exec($ch);
curl_close($ch);

/** LOG FLASH  */
$log = TEMP_PATH . 'flash_' . time() . '.log';
write_file($log, '', 'w');
chmod($log, 0777);

//set permissions
shell_exec('sudo chmod 0777 ' . $_file_name);

$_command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $_file_name . ':i > ' . $log;

shell_exec($_command);
sleep(10);
$response_flash = file_get_contents($log);
shell_exec('sudo python ' . PYTHON_PATH . 'gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log > /dev/null &');
sleep(10);

//sleep(20);

if (strpos($response_flash, 'done with autoreset') !== false) {
	$alert['type'] = 'success';
	$alert['messsage'] = 'FABlin Firmware downloaded and flashed correctly';
} else {
	$alert['type'] = 'danger';
	$alert['messsage'] = 'Oops an error occured, try to flash again<br>' . $response_flash;
}

echo json_encode($alert);
?>