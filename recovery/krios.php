<?php


$fp =fopen("/dev/ttyUSB", "w");


fwrite($fp, 'G0 X+10 F1000');


?>