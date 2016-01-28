<?php
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/utilities.php';



if (is_internet_avaiable()) {
		
	$ch = curl_init(FAQ_URL);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$faq_json = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if ($info['http_code'] == 200) {
		
		write_file(FAQ_JSON, $faq_json, 'w');
			
	}

}
?>

