<?php
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

if (is_internet_avaiable()) {

	// Create a stream
	$opts = array('http' => array('method' => "GET"));

	$context = stream_context_create($opts);

	$instagram_feed = file_get_contents(INSTAGRAM_FEED_URL, false, $context);
	$info_header = $http_response_header;

	if ($info_header[0] == 'HTTP/1.1 200 OK') {

		$fp = fopen(INSTAGRAM_FEED_JSON, 'w');
		fwrite($fp, $instagram_feed);
		fclose($fp);

	}

	$instagram_hash = file_get_contents(INSTAGRAM_HASH_URL, false, $context);
	$info_header = $http_response_header;

	if ($info_header[0] == 'HTTP/1.1 200 OK') {
		$fp = fopen(INSTAGRAM_HASH_JSON, 'w');
		fwrite($fp, $instagram_hash);
		fclose($fp);
	}

}
?>

