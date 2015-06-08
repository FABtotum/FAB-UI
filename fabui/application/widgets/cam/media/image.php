<?php

$settings_file = str_replace('media/'.basename(__FILE__),  'data/settings.json', __FILE__);

$settings = json_decode(file_get_contents($settings_file), true);




if(!file_exists('/var/www/temp/'.$settings['name'].'.'.$settings['encoding'])){

	require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/pi_camera/Camera.php';
	$camera = new Pi_Camera($settings);
	$camera->output->setValue('/var/www/temp/'.$settings['name'].'.'.$settings['encoding']);
	$camera->create();
	
	
}



$file            = '/var/www/temp/'.$settings['name'].'.'.$settings['encoding'];



$file_name       = basename($file);
$file_extension  = strtolower(substr(strrchr($file_name,"."),1));


switch( $file_extension ) {
	case "bmp": $ctype="image/bmp"; break;
    case "gif": $ctype="image/gif"; break;
    case "png": $ctype="image/png"; break;
    case "jpeg":
    case "jpg": $ctype="image/jpg"; break;
    default:
}


header('Content-type: ' . $ctype);
echo readfile($file);

?>