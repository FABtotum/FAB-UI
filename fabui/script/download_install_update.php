<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';
require_once '/var/www/lib/jog_factory.php';

/** GET ARGS FROM COMMAND LINE */
play_beep();
$version      = $argv[1];
$task_id      = $argv[2];
$task_folder  = $argv[3];
$monitor_file = $argv[4];

$file_url  = MYFAB_DOWNLOAD_URL.$version.'/'.MYFAB_DOWNLOAD_FILE;
$file_size = remote_file_size($file_url);
$rounded_filesize = roundsize($file_size);
$temp_file        = $task_folder.'download.zip';

$monitor_response = array();
$monitor_response['status'] = 'download';
$monitor_response['download']['downloaded'] = 0;
$monitor_response['download']['speed'] = 0;
$monitor_response['download']['size'] = $file_size;
$monitor_response['download']['complete'] = false;
$monitor_response['version'] = $version;
//start download
$target_file = fopen( $temp_file, 'w+') or die("can't open file");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $file_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_FILE, $target_file );
$download_start = time();
$download_prevTime = 0;
$html  = curl_exec($ch);
if(!curl_errno($ch)){
	$info = curl_getinfo($ch);
}
curl_close($ch);
//download completed
$monitor_response['download']['complete'] = true;
write_to_monitor();
sleep(1);
$monitor_response['status'] = 'installation';
$monitor_response['installation']['complete'] = false;
write_to_monitor();
//unzip file
extract_zip($temp_file, $task_folder.'temp/');
$monitor_response['installation']['complete'] = false;
write_to_monitor();
//exec install file
if(file_exists($task_folder.'temp/install.php')){
	include $task_folder.'temp/install.php';
}
$monitor_response['installation']['complete'] = false;
write_to_monitor();
sleep(10);
$monitor_response['status'] = 'completed';
write_to_monitor();
play_beep();


$_command_finalize = 'sudo php '.SCRIPT_PATH.'finalize.php '.$task_id.' update_software > /dev/null & echo $! ';
shell_exec($_command_finalize);


/* download progress handler */
function progress($download_size, $downloaded, $upload_size, $uploaded){
	global $monitor_response;
	global $download_prevSize;
	global $download_prevTime;
	global $download_start_time;
	
	$currentSpeed = ($downloaded - $monitor_response['download']['downloaded']) / (microtime(true) - $download_prevTime);
	$download_prevTime = microtime(true);
	$monitor_response['download']['downloaded'] = $downloaded;
	if($currentSpeed > 0) $monitor_response['download']['speed'] = $currentSpeed;
	write_to_monitor();
	
}

/* write to monitor */
function write_to_monitor(){
	global $monitor_file;
	global $monitor_response;
	write_file($monitor_file, json_encode($monitor_response), 'w');
}

/* get file size */
function remote_file_size($url){
	//Get all header information
	$data = get_headers($url, true);
	//Look up validity
	if (isset($data['Content-Length']))
		//Return file size
		return (int) $data['Content-Length'];
}


function play_beep(){
	$jogFactory = new JogFactory();
	$jogFactory->mdi('M300 S1000'.PHP_EOL);
}

?>