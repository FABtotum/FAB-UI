<?php
/**
 * 
 *  Update blog feed 
 * 
 */
 
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';


if(!file_exists(BLOG_FEED_XML)){
	write_file(BLOG_FEED_XML, '', 'w');
}


if (is_internet_avaiable()) {
				
	$ch = curl_init(BLOG_FEED_URL);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$blog_xml = curl_exec($ch);
	
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	//write feed
	if($info['http_code'] == 200 && strlen($blog_xml) > 0) write_file(BLOG_FEED_XML, $blog_xml, 'w');
}

?>