<?php

$file          = '/var/www/temp/picture.jpg';
$last_modified = filemtime($file);
$seconds_to_cache = ((60 * 60 ) * 24 ) * 365; // 1 year


$file_name       = basename($file);
$file_extension = strtolower(substr(strrchr($file_name,"."),1));

switch( $file_extension ) {
    case "gif": $ctype="image/gif"; break;
    case "png": $ctype="image/png"; break;
    case "jpeg":
    case "jpg": $ctype="image/jpg"; break;
    default:
}


header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $last_modified) . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:i:s', $last_modified + $seconds_to_cache) . ' GMT');
header('Content-type: ' . $ctype);
echo readfile('/var/www/temp/picture.jpg');



?>