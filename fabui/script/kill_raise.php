<?php

$pid = $argv[1];
shell_exec("sudo kill ".$pid);

shell_exec('sudo python /var/www/fabui/python/monitor.py &');



?>