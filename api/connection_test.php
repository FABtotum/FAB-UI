<?php

$key = $_SERVER['HTTP_X_API_KEY'];
$config = '/var/www/fabui/config/config.json';

$_units = json_decode(file_get_contents($config), TRUE);


$_upload_api_key = isset($_units['api']['key']) ? $_units['api']['key']: '';

if($key == $_upload_api_key){
	return http_response_code(200);
}else{
	return http_response_code(401);
	
}




?>



