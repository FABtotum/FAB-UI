<!DOCTYPE html>
<html lang="en-us" id="extr-page" class="animated fadeInDown">
	<head>
		<meta charset="utf-8">
		<title>FAB UI - Login </title>
		<meta name="description" content="Login Page">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-rtl.min.css">
		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/demo.min.css">

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/js/plugin/fancybox/jquery.fancybox.css">  

		<link rel="stylesheet" href="<?php echo base_url() ?>application/layout/assets/css/fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url() ?>application/layout/assets/css/fabtotum_style.css" />

		<script src="<?php echo base_url() ?>application/layout/assets/js/libs/jquery-2.1.1.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/libs/jquery-ui-1.10.3.min.js"></script>

		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

	</head>
	<body >
		<header id="header">
			<div id="logo-group">
				<span id="logo"> <img src="<?php echo base_url() ?>application/layout/assets/img/logo-0.png" /> </span>
			</div>
			<span id="extr-page-header-space">&nbsp; <a href="javascript:void(0);"  class="btn btn-danger power-off"><i class="fa fa-power-off"></i></a></span>
			<span id="extr-page-header-space"> <span class="hidden-mobile">Already registered?</span> <a href="<?php echo site_url('login') ?>" class="btn btn-primary">Sign In</a> </span>
		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-8">
						<div class="well no-padding">

							<form action="<?php echo site_url('login/do_registration') ?>" id="form-register" class="smart-form client-form" method="POST" >
								<header><i class="fa fa-play rotate-90"></i> New Registration</header>
								<fieldset>
									<section>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="first_name" placeholder="First name">
											<b class="tooltip tooltip-bottom-right">Needed to enter the website</b> </label>
									</section>

									<section>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="last_name" placeholder="Last name">
											<b class="tooltip tooltip-bottom-right">Needed to enter the website</b> </label>
									</section>

									<section>
										<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="email" name="email" placeholder="Email address">
											<b class="tooltip tooltip-bottom-right">Needed to verify your account</b> </label>
									</section>

									<section>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="password" placeholder="Password" id="password">
											<b class="tooltip tooltip-bottom-right">Don't forget your password</b> </label>
									</section>

									<section>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="passwordConfirm" placeholder="Confirm password">
											<b class="tooltip tooltip-bottom-right">Don't forget your password</b> </label>
									</section>
								</fieldset>

								<footer>
									<button id="register-button" type="button" class="btn btn-primary">Register</button>
								</footer>
							</form>

						</div>
					</div>
					<div class="col-sm-2"></div>
				</div>
			</div>

		</div>
		<!-- END MAIN PANEL -->
		<!-- ==========================CONTENT ENDS HERE ========================== -->
		<!--================================================== -->
		<a class="fancybox-shutdown hidden" title="Now you can switch off the power" href="/assets/img/power-off.png"><img class="img-responsive" src="/assets/img/power-off.png"></a>

		<script src="<?php echo base_url() ?>application/layout/assets/js/app.config.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/pace/pace.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/bootstrap/bootstrap.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/notification/SmartNotification.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/fastclick/fastclick.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/notification/FabtotumNotification.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/fancybox/jquery.fancybox.pack.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/app.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/demo.min.js?"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/fabtotum.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/app.min.js"></script>
		<script src="<?php echo module_url('login') ?>assets/register.js"></script>

		<script type="text/javascript">
			var max_idle_time = 0;
			var number_tasks = 0;
			var number_updates = 0;
			var number_notifications = 0;
			var setup_wizard = false;
			var fabui = false;
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();
			})
		</script>

	</body>

</html>