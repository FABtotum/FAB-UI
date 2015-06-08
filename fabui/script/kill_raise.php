<?php
include_once '/var/www/lib/config.php';

$pid     = $argv[1];
$type    = $argv[2];
$process = $argv[3];


$kill_command = 'sudo kill '.$pid;

shell_exec($kill_command);

if($type != "" && $process != ""){
	
	$raise_command = 'sudo '.$type.' '.$process.' &';
	shell_exec($raise_command);
}





?>