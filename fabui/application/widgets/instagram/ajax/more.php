<?php

require_once '/var/www/lib/config.php';

$ch = curl_init($_POST['hash']);

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$instagram_hash = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($info['http_code'] == 200) {

	$hash = json_decode($instagram_hash, true);

	$hash_ids = array();
	$images = array();

	foreach ($hash['data'] as $item) {
		$hash_ids[] = $item['id'];
		$images[] = array('image' => $item['images']['standard_resolution']['url'], 'text' => $item['caption']['text'], 'user' => $item['user'], 'likes' => $item['likes']['count'], 'comments' => $item['comments']['count']);
	}
	
	$response['images'] = $images;
	$response['hash_next_url'] =  $hash['pagination']['next_url'];
	
	echo json_encode($response);

}
?>