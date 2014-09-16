<?php
$zip = new ZipArchive;

$res = $zip->open('/var/www/update/temp/myfab_1.zip');
if ($res === TRUE) {
	$zip->extractTo('/var/www/update/temp/');
	$zip->close();
	echo 'woot!';
} else {
	echo 'doh!';
}
?>