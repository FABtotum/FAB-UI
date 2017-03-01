<!DOCTYPE html>
<html lang="en-us" >
	<head>
		<meta charset="utf-8">
		<title> Error - FABui </title>
		<meta name="description" content="">
		<meta name="author" content="">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-skins.min.css">

		<!-- SmartAdmin RTL Support is under construction-->
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/smartadmin-rtl.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/fabtotum_style.css">

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="/assets/css/demo.min.css">
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<!-- GOOGLE FONT -->
		
		<!-- Specifying a Webpage Icon for Web Clip
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="/assets/img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/assets/img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/assets/img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/assets/img/splash/touch-icon-ipad-retina.png">

		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="/assets/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="/assets/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="/assets/img/splash/iphone.png"        media="screen and (max-device-width: 320px)">
		<script src="/assets/js/libs/jquery-2.1.1.min.js"></script>
		<script src="/assets/js/libs/jquery-ui-1.10.3.min.js"></script>
		<style>#main {margin-left:0px !important;}</style>
	</head>
<body>
<!-- MAIN PANEL -->
<div id="main" role="main">
	<div id="content">
		<!-- row -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="text-center error-box">
							<h1 class="error-text tada animated"><i class="fa fa-times-circle text-danger error-icon-shadow"></i> Error</h1>
							<h2 class="font-xl"><strong>Oooops, Something went wrong!</strong></h2>
							<br />
							<p class="lead semi-bold">
								<strong>You have experienced a technical error. We apologize.</strong><br><br>
							</p>
							<?php echo $heading; ?>
							<?php echo $message; ?>
						</div>
		
					</div>
		
				</div>
		
			</div>
			
		</div>
		<!-- end row -->

	</div>
	<!-- END MAIN CONTENT -->

</div>
	
	<!--================================================== -->
	<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
	<script data-pace-options='{ "restartOnRequestAfter": true }' src="/assets/js/plugin/pace/pace.min.js"></script>
	
	<!-- These scripts will be located in Header So we can add scripts inside body (used in class.datatables.php) -->
	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script>
		if (!window.jQuery) {
			document.write('<script src="/assets/js/libs/jquery-2.0.2.min.js"><\/script>');
		}
	</script>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script>
		if (!window.jQuery.ui) {
			document.write('<script src="/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
		}
	</script> -->
	
	<!-- IMPORTANT: APP CONFIG -->
	<script src="/assets/js/app.config.js"></script>
	
	<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
	<script src="/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
	
	<!-- BOOTSTRAP JS -->
	<script src="/assets/js/bootstrap/bootstrap.min.js"></script>
	
	<!-- browser msie issue fix -->
	<script src="/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
	
	<!-- FastClick: For mobile devices -->
	<script src="/assets/js/plugin/fastclick/fastclick.min.js"></script>
	
	<!--[if IE 8]>
		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
	<![endif]-->
	
	<!-- MAIN APP JS FILE -->
	<script src="/assets/js/app.min.js"></script>		
	<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
		})
	</script>			
	<script type="text/javascript">
	
		$(document).ready(function() {
			// PAGE RELATED SCRIPTS
			$("#search-error").focus();
		})
	
	</script>
</body>