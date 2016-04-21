<?php
require_once '/var/www/lib/config.php';
shell_exec('sudo bash '.SCRIPT_PATH.'bash/stop_all.sh');
echo true;
?>