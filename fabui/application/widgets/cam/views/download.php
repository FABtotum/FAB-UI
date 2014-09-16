<?php

$_image   = '/var/www/temp/picture.jpg';
$_file_name = 'raspicam.jpg';
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