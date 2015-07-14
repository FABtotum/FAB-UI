<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php';
include '/var/www/lib/serial.php';

$fw_file = '/var/www/build/Marlin.cpp.hex';
$exists = file_exists($fw_file);





//set permissions
shell_exec('sudo chmod 0777 /var/www/build -R');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	
	

	if ($_POST['flash'] == 'local') {
		$command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $fw_file . ':i';
		$response_flash = shell_exec($command);
		sleep(10);
		$start_up = shell_exec('sudo python /var/www/fabui/python/gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log');
		sleep(10);
		include '/var/www/fabui/script/boot.php';
		sleep(2);

		if (strpos($response_flash, 'done with autoreset') !== false) {
			$alert['type'] = 'success';
			$alert['messsage'] = 'FABlin Firmware flashed correctly';
		} else {
			$alert['type'] = 'danger';
			$alert['messsage'] = 'Oops an error occured, try to flash again';
		}
	}

	if ($_POST['flash'] == 'remote') {
		
		if (is_internet_avaiable()) {
			
			$_folder = '/var/www/build/';
			
			
			$_marlin_remote_version = $_POST['version'];
			
			$_file_name = $_folder.MARLIN_DOWNLOAD_FILE;
			
			shell_exec('sudo rm -r '.$_file_name);
			
			$_url       = MARLIN_DOWNLOAD_URL.$_marlin_remote_version.'/'.MARLIN_DOWNLOAD_FILE;
			
			$_target_file = fopen( $_file_name, 'w+') or die("can't open file");
		    $start = time();
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $_url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    //curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
		    //curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    
		    curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch, CURLOPT_FILE, $_target_file );
		   
		    $html  = curl_exec($ch);
		    curl_close($ch);
		    
		    
			/** LOG FLASH  */
			$log = TEMP_PATH.'flash_'.time().'.log';
			write_file($log, '', 'w');
			chmod($log, 0777);
			
			
			//set permissions
			shell_exec('sudo chmod 0777 '.$_file_name);
			
		   	$_command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:'.$_file_name.':i > '.$log;
			
		    shell_exec($_command);
			sleep(10);
			$response_flash = file_get_contents($log);
			shell_exec('sudo python ' . PYTHON_PATH . 'gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log > /dev/null &');
			sleep(10);
			
			include '/var/www/fabui/script/boot.php';
			
			$actual_version = get_fw_version();
			

			if (strpos($response_flash, 'done with autoreset') !== false && ($_marlin_remote_version == $actual_version)) {
			
				
					
				$alert['type'] = 'success';
				$alert['messsage'] = 'FABlin Firmware downloaded and flashed correctly.<br><strong>Version Downloaded:'.$_marlin_remote_version.'</strong><br><strong>Actual Version:'.$actual_version.'</strong>';
			} else {
				$alert['type'] = 'danger';
				$alert['messsage'] = 'Oops an error occured, try to flash again<br>'.$response_flash;
			}

		} else {
			$alert['type'] = 'danger';
			$alert['messsage'] = 'Oops no internet connection, plaese check your network configuration';
		}

	}

}elseif($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['avoid']) && $_GET['avoid'] == 1){
	
	$command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $fw_file . ':i';
	$response_flash = shell_exec($command);
	sleep(10);
	$start_up = shell_exec('sudo python /var/www/fabui/python/gmacro.py start_up /var/www/temp/flashing.trace /var/www/temp/flashing.log');
	sleep(10);
	
	echo $response_flash;
	
	exit();
	
}



$remote_versions = json_decode(file_get_contents('http://update.fabtotum.com/MARLIN/versions.php'), TRUE);


include 'header.php';
?>
	</head>
	<body>
		<header id="header">
			<div id="logo-group">
				<span id="logo"><img src="/assets/img/logo-0.png"></span>
			</div>
		</header>
		<div id="main" role="main">
			<div id="ribbon">
				<ol class="breadcrumb">
					<li><a href="/recovery/index.php">Recovery</a></li>
					<li>Flash FABlin firmware</li>
				</ol>
			</div>
			<div id="content">
				
				<div class="row">
					<div class="col-sm-12">
						<pre>Firmware: <?php echo get_fw_version(); ?></pre>
					</div>
				</div>
				
				<?php if(isset($alert)): ?>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-<?php echo $alert['type'] ?> fade in">
								<?php echo $alert['messsage'] ?>
							</div>
						</div>
					</div>
					
				<?php endif; ?>
					
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<p>Firmware file: <?php echo $fw_file; ?></p>
							<p>Do you really want to flash firmware from local file ? </p>
							<form method="POST">
								<button name="flash" value="local" class="btn btn-primary" type="submit"><i class="fa"></i>Flash Local</button>
							</form>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<p>Do you want to download the latest version of the firmware and flash it? (Need internet connection)</p>
							<!--<button id="flash_remote" value="remote" class="btn btn-primary" type="button"><i class="fa"></i>Flash Remote</button>-->
							<form method="POST" class="form-inline margin-bottom-10">
								<fieldset>
									<div class="form-group">
										<label>Version: </label>
										<select class="form-control" name="version">
											<?php foreach($remote_versions as $version): ?>
												<option><?php echo $version ?></option>
											<?php endforeach; ?>
										</select>
										<button name="flash" value="remote" class="btn btn-primary" type="submit"><i class="fa"></i>Flash Remote</button>
									</div>
								</fieldset>
							</form>
						
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<?php
		include 'footer.php';
 ?>
 <script type="text/javascript">
 	
 	$("#flash_remote").on('click', flash_remote);
 	
 	$(".btn-primary").on('click', function(){
 		openWait("Flashing please wait...");
 	});
 	
 	
 	
 	function flash_remote(){
 		
 		openWait("Flashing please wait...");
 		$.ajax({
			 url: "ajax/flash_remote.php",
			 type: "POST",
             dataType: 'json',
		}).done(function( data ) {
			
			closeWait();
				  
		});
 	}
 	
 </script>
 
	</body>
</html>


<?php 



function get_fw_version(){
	
	$serial = new Serial();
	$serial->deviceSet(PORT_NAME);
	$serial->confBaudRate(BOUD_RATE);
	$serial->confParity("none");
	$serial->confCharacterLength(8);
	$serial->confStopBits(1);
	$serial->deviceOpen();
	$serial->sendMessage("M765\r\n");
	$reply = $serial->readPort();
	$serial->deviceClose();
	
	return trim(str_replace('V ', '', str_replace(PHP_EOL.'ok','', $reply)));
}



?>
