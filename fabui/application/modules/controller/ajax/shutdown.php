<?php
session_start();
session_destroy();
require_once '/var/www/lib/config.php';
shell_exec('sudo bash '.SCRIPT_PATH.'bash/shutdown.sh');
echo true;
?>