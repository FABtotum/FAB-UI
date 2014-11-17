<html>
	<head>
		<title>Flash Remote</title>
	</head>
	<body>

	<h2>Flash Remote</h2>
<?php
//get remote version
$remote_version = file_get_contents('http://update.fabtotum.com/MARLIN/version.txt');
$source = "http://update.fabtotum.com/MARLIN/download/".$remote_version."/Marlin.cpp.hex";



echo "<pre>";

echo "Check internet connection...".PHP_EOL;

if(@fsockopen('www.google.com', 80, $num, $error, 5)){
			
	echo "Internet ok".PHP_EOL;
	
	echo "Remote version: ".$remote_version.PHP_EOL;
	echo "Dowloading remote file...".PHP_EOL;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $source);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	$data = curl_exec ($ch);
	$error = curl_error($ch); 
	curl_close ($ch);
	
	$destination = "/var/www/temp/Marlin.cpp.hex";
	$file = fopen($destination, "w+");
	fputs($file, $data);
	fclose($file);
	
	echo "File downloaded...".PHP_EOL;
	
	chmod($destination, 0777);
	
	echo "Starting flash".PHP_EOL;
	
	
	$cmd = "sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0   -U flash:w:".$destination.":i";
	
	$output = shell_exec($cmd);
	
	
	echo $output.PHP_EOL;
	
	echo "Flash done!".PHP_EOL;
	
	//shell_exec('sudo rm '.$destination);

}else{
	echo "No internet connectivity".PHP_EOL;
}

echo "</pre>";

?>
	</body>
</html>