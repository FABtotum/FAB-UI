<?php

$log_type = isset($_GET['type']) && $_GET['type'] != '' ? $_GET['type'] : 'system';


switch($log_type){
	case 'system':
		$log_file = '/var/log/syslog';
		break;
	case 'kernel':
		$log_file = '/var/log/kern.log';
		break;
	case 'messages':
		$log_file = '/var/log/messages';
		break;
		
}

$today_number = date("d");
$today_month = date("M");

shell_exec('sudo chmod 644 '.$log_file);
$log = array_reverse(file($log_file));


$option['system'] = "System Log";
$option['kernel'] = "Kernel Log";
$option['messages'] = "Messages Log";


$errors = 0;

?>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="author" content="FABteam">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="HandheldFriendly" content="true">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<title>FAB UI beta</title>
		<link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-skins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/demo.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/font-fabtotum.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/js/plugin/magnific-popup/magnific-popup.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/fonts.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/fabtotum_style.css">
		<style>
			
			.table {
				font-size:13px !important;
			}
			
			.danger {
				font-weight: bolder !important;
			}
		
			#main {
				margin-left: 0px !important;
			}
		</style>
		<script src="/assets/js/libs/jquery-2.1.1.min.js"></script>
		<script src="/assets/js/libs/jquery-ui-1.10.3.min.js"></script>
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
					<li>Log</li>
				</ol>
			</div>
			<div id="content">
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							
							<div class="form-inline margin-bottom-10">
								<fieldset>
									<div class="form-group">
										<select class="form-control logs">
											<?php foreach($option as $key => $value): ?>
												<option <?php echo $key == $log_type ? 'selected' : ''; ?> value="<?php echo $key ?>"><?php echo $value; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</fieldset>
							</div>
							
							<a href="javascript:void(0);" class="btn btn-default pull-right refresh">Refresh</a>
							<a data-action="all"    href="javascript:void(0);" class="btn btn-default btn-primary view">All</a>&nbsp;
							<a data-action="errors" href="javascript:void(0);" id="error-button" class="btn btn-default view">Errors</a>&nbsp;
							<a data-action="today"  href="javascript:void(0);" id="today-button" class="btn btn-default view">Today</a>
							
							<table class="table table-bordered margin-top-10">
								<thead>
									<tr>
										<th class="text-center"><i class="fa fa-clock-o"></i> Date</th>
										<th class="">Message</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($log as $line): ?>
										
									<?php 
										$temp = explode(' ', $line);
										$month = $temp[0];
										
										if($temp[1] < 10){
											
											$number = $temp[2];
											$time = $temp[3];
											$search = $month."  ".$number." ".$time;

										}else{
											$number = $temp[1];
											$time   = $temp[2];
											$search = $month." ".$number." ".$time;
										}
										
										
										
										
										$date = $number.' '.$month.' '.$time;
										$descr = trim(str_replace($search, '', $line));
										
										$class= "";
										if(!strpos(strtolower($descr), 'error') === false){
											$class = "danger";
											$errors++;
										}
										
										
										if($today_number == $number && $today_month == $month){
											$class .= " today";
										}

										
									?>	
									<tr class="<?php echo $class; ?>">
										<td width="120"><?php echo $date ?></td>
										<td><?php echo $descr ?></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="/assets/js/app.config.js"></script>
		<script src="/assets/js/bootstrap/bootstrap.min.js"></script>
		<script src="/assets/js/demo.min.js"></script>
		<script src="/assets/js/app.min.js"></script>
		
		<script type="text/javascript">
		
			$(function() {
				
				$('.view').on('click', show_rows);
				$('.logs').on('change', change_log);
				$('.refresh').on('click', function(){ document.location.href = document.location.href; });
				
				<?php if($errors == 0): ?>
				$("#error-button").addClass("disabled");
				<?php endif; ?>
				
				
			});
			
			function show_rows(){
				
				var action = $(this).attr('data-action');
				
				$('.view').removeClass('btn-primary');
				
				$(".table tbody > tr").hide();
				
				if(action == 'all'){
					$(this).addClass('btn-primary');
					$(".table tbody > tr").show();
				}
				
				if(action == 'errors'){
					$(this).addClass('btn-primary');
					$('.table .danger').show();
				}
				
				if(action == 'today'){
					$(this).addClass('btn-primary');
					$('.table .today').show();
				}
				
			}
			
			
			function change_log(){
				
				document.location.href = 'log.php?type=' + $(this).val();
			}
			
		</script>
		
	</body>
</html>
<?php 

?>