<?php
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

if (is_internet_avaiable()) {

	$ch = curl_init(INSTAGRAM_FEED_URL);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$instagram_feed = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if ($info['http_code'] == 200) {
			
		$fp = fopen(INSTAGRAM_FEED_JSON, 'w');
	 	fwrite($fp, $instagram_feed);
	 	fclose($fp);
	}
	
	
	// ===========================================================
	
	
	
	$ch = curl_init(INSTAGRAM_HASH_URL);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$instagram_hash = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if ($info['http_code'] == 200) {
			
		$fp = fopen(INSTAGRAM_HASH_JSON, 'w');
	 	fwrite($fp, $instagram_hash);
	 	fclose($fp);
	}


}
?>

