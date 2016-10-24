<?php
/**
 * 
 * Update twitter feed
 */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';
if(!file_exists(TWITTER_FEED_JSON)){
	write_file(TWITTER_FEED_JSON, '', 'w');
}
if (is_internet_avaiable()) {
	
	$ch = curl_init(TWITTER_FEED_URL);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$twitter_feed = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	if($info['http_code'] == 200) {
		if(isJSON($twitter_feed)){
			write_file(TWITTER_FEED_JSON, $twitter_feed, 'w');
		}
	}
}
?>