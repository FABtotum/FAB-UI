<?php

include_once("header.php");

$mode=$_GET['mode'];

echo "Info Module<br><br>";
switch ($mode) {
    case 'net':
        echo "Network interfaces:<br>";
		$outp="<code>".nl2br(shell_exec("sudo ifconfig"))."</code>";
        break;
    case 'php':
        echo "PHP infos:";
		$outp=phpinfo();
        break;
    case 'Firmware':
        echo "Firmware Loader:";
		//todo
		$outp="TODO";
        break;

		
	}

	
echo $outp;

include_once("footer.php");

?>