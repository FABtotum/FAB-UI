<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';

if (is_internet_avaiable()) {
	
	$ch = curl_init(BLOG_FEED_URL);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$blog_xml = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if ($info['http_code'] == 200) {
		write_file(BLOG_FEED_XML, $blog_xml, 'w');
	}
}
?>

