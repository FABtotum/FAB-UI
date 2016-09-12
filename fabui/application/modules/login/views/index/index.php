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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/js/plugin/magnific-popup/magnific-popup.css"> 

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
			<span id="extr-page-header-space"> <span class="hidden-mobile">Need an account?</span> <a href="<?php echo site_url('login/register') ?>" class="btn btn-primary ">Create account</a> </span>
		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">
				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-8">
						<?php if($login_failed == true): ?>
							<div class="alert alert-danger bounce animated">
								<i class="fa-fw fa fa-warning"></i><strong>Oops</strong> please check your email or password
							</div>
						<?php endif; ?>
						<?php if($new_registration == true): ?>
							<div class="alert alert-success fade in">
								<i class="fa-fw fa fa-check"></i><strong>Congratulations</strong> New registration complete
							</div>
						<?php endif; ?>
						<?php if($new_reset == true): ?>
							<div class="alert alert-success fade in">
								<i class="fa-fw fa fa-check"></i><strong>Congratulations</strong> your password has been reset
							</div>
						<?php endif; ?>	
					</div>
					<div class="col-sm-2"></div>
				</div>				
				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-8">
						<div class="well no-padding">
							<form action="<?php echo site_url('login/do_login') ?>" id="login-form" class="smart-form client-form" method="POST">
								<header><i class="fa fa-play fa-rotate-90 fa-border"></i> Sign in</header>
								<fieldset>
									<section>
										<label class="label">E-mail</label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input class="trigger" type="email" name="email" value="<?php echo $email; ?>">
											<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
									</section>

									<section>
										<label class="label">Password</label>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" class="trigger" name="password" id="password">
											<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
										<div class="note">
											<a id="forget-password" href="javascript:void(0)">Forgot password?</a>
										</div>
									</section>
								</fieldset>
								<footer>
									<button type="submit" class="btn btn-primary" id="login-button">Sign in</button>
								</footer>
							</form>

						</div>
					</div>
					<div class="col-sm-2"></div>
				</div>
			</div>

		</div>
		<?php if(is_internet_avaiable()): ?>
		<div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">Don't panic</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<p>
									Enter the email address you used when creating the account and click <strong>Send Email</strong>.
									<br>
									A message will be sent to that address containing a link to reset your password
								</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input id="mail-for-reset" type="email" class="form-control invalid" placeholder="example@fabtotum.com" required />
									<em id="error-message" style="margin-top:5px; color:#D56161; display: none;">This email is not recognized by the printer, please insert a valid mail</em>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" id="send-mail" class="btn btn-primary">Send Mail</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<?php endif; ?>
		<!-- END MAIN PANEL -->
		<!-- ==========================CONTENT ENDS HERE ========================== -->

		<!--================================================== -->
		<div id="power-off-img" style="display:none;"><img class="img-responsive" src="/assets/img/power-off.png">
		</div>

		<script src="<?php echo base_url() ?>application/layout/assets/js/app.config.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/pace/pace.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/bootstrap/bootstrap.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/notification/SmartNotification.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/fastclick/fastclick.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/app.min.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/fabtotum.js"></script>
		<script src="<?php echo base_url() ?>application/layout/assets/js/app.min.js"></script>
		<script src="<?php echo module_url('login') ?>assets/login.js"></script>

		<script type="text/javascript">
			var max_idle_time = 0;
			var number_tasks = 0;
			var number_updates = 0;
			var number_notifications = 0;
			var setup_wizard = false;
			var fabui = false;
			var pressedEmergencyButton = false;
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();
			})
		</script>

	</body>

</html>