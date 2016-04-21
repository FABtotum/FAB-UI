<?php
require_once '/var/www/lib/config.php';
shell_exec('sudo bash '.SCRIPT_PATH.'bash/reset_controller.sh');
echo true;
?>