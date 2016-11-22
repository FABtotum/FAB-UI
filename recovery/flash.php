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
			$response_flash = flash_local();
			break;
		case 'remote':
			if(is_internet_avaiable()) $response_flash = flash_remote();
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
if(isset($flash_type)){
	if($response_flash){
		$message = '<div class="alert alert-success fade in"><i class="fa-fw fa fa-check"></i><strong> Flash done.</strong>';
	}else{
		$message = '<div class="alert alert-warning fade in"><i class="fa-fw fa fa-warning"></i><strong> Flash failed. Please try again.</strong>';
	}
	
	$message .= ' <button id="details" type="button">view details</button> </div>';
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
				<?php if(isset($response_flash)): ?>
				<div class="row flash-details" style="display:none;">
					<div class="col-sm-12">
						<pre><?php echo $response_flash[1]; ?></pre>
					</div>
				</div>
				<?php endif; ?>
				<div class="row">
					<div class="col-sm-12">
						<pre>Installed Firmware: <?php echo $sysinfo['fw']['version'];?></pre>
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
												<?php if($version != 'latest'): ?>
												<option><?php echo $version ?></option>
												<?php endif; ?>
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

		$("#details").on('click', showFlashDetails);
		
	 	function showFlashDetails()
	 	{
	 		if($('.flash-details').is(":visible")){
	 			$(".flash-details").slideUp(function() { 	
		 			$("#details").html('view details');
			 	});
	 		}else{
	 			$(".flash-details").slideDown(function() {
	 				$("#details").html('hide details');
			 	});
	 		}
	 	}
		
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
	$re = '/avrdude-original: AVR device initialized and ready to accept instructions/i';
	write_file(LOCK_FILE, '');
	$flash_command = 'sudo /usr/bin/avrdude -D -q -V -v -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:' . $file . ':i';
	$response_flash = liveExecuteCommand($flash_command, false);
	//preg_match_all($re, $response_flash['output'], $matches);
	if(preg_match($re, $response_flash['output'] )){
		$flash_ok = true;
	}else{
		$flash_ok = false;
	}
	unlink(LOCK_FILE);
	//flash done - just wait 2 seconds
	sleep(2);
	shell_exec('sudo python '.PYTHON_PATH.'baud.py');
	shell_exec('sudo python '.PYTHON_PATH.'boot.py -R');
	sleep(5);
	return array($flash_ok, $response_flash['output']);
}

function liveExecuteCommand($cmd, $echo = true)
{

	while (@ ob_end_flush()); // end all output buffers if any

	$proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

	$live_output     = "";
	$complete_output = "";

	while (!feof($proc))
	{
		$live_output     = fread($proc, 4096);
		$complete_output = $complete_output . $live_output;
		if($echo) echo "$live_output";
		@ flush();
	}
	pclose($proc);
	// get exit status
	preg_match('/[0-9]+$/', $complete_output, $matches);
	// return exit status and intended output
	return array (
		'exit_status'  => $matches[0],
		'output'       => str_replace("Exit status : " . $matches[0], '', $complete_output)
	);
}
