<?php
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

if (is_internet_avaiable()) {
	
	
	
	$ch = curl_init(TWITTER_FEED_URL);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$twitter_feed = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if ($info['http_code'] == 200) {
		
		write_file(TWITTER_FEED_JSON, $twitter_feed, 'w');
	}
	
}
?>

