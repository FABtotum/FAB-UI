<?php
include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php'; 
include "/var/www/lib/serial.php";

$gcode = trim($_REQUEST['code']);


if($gcode != ''){
	
	if($gcode == 'picture'){
			
		
		$_image   = TEMP_PATH.'picture.jpg';
		
			
		if(!file_exists($_image)){
			write_file($_image, '', 'w');
		}
		
		$_command = 'sudo raspistill -n -hf -t 1 -rot 90 -awb sun -ISO 800 -w 768 -h 1024 -o '.$_image;
		
		$response = shell_exec ( $_command );
		
		//echo $response;
		
		//exit();
		
		//shell_exec('sudo chmod 777 '.$_image);
		
		//sleep(5);
		
		header('Content-type: image/jpg');
		echo readfile($_image);
		
		
		
	}else{
		
		$ini_array = parse_ini_file(SERIAL_INI);
		
		$serial = new Serial();
		$serial->deviceSet($ini_array['port']);
		$serial->confBaudRate($ini_array['baud']);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->deviceOpen();
		$serial->sendMessage($gcode.PHP_EOL);
		$reply = $serial->readPort();
		$serial->serialflush();
		$serial->deviceClose();
		
		echo $reply;		
		
	}

}else{
	echo "missing code";
}





?>