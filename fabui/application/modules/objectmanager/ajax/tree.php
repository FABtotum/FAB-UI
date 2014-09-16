<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** SAVE POST PARAMETERS */
$_folder    = str_replace("//", "/", (str_replace('/media', '', $_POST["folder"])));


/** LOAD FROM USB DISK */
$_destination = '/var/www/fabui/application/modules/objectmanager/temp/media.json';
$_command     = 'sudo python /var/www/fabui/python/usb_browser.py --path='.$_folder.'  --dest='.$_destination .' -f';
shell_exec($_command);


$data['tree'] = json_decode(file_get_contents($_destination, FILE_USE_INCLUDE_PATH), TRUE);
$data['command'] = $_command;

/** RESPONSE */
header('Content-Type: application/json');
echo minify(json_encode($data));
?>