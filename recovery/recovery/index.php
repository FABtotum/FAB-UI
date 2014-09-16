<?php
include_once("header.php");

function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

//I'm connected to...(parse ifconfig)
	$nets=shell_exec("sudo ifconfig");
	$nets=explode("\n\n",$nets);
	
	//DEBUG print_r($nets);
	
	foreach($nets as $id => $interface){
	   $interface=explode(" ",$interface,2);
	   $int[$interface[0]]=get_string_between($interface[1], "inet addr:", " ");
	   
	    
	}

	echo '<div align=center> <img src=fablogo.jpg></div>';
	//echo '<div align=center>  <b> currently connected to: </b>';
		$icons=array("eth0" => "icon_ethernet.gif" , "wlan0" => "icon_wlan.png");
		$names=array("eth0" => "LAN" , "wlan0" => "Wireless");
		
		/*
		foreach($int as $id => $addr){
			if(($id!="lo") &&($id!="")){
				if($addr==""){
					$addr="Disconnected";
				}
				if($addr==$_SERVER['SERVER_ADDR']){
				//display on wich connection the UI is connected to.
				$names[$id]="<font color=green>".$names[$id]."</font>";
				}
			echo " <img src=".$icons[$id]." width=15px height=15px> ". $names[$id] .": ". $addr." ";
			}
		}*/
    echo '</div><br>';
	//echo '<div align=center> <a href=http://'.$_SERVER['SERVER_ADDR'].'/fabui/><button>[fabui]</button></a> <a href=jog.php><button>[JOG]</button></a> <a href=setup.php><button>[Wlan CONFIG]</button></a> <a href=info.php?mode=net><button>[Network]</button></a> <a href=reboot.php><button>[REBOOT]</button></a> <a href=flash.php><button>[FLASH FW]</button></a> <a href=/phpmyadmin><button>[Database]</button></a> <a href=/recovery/update><button>[Update]</button></a> <a href=/recovery/install><button>[Install]</button></a><a href=/recovery/phpinfo.php><button>[PHPINFO]</button></a></div>';

include_once("footer.php");
?>

