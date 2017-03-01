<?php
/**
 * 
 * Update instagram feed
 * 
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';

if(!file_exists(INSTAGRAM_FEED_JSON)){
	write_file(INSTAGRAM_FEED_JSON, '', 'w');
}

if (is_internet_avaiable()) {
	$ch = curl_init(INSTAGRAM_FEED_URL);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$instagram_feed = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	if($info['http_code'] == 200) {
		if(isJSON($instagram_feed) && strlen($instagram_feed) > 0 ){
			write_file(INSTAGRAM_FEED_JSON, $instagram_feed, 'w');
		}
	}
}
?>

