<?php
/* INIT */
//$_client_version = 0;
//ini_set( 'error_reporting', E_ALL );
//ini_set( 'display_errors', true );

ob_flush();
flush();

$_target_file   = fopen( '/var/www/recovery/update/testfile.iso', 'w+') or die("can't open file");



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://download.thinkbroadband.com/50MB.zip");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt( $ch, CURLOPT_FILE, $_target_file );
$html = curl_exec($ch);
curl_close($ch);


function progress($download_size, $downloaded, $upload_size, $uploaded)
{
	
	$_progress_file = fopen( '/var/www/recovery/update/progress.json', 'w+') or die("can't open file");
	$percent = (($downloaded/$download_size)*100);
	
	
	$_items_response['download_size'] = $download_size;
	$_items_response['downloaded']    = $downloaded;
	$_items_response['percent']       = $percent;
	$_items_response['pid']           = getmypid();
 	
	file_put_contents('/var/www/recovery/update/progress.json', json_encode($_items_response));
	
	ob_flush();
	flush();
	//sleep(1); // just to see effect
}
;
ob_flush();
flush();
?>