<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title>MYFABTOTUM</title>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="HandheldFriendly" content="True" />
    
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/font-awesome.min.css"/>
    
    <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-production.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-skins.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/demo.css"/>
    
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon"/>
    <link rel="icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon" />
    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="<?php echo base_url() ?>application/layout/assets/css/fonts.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/lockscreen.css" />
</head>
<body id="login" class="animated fadeInDown">
	<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
	<header id="header">
	<span id="logo"><img src="<?php echo base_url() ?>application/layout/assets/img/logo-0.png" /></span>
		<div id="logo-group">
			<span id="logo"> MYFABTOTUM </span>
			<!-- END AJAX-DROPDOWN -->
		</div>
	</header>
	<div id="main" role="main">
		<!-- MAIN CONTENT -->
		<div id="content" class="container">

			<div class="row">
				<div
					class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
					<h1 class="txt-color-red login-header-big">FABTOTUM</h1>


					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<h5 class="about-heading">About FABtotum - Are you up to date?</h5>
							<p>FABUI    : updated 1.0</p>
							<p>Firmware : updated 1.0</p>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<h5 class="about-heading">Did you know?</h5>
							<p>You can connect your FABtotum Personal Fabricator to the Internet by enabling port forwarding in your router.</p>
							<p>Want to learn more? <a href="http://support.fabtotum.com" target="blank">support.fabtotum.com<a/></p>
						</div>
					</div>

				</div>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
					<div class="well no-padding">
						<form action="<?php echo site_url('login/do_login') ?>" id="login-form" class="smart-form client-form" method="POST">
							<header> Sign In </header>

							<fieldset>

								<section>
									<label class="label">E-mail</label> <label class="input"> <i
										class="icon-append fa fa-user"></i> <input type="email"
										name="email"> <b class="tooltip tooltip-top-right"><i
											class="fa fa-user txt-color-teal"></i> Please enter email
											address/username</b>
									</label>
								</section>

								<section>
									<label class="label">Password</label> <label class="input"> <i
										class="icon-append fa fa-lock"></i> <input type="password"
										name="password"> <b class="tooltip tooltip-top-right"><i
											class="fa fa-lock txt-color-teal"></i> Enter your password</b>
									</label>
									<div class="note">
										<a href="javascript:void(0)">Forgot password?</a>
									</div>
								</section>

								<section>
									<label class="checkbox"> <input type="checkbox" name="remember"
										checked=""> <i></i>Stay signed in
									</label>
								</section>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">Sign in</button>
							</footer>
						</form>

					</div>
                   
				</div>
			</div>
		</div>

	</div>

	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/libs/jquery/2.0.2/jquery.min.js"></script>
	<!-- BOOTSTRAP JS -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/bootstrap/bootstrap.min.js"></script>
	<!-- CUSTOM NOTIFICATION -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/notification/SmartNotification.min.js"></script>
	<!-- JQUERY VALIDATE -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
	<!-- browser msie issue fix -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
	<!-- SmartClick: For mobile devices -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/smartclick/smartclick.js"></script>
	<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/oauthpopup/jquery.oauthpopup.js"></script>
	<!--[if IE 7]>
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
		<![endif]-->
	<!-- MAIN APP JS FILE -->
	<script src="<?php echo base_url() ?>application/layout/assets/js/app.js"></script>
    
	<script type="text/javascript">
			runAllForms();
			$(function() {
				$("#login-form").validate({
					
					rules : {
						email : {
							required : true,
							email : true
						},
						password : {
							required : true,
							minlength : 3,
							maxlength : 20
						}
					},

				
					messages : {
						email : {
							required : 'Please enter your email address',
							email : 'Please enter a VALID email address'
						},
						password : {
							required : 'Please enter your password'
						}
					},

					
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});

			    $('#facebook').click(function () {
					Login();
			    });
				
			});

		</script>
</body>
</html>
