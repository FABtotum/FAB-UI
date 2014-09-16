<?php

require_once("/var/www/recovery/update/inc/init.php");
require_once("/var/www/recovery/update/inc/utilities.php");

$_file_name = $argv[1];
$_url       = $argv[2];
$_monitor   = $argv[3];


$_target_file = fopen( $_file_name, 'w+') or die("can't open file");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_FILE, $_target_file );
$start = time();
$html  = curl_exec($ch);
curl_close($ch);


//write_file('/var/www/recovery/update/temp/test.log', $start.PHP_EOL, 'a+');

function progress($download_size, $downloaded, $upload_size, $uploaded)
{
	
	global $start;
	global $_file_name;
	global $_monitor; 
	
	
	$percent      = (($downloaded/$download_size)*100);
	$elapsed_time = time() - $start;
    
    $elapsed_time = $elapsed_time%86400;
    $velocita     = $downloaded/$elapsed_time;
	
	$_items_response['download_size'] = $download_size;
	$_items_response['downloaded']    = $downloaded;
	$_items_response['percent']       = $percent;
	$_items_response['pid']           = getmypid();
	$_items_response['start']         = $start;
	$_items_response['elapsed']       = $elapsed_time%60;
	$_items_response['velocita']      = $velocita;
	$_items_response['file']          = $_file_name;
    //$_items_response['time_left']     = $time_left%60;
	
    //write_file('/var/www/recovery/update/temp/test.log', $start.PHP_EOL, 'a+');
	file_put_contents($_monitor, json_encode($_items_response));
}


?>