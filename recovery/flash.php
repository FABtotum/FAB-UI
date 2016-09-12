<?php 
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include '/var/www/lib/config.php';
include '/var/www/lib/utilities.php';
include '/var/www/lib/serial.php';

//local fw file
$local_file = BUILD_PATH.'Marlin.cpp.hex';
$response = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){ //if is post call	
	
	$flash_type = $_POST['flash'];
	switch($flash_type){
		case 'local':
			$response = flash_local();
			break;
		case 'remote':
			if(is_internet_avaiable()) $response = flash_remote();
			break;
		default:
			break;
	}
}elseif($_SERVER['REQUEST_METHOD'] == 'GET'){ //if called from other sources
	if(isset($_GET['avoid']) && $_GET['avoid'] == 1){
		echo flash_local();
		exit();
	}
}

if($response != '' && strpos($response, 'done with autoreset') !== false){
	$message = '<div class="alert alert-success fade in"><i class="fa-fw fa fa-check"></i><strong> Flash done.</strong></div>';
}

//load remote fw versions
$remote_versions = json_decode(file_get_contents('http://update.fabtotum.com/MARLIN/versions.php'), TRUE);
//read system info
$sysinfo = json_decode(shell_exec('sudo python '.PYTHON_PATH.'sysinfo.py'), true);

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
				<?php if(isset($message)) echo $message; ?>
				<div class="row">
					<div class="col-sm-12">
						<pre>Installed Firmware: <?php echo $sysinfo['fw'];?></pre>
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
							<p>Local firmware file: <?php echo $local_file; ?></p>
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
		
		<!-- MODAL -->
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Flashing please wait... </h4>
					</div>
					<div class="modal-body">
						<div class="progress">
							<div class="progress progress-striped active">
								<div class="progress-bar" role="progressbar" style="width: 100%"></div>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		<?php
		include 'footer.php';
 ?>
 	<script type="text/javascript">

	 	$(".btn").on('click', function(){
	 		$(".btn").addClass("disabled");
	 		$("#modal").modal({
		 		keyboard: false,
		 		backdrop: 'static',
		 		show: true
		 	});
	 		
	 	});
		
 	</script>
 
	</body>
</html>
<?php

/**
 * flash firmware from local file
 */
function flash_local()
{
	global $local_file;
	return flash($local_file);
	
}
/**
 * flash firmware from remote downloaded file
 */
function flash_remote()
{
	$version_to_download = $_POST['version'];
	$file = BUILD_PATH.MARLIN_DOWNLOAD_FILE;
	//remove existing file
	shell_exec('sudo rm -r '.$file);
	$url = MARLIN_DOWNLOAD_URL.$version_to_download.'/'.MARLIN_DOWNLOAD_FILE;
	$target_file = fopen( $file, 'w+') or die("can't open file");
	//download file
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_BUFFERSIZE, 64000);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_FILE, $target_file );
	$html  = curl_exec($ch);
	curl_close($ch);
	//set permissions to file
	shell_exec('sudo chmod 0777 '.$file);
	return flash($file);
}
/**
 * flash
 */
function flash($file)
{	
	write_file(LOCK_FILE, '');
	$flash_command = 'sudo /usr/bin/avrdude -D -q -V -v -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $file . ':i';
	$response_flash = shell_exec($flash_command);
	unlink(LOCK_FILE);
	//flash done - just wait 2 seconds
	sleep(2);
	shell_exec('echo "M728\r\n" > /dev/ttyAMA0');
	//recalculate baudrate
	shell_exec('sudo python '.PYTHON_PATH.'baud.py');
	//start up printer
	shell_exec('echo "M728\r\n" > /dev/ttyAMA0');
	sleep(5);
	//reboot all settings
	include FABUI_PATH.'script/boot.php';
	sleep(5);
	return $response_flash;
}
