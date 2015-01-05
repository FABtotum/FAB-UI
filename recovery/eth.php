<?php

require 'utilities.php';


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['end'])){
	
	$end = $_POST['end'];		
	setEthIP($end);
	
}


$configuration = networkConfiguration();
$end = end(explode('.', $configuration['eth']));

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
			#main {
				margin-left: 0px !important;
			}
		</style>
		<script src="/assets/js/libs/jquery-2.1.1.min.js"></script>
		<script src="/assets/js/libs/jquery-ui-1.10.3.min.js"></script>

	</head>
	<body>
		<div id="main" role="main">
			<header id="header">
				<div id="logo-group">
					<span id="logo"><img src="/assets/img/logo-0.png"></span>
				</div>
			</header>
			<div id="ribbon">
				<ol class="breadcrumb">
					<li>
						<a href="/recovery/index.php">Recovery</a>
					</li>
					<li>
						Lan Configuration
					</li>
				</ol>
			</div>
			<div id="content">
				
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<ul class="nav nav-tabs">
								<li>
									<a href="/recovery/wlan.php" >Wifi Configuration</a>
								</li>
								<li  class="active">
									<a href="javascript:void(0);" data-toggle="tab">Ethernet Configuration</a>
								</li>
							</ul>
							<div class="tab-content padding-10">
								<div class="tab-pane active">
									<!-- -->
									<div class="row">
										<div class="col-sm-12">
											<div class="well">
												<form class="form-horizontal" method="POST">
													<fieldset>
														<legend>Set ethernet static IP address</legend>
														<div class="form-group">
															<div class="col-md-12">
																<div class="input-group">
																	<span class="input-group-addon">169.254.1.</span>
																	<input class="form-control" id="prepend" type="number" name="end" value="<?php echo $end; ?>" min="1">
																</div>
															</div>
														</div>
													</fieldset>
													<div class="form-actions">
														<div class="row">
															<div class="col-md-12">
																<button class="btn btn-primary" type="submit" id="save"><i class="fa fa-save"></i> Save configuration</button>
															</div>
														</div>
													</div>								
												</form>
											</div>
										</div>
									</div>
									<!-- -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>