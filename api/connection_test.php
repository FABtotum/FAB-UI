<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fabui/ajax/config.php';

$post_key = $_SERVER['HTTP_X_API_KEY'];

$_units = json_decode(file_get_contents(CONFIG_UNITS), TRUE);
$_upload_api_keys = isset($_units['api']['keys']) ? $_units['api']['keys']: '';

foreach ($_upload_api_keys as $user => $key){
	if ($key == $post_key){
		return http_response_code(200);
	}
}

// if($key == $_upload_api_key){
// 	;
// }else{
	
	
// }
return http_response_code(401);



?>



