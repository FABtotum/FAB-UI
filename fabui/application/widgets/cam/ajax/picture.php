<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/pi_camera/Camera.php';


$iso        = $_POST['iso'];
$size       = $_POST['size'];
$encoding   = $_POST['encoding'];
$quality    = $_POST['quality'];
$imxfx      = $_POST['imxfx'];
$brightness = $_POST['brightness'];
$contrast   = $_POST['contrast'];
$sharpness  = $_POST['sharpness'];
$saturation = $_POST['saturation'];
$awb        = $_POST['awb'];
$ev_comp    = $_POST['ev_comp'];
$exposure   = $_POST['exposure'];
$rotation   = $_POST['rotation'];
$metering   = $_POST['metering'];

$split_size = explode('-', $size);
$width = $split_size[0];
$height = $split_size[1];


$settings_file = str_replace('ajax/'.basename(__FILE__),  'data/settings.json', __FILE__);

$settings = array();
$settings['encoding'] = $encoding;
$settings['iso'] = $iso;
$settings['width']  = $width;
$settings['height'] = $height;
$settings['quality'] = $quality;
$settings['imxfx'] = $imxfx;
$settings['brightness'] = $brightness;
$settings['contrast'] = $contrast;
$settings['sharpness'] = $sharpness;
$settings['saturation'] = $saturation;
$settings['awb'] = $awb;
$settings['ev'] = $ev_comp;
$settings['exposure'] = $exposure;
$settings['rotation'] = $rotation;
$settings['metering'] = $metering;
$settings['name'] = 'picture';
$json = json_encode($settings);
write_file($settings_file, $json, 'w');

$camera = new Camera($settings);
$camera->output->setValue(TEMP_PATH.'picture.'.$settings['encoding']);
$camera->doImage();


$_response_items['command'] = $camera->get_command();
header('Content-Type: application/json');
echo json_encode($_response_items);
?>