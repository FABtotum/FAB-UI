<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "php_serial.class.php";

//gcode file
$prog=$_GET[prog];
$debug= shell_exec('sudo python /var/www/python_api/g_push.py');

echo "Done!<br>";
echo $debug;

?>