<?php

$_command        = 'sudo python /var/www/fabui/python/triangulation.py -i/var/www/tasks/scan_50_1428591668/images/ -o/var/www/tasks/scan_50_1428591668/pprocess_50_1428591668.asc -s60 -b0 -e360 -w2592 -h1944 -z0 -a0 -l/var/www/tasks/scan_50_1428591668/pprocess_50_1428591668.monitor -mr -t50 2>/var/www/tasks/scan_50_1428591668/pprocess_50_1428591668.debug > /dev/null & echo $!';
$_output_command = shell_exec ( $_command );
echo (intval(trim(str_replace('\n', '', $_output_command))) + 1).PHP_EOL;

echo (intval($_output_command) + 1).PHP_EOL;


?>