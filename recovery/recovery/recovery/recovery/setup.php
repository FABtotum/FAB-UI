<?php
include_once("header.php");

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//include_once("shell.php");

//Setup script prototype

// 1

//selecting  a wifi network(or skip)


function list_connections(){
//shell exec : iwlist wlan0 scan (list available connections)
$scanresult=shell_exec("sudo iwlist wlan0 scan");
//echo nl2br($scanresult);

$network=explode("Cell",$scanresult);
array_shift($network);

//echo nl2br($network[0]);

foreach($network as $cell => $data){
	$data=explode("\n",$data);
    array_pop($data); //remove last empty /n
	
	foreach($data as $cont){
		if ($cont!=""){
			$cont=explode(":",$cont, 2);
			
				if(sizeof($cont)>1){
				$cont[1]=str_replace("\n","",$cont[1]);
				$cont[0]=str_replace(" ","",$cont[0]);
				$ap[$cell][$cont[0]]=$cont[1];
				}else{
				
				$signal=explode("Signal level=",$cont[0]);
				$strenght=str_replace("/100","",$signal[1]);
				
				$ap[$cell]['Signal']="$strenght";
				}
				
			}
		}
}

echo "Available networks in range:<br><br>";

//print_r($ap);
 
 
 
echo'<form action="setup.php?posted=1" method="post">';

foreach ($ap as $key => $cell) {
   //print_r($cell);
   echo '<input type="radio" name="ssid" value="'.str_replace('"',"",$cell['ESSID']).'"> '.$key." - Name : <b>".$cell['ESSID']."</b>  Strength: ".$cell['Signal']; 
   echo "/100 <br>";
   
}

echo'<br><br> Password: <input type="text" name="pass" value="">
<button type="submit">Save Config</button>
</form>';


//!!! form per inserire password.


//
//get Connection settings
//FORM
}



if ($_GET['posted']==1){


$ssid=$_POST['ssid'];
$pass=$_POST['pass'];

echo "NOW CONFIGURING:<br>Applying changes...<br>";

//write config (call python script -must be in sudoers)
$debug.= shell_exec("sudo python /var/www/fabui/python/connection_setup.py -n".$ssid." -p".$pass);

//restart the nework
sleep(3);

echo "done! FABtotum Wireless Lan Interface is now restarting...<br><br>";

//$debug.= shell_exec("sudo /etc/init.d/networking restart");
$debug.= shell_exec("sudo ifdown wlan0");
sleep(3);
$debug.= shell_exec("sudo ifup wlan0");

echo "<b>Network restarted!</b><br><br>";
echo "Return to: <a href=http://". $_SERVER['SERVER_ADDR'].">Main Menu @ ".$_SERVER['SERVER_ADDR']."</a>";
echo "<br><code>Debug Verbose:<br>".nl2br($debug)."</code>";

}else{
list_connections();
}

// 2
//user & printer


// 3
//UI config


// 4
//
include_once("footer.php");
?>