<?php


$pkg_to_remove[] = 'tightvncserver';
$pkg_to_remove[] = 'xstartup';
$pkg_to_remove[] = 'lxpanel';
$pkg_to_remove[] = 'pcmanfm';
$pkg_to_remove[] = 'openbox';



foreach($pkg_to_remove as $pkg){
	unistall_pkg($pkg);
}

function unistall_pkg($pkg){
	
	$command = 'sudo apt-get --purge remove '.$pkg.' -y';
	shell_exec($command).PHP_EOL;
	
}

?>