<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$cmd = "sudo python /var/www/recovery/python/plane_level.py";

$descriptorspec = array(
   0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
   2 => array("pipe", "w")    // stderr is a pipe that the child will write to
);
flush();
$process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());
if (is_resource($process)) {
    while ($s = fgets($pipes[1])) {
        $result.=$s;
        flush();
    }
}

echo $result;

?>