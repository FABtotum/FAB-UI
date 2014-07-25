<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="utf-8">


<title>MYFABTOTUM</title>
<meta name="description" content="">
<meta name="author" content="">

<!-- Use the correct meta names below for your web application
			 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
			 
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">-->

<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<!-- Basic Styles -->
<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url() ?>application/layout/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url() ?>application/layout/assets/css/font-awesome.min.css">

<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-production.css">
<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-skins.css">

<!-- SmartAdmin RTL Support is under construction
			<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->

<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url() ?>application/layout/assets/css/demo.css">

<!-- FAVICONS -->
<link rel="shortcut icon"
	href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico"
	type="image/x-icon">
<link rel="icon"
	href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico"
	type="image/x-icon">

<!-- GOOGLE FONT -->
<link rel="stylesheet"
	href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

<link rel="stylesheet" type="text/css" media="screen"
	href="<?php echo base_url() ?>application/layout/assets/css/lockscreen.css">

<style>
#header>:first-child,aside {
	width: 100% !important;
}

.lockscreen {
	margin-top: 0px !important;
	position: absolute;
	top: 0% !important;
}

.bootstrapWizard li {
	width: 20% !important;
}

.lockscreen .logo img {
	width: auto;
	max-width: 100% !important;
	height: auto;
}
</style>

</head>
<body class="animated fadeInDown">


	<div id="main" role="main">

		<!-- MAIN CONTENT -->
		<div>
			<form class="lockscreen animated flipInY">
				<div class="logo text-align-center">
					<img src="/assets/img/logo_fabtotum.png" />
				</div>
				<div>
					<img src="/assets/img/logo_fabtotum.png" alt="" width="120"
						height="120">
					<div>
						<h1>
							<i
								class="fa fa-user fa-3x text-muted air air-top-right hidden-mobile"></i>myFAB
							<small><i class="fa fa-lock text-muted"></i> &nbsp;LOGIN</small>
						</h1>

						<br>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i
											class="fa fa-envelope fa-lg fa-fw"></i> </span> <input
											class="form-control input-lg" placeholder="email@address.com"
											type="text" name="email" id="email">

									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i
											class="fa fa-envelope fa-lg fa-fw"></i> </span> <input
											class="form-control input-lg" placeholder="password"
											type="password" name="email" id="email">

									</div>
								</div>
							</div>
						</div>
					</div>
					<br>
				</div>
			</form>
		</div>

	</div>


	<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/libs/jquery/2.0.2/jquery.min.js"></script>


	<!-- BOOTSTRAP JS -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/bootstrap/bootstrap.min.js"></script>

	<!-- CUSTOM NOTIFICATION -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/notification/SmartNotification.min.js"></script>

	<!-- JQUERY VALIDATE -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>

	<!-- browser msie issue fix -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

	<!-- SmartClick: For mobile devices -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/plugin/smartclick/smartclick.js"></script>



	<script
		src="<?php echo base_url() ?>application/layout/assets/js/plugin/oauthpopup/jquery.oauthpopup.js"></script>




	<!--[if IE 7]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

	<!-- MAIN APP JS FILE -->
	<script
		src="<?php echo base_url() ?>application/layout/assets/js/app.js"></script>

	<script type="text/javascript">
			runAllForms();

			$(function() {
				// Validation
				$("#login-form").validate({
					// Rules for form validation
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

					// Messages for form validation
					messages : {
						email : {
							required : 'Please enter your email address',
							email : 'Please enter a VALID email address'
						},
						password : {
							required : 'Please enter your password'
						}
					},

					// Do not change code below
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});

			    $('#facebook').click(function () {
					Login();
			    });
				
			});


			function Login(){

				FB.login(function(login_response) {
					
					var access_token = login_response.authResponse.accessToken;
		
					if (login_response.authResponse){
						FB.api('/me', function(response) {

							response.accessToken = access_token;

							$.ajax({
								  type: "POST",
								  url: "<?php echo site_url('login/facebook') ?>",
								  data: response
								})
								  .done(function(response) {
									  window.location = '<?php echo site_url('login') ?>';
							});
						});
					} else {
					console.log('User cancelled login or did not fully authorize.');
					}
				},{scope: 'email,user_photos,user_videos'});
			}


			// Load the SDK asynchronously
			
		</script>

</body>
</html>
