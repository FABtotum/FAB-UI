<?php
$action = $_POST['action'];
$action_command = $action == 'on' ? 'ifup' : 'ifdown';
shell_exec('sudo '.$action_command.' wlan0');

?>