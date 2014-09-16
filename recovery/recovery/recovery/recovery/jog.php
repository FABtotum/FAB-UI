<?php

include_once("header.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include "php_serial.class.php";

//print_r($_POST);
$value=$_POST['c'];
$feed=intval($_POST[feed]);
if($feed==""){
//$feed=1000;
} 

if($_POST['s']==1){
echo '<img src="http://my.fabtotum.com/embed.php">';

}


if($value!=""){

//$value.=" F$feed";
//$value=str_replace("_","\r\n",$value);

$serial = new phpSerial;
$serial->deviceSet("/dev/ttyAMA0");
$serial->confBaudRate(115200);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();
$serial->sendMessage($value."\r\n");
$reply=$serial->readPort();
$serial->deviceClose();
} 
 

echo "<div><b>SERIAL STARTED</b> || <a href=/jog.php>[RELOAD]</a><br><br>";


echo "<form method='POST' action='jog.php'>


SETUP    : <button name='s' type='submit' value='1'>STILL</button> <button name='c' type='submit' value='G92 X0 Y0 Z0'>ZERO ALL</button> <button name='c' type='submit' value='G0 X0 Y0 Z0'>GO TO ZERO</button><br><br>
FEEDRATE : <input name='feed' type='text' size='25' value='".$feed."'><br>

MOTORS   : <button name='c' type='submit' value='M17'>ON</button> <button name='c' type='submit' value='M18'>OFF</button><br>
COORD    : <button name='c' type='submit' value='G91'>RELATIVE</button> <button name='c' type='submit' value='G90'>ABSOLUTE</button><br>
JOG      : <br><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name='c' type='submit' value='G0 Y+10'>^</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name='c' type='submit' value='G0 Z+10'>Z+</button><br>
<button name='c' type='submit' value='G0 X-10'>&lt;</button> <button name='c' type='submit' value='G0 X+10'>&gt;</button><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name='c' type='submit' value='G0 Y-10'>v</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button name='c' type='submit' value='G0 Z-10'>Z-</button><br>
<br><br>

<button name='c' type='submit' value='G0 E-45'>&lt A </button> <button name='c' type='submit' value='G0 E+45'>A &gt</button><br>


</form>";

echo "<form method='POST' action='jog.php'>
MDI : <input name='c' type='text' size='25' value='".$value."'><button type='submit'>EXEC</button><br>
</form>";

echo "MONITOR<br>";
echo "SENT     - ($value)<br>";
echo "REPLIED  - ($reply)<br>";


echo'DEBUG LED : <a href=?c=a>[ON]</a> || <a href=?c=b>[OFF]</a><br>';
echo'MOTORS    : <a href=?c=M17>[ON]</a> || <a href=?c=M18>[OFF]</a><br>';



include_once("footer.php");

?>