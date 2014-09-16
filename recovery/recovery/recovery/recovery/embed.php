<?PHP
exec('sudo raspistill -hf -w 512 -h 320 -o /var/www/camera/pictures/imageembed.jpg -t 0');
$filename = "/var/www/camera/pictures/imageembed.jpg";
$handle = fopen($filename, "rb");
$contents = fread($handle, filesize($filename));
fclose($handle);
echo $contents;
?>