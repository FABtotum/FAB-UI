<?php

require 'utilities.php';

$mode = isset($_GET['mode']) && $_GET['mode'] != '' ? $_GET['mode'] : 'net';

switch($mode){
	case 'net':
		$content = '<pre>'.shell_exec("sudo ifconfig").'</pre>';
		break;
	case 'php':
		
		$content = '';
		
		$phpinfo = phpinfo_array();
		
		foreach($phpinfo as $key => $value){
			
			$content .= '<h6>'.$key.'</h6>';
			$content .= '<table class="table table-bordered table-striped">';
			foreach($value as $j =>  $jv){
				$content .= '<tr>';
				$content .= '<td width="200">'.$j.'</td>';
				$content .= '<td>';
				$content .= is_array($jv) ? $jv['local'] : $jv;		
				$content .= '</td>';
				$content .= '</tr>';
			}
			$content .= '</table>';
		}
		break;
	case 'raspi':
		
		$content = '<h6>Version</h6>';
		$content .= '<pre>';
		$content .= shell_exec('sudo cat /proc/version');
		$content .= '</pre>';
		$content .= '<h6>Cpu</h6>';
		$content .= '<pre>';
		$content .= shell_exec('sudo cat /proc/cpuinfo');
		$content .= '</pre>';
		$content .= '<h6>Memory</h6>';
		$content .= '<pre>';
		$content .= shell_exec('sudo cat /proc/meminfo'); 
		$content .= '</pre>';
		$content .= '<h6>Partitions</h6>';
		$content .= '<pre>';
		$content .= shell_exec('sudo cat /proc/partitions');   
		$content .= '</pre>';
		$content .= '<h6>USB devices</h6>';
		$content .= '<pre>';
		$content .= shell_exec('sudo lsusb');   
		$content .= '</pre>'; 
		
		$content .= '<h6>Raspi Cam</h6>';
		$content .= '<pre>';
		$content .= shell_exec('sudo raspistill -v');   
		$content .= '</pre>'; 
		
		
		break;
}



$option['net'] = "Network";
$option['fw']  = "Firmware";
$option['php'] = "Php";
$option['raspi'] = "Rasberry Pi";


include 'header.php';

?>
	<style>
		.table{
			font-size: 13px;
		}
	</style>

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
					<li>Info</li>
				</ol>
			</div>
			<div id="content">
				<div class="row">
					<div class="col-sm-12"> 
						<div class="well">
							
							<div class="form-inline margin-bottom-10">
								<fieldset>
									<div class="form-group">
										<select class="form-control modes">
											<?php foreach($option as $key =>$value): ?>
												<option <?php echo $key == $mode ? 'selected' : ''; ?> value="<?php echo $key; ?>"><?php echo $value ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</fieldset>
							</div>
							<?php echo $content; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		
		<script type="text/javascript">
		
			$(function() {
				
				$('.modes').on('change', change_mode);
				
			});
			
			
			function change_mode(){
				document.location.href = 'info.php?mode=' + $(this).val();
			}
			
			
			
		
		</script>
		
	</body>
</html>