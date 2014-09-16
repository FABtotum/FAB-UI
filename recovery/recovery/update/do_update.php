<?php

$_command = 'sudo php /var/www/recovery/update/update_command.php > /dev/null &';
shell_exec ( $_command);

sleep(3);

echo "OK";


?>