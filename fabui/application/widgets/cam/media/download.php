<?php

$settings_file = str_replace('media/'.basename(__FILE__),  'data/settings.json', __FILE__);


$settings = json_decode(file_get_contents($settings_file), true);


if(!file_exists('/var/www/temp/'.$settings['name'].'.'.$settings['encoding'])){

	require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/pi_camera/Camera.php';
	$camera = new Camera($settings);
	$camera->output->setValue('/var/www/temp/'.$settings['name'].'.'.$settings['encoding']);
	$camera->create();	
}


$_image   = '/var/www/temp/'.$settings['name'].'.'.$settings['encoding'];
$_file_name = 'raspicam.'.$settings['encoding'];
$_data      = file_get_contents($_image);


// Generate the server headers
if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE)
{
	
	header('Content-Disposition: attachment; filename="'.$_file_name.'"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header("Content-Transfer-Encoding: binary");
	header('Pragma: public');
	header("Content-Length: ".strlen($_data));
}
else
{
	
	header('Content-Disposition: attachment; filename="'.$_file_name.'"');
	header("Content-Transfer-Encoding: binary");
	header('Expires: 0');
	header('Pragma: no-cache');
	header("Content-Length: ".strlen($_data));
}

exit($_data);


?>