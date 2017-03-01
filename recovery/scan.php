<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');
include "php_serial.class.php";

$value=str_replace("_","\r\n",$value);

$serial = new phpSerial;
$serial->deviceSet("/dev/ttyAMA0");
$serial->confBaudRate(9600);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->deviceOpen();

$serial->sendMessage($value."\r\n");

//CONFIG

$scans=360;
$deg=360/$scans; //deg to move every time 

//CLEAR DIR

    function emptyDirectory($dirname,$self_delete=false) {
    if (is_dir($dirname))
    $dir_handle = opendir($dirname);
    if (!$dir_handle)
    return false;
    while($file = readdir($dir_handle)) {
    if ($file != "." && $file != "..") {
    if (!is_dir($dirname."/".$file))
    @unlink($dirname."/".$file);
    else
    emptyDirectory($dirname.'/'.$file,true);
    }
    }
    closedir($dir_handle);
    if ($self_delete){
    @rmdir($dirname);
    }
    return true;
    }

//INITIALIZATION

$serial->sendMessage("G92 X0 Y0 Z0 E0\r\n");
$serial->sendMessage("M17\r\n");
$serial->sendMessage("G91\r\n");

emptyDirectory("/var/www/scans",0);  //clear dir

//MAIN CYCLE

	for ($i = 1; $i <= $scans; $i++) {
		$last_file_size=-1;
		echo "Processing scan $i of $scans...\n";
		$filename='/var/www/scans/img_'.$i.'.jpg';

		$msg="Waiting snapshot img_$i.jpg ...\n";
		exec('sudo raspistill -hf -ISO 2400 -w 1920 -h 1080 -o '.$filename.' -t 0');
	
		if($i==1) sleep(2);

		$serial->sendMessage("G0 E-".$deg." F1500\r\n");

		//$last_file_change=time();
		//while((!file_exists($filename))||(filemtime($filename)>$last_file_change)){
		//	sleep(2);
		//	echo $msg." exist? :".file_exists($filename)." - changed :".filemtime($filename)." / ".$last_file_change."\n";
		//	$msg="";
		//	$last_file_change=filemtime($filename);
		//}


	sleep(3);

	//$reply=$serial->readPort();
	echo "$i- DONE! ("." G0 E-".$deg.") || ".$deg*$i."/360° \n";
	//echo $reply."\n";
}


$serial->deviceClose();

echo "Scanning completed";

?>