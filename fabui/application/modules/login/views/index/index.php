<!DOCTYPE html>
<html lang="en-us" id="extr-page">
	<head>
		<meta charset="utf-8" />
		<title>FAB UI</title>
		<meta name="description" content="" />
		<meta name="author" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta name="HandheldFriendly" content="True" />
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/font-awesome.min.css"/>
		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-production.min.css"/>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/smartadmin-skins.min.css" />
		<link rel="stylesheet" href="<?php echo base_url() ?>application/layout/assets/js/plugin/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/demo.min.css"/>
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon"/>
		<link rel="icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon" />
		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="<?php echo base_url() ?>application/layout/assets/css/fonts.css" />

		<link rel="stylesheet" href="<?php echo base_url() ?>application/layout/assets/css/fabtotum_style.css" />

		<!--<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() ?>application/layout/assets/css/lockscreen.css" />-->

		<style>
			.blog-photos li {
				display: inline;
			}
			.blog-photos li img {
				opacity: 0.6;
				width: 58px;
				height: 58px;
				margin: 0 2px 8px;
			}
			.blog-photos li img:hover {
				opacity: 1;
				box-shadow: 0 0 0 2px #3498db;
			}

			/*Blog Latest Tweets*/
			.blog-twitter .blog-twitter-inner {
				padding: 10px;
				background: #f7f7f7;
				position: relative;
				margin-bottom: 10px;
				/*border-top: solid 2px #eee;*/
			}

			.blog-twitter .blog-twitter-inner, .blog-twitter .blog-twitter-inner:after, .blog-twitter .blog-twitter-inner:before {
				transition: all 0.3s ease-in-out;
				-o-transition: all 0.3s ease-in-out;
				-ms-transition: all 0.3s ease-in-out;
				-moz-transition: all 0.3s ease-in-out;
				-webkit-transition: all 0.3s ease-in-out;
			}

			.blog-twitter .blog-twitter-inner:after, .blog-twitter .blog-twitter-inner:before {
				width: 0;
				height: 0;
				right: 0px;
				bottom: 0px;
				content: " ";
				display: block;
				position: absolute;
			}

			.blog-twitter .blog-twitter-inner:after {
				border-top: 15px solid #eee;
				border-right: 15px solid transparent;
				border-left: 0px solid transparent;
				border-left-style: inset; /*FF fixes*/
				border-right-style: inset; /*FF fixes*/
			}
			.blog-twitter .blog-twitter-inner:before {
				border-bottom: 15px solid #fff;
				border-right: 0 solid transparent;
				border-left: 15px solid transparent;
				border-left-style: inset; /*FF fixes*/
				border-bottom-style: inset; /*FF fixes*/
			}

			.blog-twitter .blog-twitter-inner:hover {
				border-color: #72c02c;
				border-top-color: #72c02c;
			}
			.blog-twitter .blog-twitter-inner:hover:after {
				border-top-color: #3498db;
			}

			.blog-twitter .blog-twitter-inner span.twitter-time {
				color: #777;
				display: block;
				font-size: 11px;
			}

			.blog-twitter .blog-twitter-inner a {
				color: #72c02c;
				text-decoration: none;
			}
			.blog-twitter .blog-twitter-inner a:hover {
				text-decoration: underline;
			}

			.blog-twitter .blog-twitter-inner i.fa {
				top: 2px;
				color: #bbb;
				font-size: 18px;
				position: relative;
			}

			.blog-twitter .blog-twitter-inner a {
				color: #3498db;
			}

			.blog-twitter .blog-twitter-inner:hover {
				border-color: #3498db;
				border-top-color: #3498db;
			}
			
			.link{
				color:white;
				font-weight: bold;
			}

		</style>

	</head>
	<body class="<?php echo $body_class ?>">
		<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
		<header id="header">
			<div id="logo-group">
				<span id="logo"> <img src="<?php echo base_url() ?>application/layout/assets/img/logo-0.png" /> </span>
			</div>

			<span id="extr-page-header-space">&nbsp; <a href="javascript:void(0);"  class="btn btn-danger power-off"><i class="fa fa-power-off"></i></a></span>
			<span id="extr-page-header-space"> <span class="hidden-mobile">Need an account?</span> <a href="<?php echo site_url('login/register') ?>" class="btn btn-danger ">Create account</a> </span>

		</header>
		<div id="main" role="main">
			<!-- MAIN CONTENT -->
			<div id="content" class="container">
				<div class="row">
					<div id="cookies" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="display: none;">
						<div class="alert alert-danger fade in">
							<i class="fa-fw fa fa-warning"></i><strong>Warning</strong>You don't have cookies turned on!
						</div>
					</div>
				</div>

				<?php if($new_registration == true): ?>
				<div class="row">

					<div  class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
						<div class="alert alert-success fade in">
							<i class="fa-fw fa fa-check"></i><strong>Congratulations</strong> New registration complete
						</div>
					</div>

				</div>
				<?php endif; ?>

				<?php if($new_reset == true): ?>
				<div class="row">

					<div  class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
						<div class="alert alert-success fade in">
							<i class="fa-fw fa fa-check"></i><strong>Congratulations</strong> your password has been reset
						</div>
					</div>

				</div>
				<?php endif; ?>

				<?php if($login_failed == true): ?>
				<div class="row">

					<div  class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
						<div class="alert alert-danger bounce animated">
							<i class="fa-fw fa fa-warning"></i><strong>Oops</strong> please check your email or password
						</div>
					</div>

				</div>
				<?php endif; ?>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
						<h1 class="txt-color-red login-header-big">FAB UI <em class="font-xs txt-color-orangeDark">beta</em></h1>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<h5 class="about-heading">About FABtotum - Are you up to date?</h5>
								<p>
									FABUI : version <?php echo $fabui_version; ?> <i>beta</i>
								</p>
								<p>
									Firmware : version <?php echo $fw_version; ?> <i>beta</i>
								</p>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<h5 class="about-heading">Did you know?</h5>
								<p>

									To get support and for the manuals go to:
								</p>
								<p>
									<a href="http://support.fabtotum.com" target="blank">support.fabtotum.com<a/>
								</p>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
						<div class="well no-padding">
							<form action="<?php echo site_url('login/do_login') ?>" id="login-form" class="smart-form client-form" method="POST">
								<header>
									Sign In
								</header>
								<fieldset>
									<section>
										<label class="label">E-mail</label><label class="input"> <i
											class="icon-append fa fa-user"></i>
											<input class="trigger" type="email" name="email" value="<?php echo $email; ?>">
											<b class="tooltip tooltip-top-right"><i
											class="fa fa-user txt-color-teal"></i> Please enter email
											address/username</b> </label>
									</section>
									<section>
										<label class="label">Password</label><label class="input"> <i
											class="icon-append fa fa-lock"></i>
											<input type="password" class="trigger"
											name="password">
											<b class="tooltip tooltip-top-right"><i
											class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
									</section>
									<div class="note">
										<a id="forget-password" href="javascript:void(0)">Forgot password?</a>
									</div>

								</fieldset>
								<footer>
									<button type="button" class="btn btn-primary" id="login-button">
										Sign in
									</button>
								</footer>
							</form>
						</div>
					</div>
				</div>



				<?php if(is_internet_avaiable()): ?>
				
				<hr>

				<div class="row">

					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
						
						
						<?php if(isset($twitter_feed)): ?>
							
							<h3 class="text-primary text-center"><i class="fa fa-twitter"></i> Latest Tweet</h3>
							<div class="blog-twitter">
								
								<?php foreach($twitter_feed as $tweet): ?>
									
									
								<?php

										$created = get_time_past(strtotime($tweet -> created_at));
										$text = $tweet -> text;
										$user_mentions = $tweet -> entities -> user_mentions;
										$urls = $tweet -> entities -> urls;
										$hashtags = $tweet -> entities -> hashtags;

										$now = time();

										foreach ($user_mentions as $user) {

											$text = str_replace("@" . $user -> screen_name, "<a target='_blank' href='https://twitter.com/" . $user -> screen_name . "'>@" . $user -> screen_name . "</a>", $text);

										}

										foreach ($urls as $url) {

											$text = str_replace($url -> url, "<a target='_blank' href='" . $url -> url . "'>" . $url -> url . "</a>", $text);

										}

										foreach ($hashtags as $hash) {

											$text = str_replace("#" . $hash -> text, "<a target='_blank' href='https://twitter.com/search?q=" . $hash -> text . "'>#" . $hash -> text . "</a>", $text);

										}
								?>
									
									<div class="blog-twitter-inner">
										<i class="fa fa-twitter"></i>&nbsp;&nbsp;<a target="_blank"
											href="https://twitter.com/Fabtotum">@Fabtotum</a>
										<?php echo $text?>
										<br>
										
										<span class="twitter-time"><span class="fa fa-clock-o"></span> <?php echo $created ?> ago</span>
									</div>
									
									
								<?php endforeach; ?>	
								
							</div>
							
						<?php endif; ?>
						
						
					</div>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 text-center">
						
						<?php if(isset($instagram_hash)): ?>
							
							<h3 class="text-primary"><i class="fa fa-instagram"></i> Latest #fabtotum</h3>
							
							<ul class="list-unstyled blog-photos margin-bottom-30">
								<?php foreach($instagram_hash['data'] as $img): ?>
								<li>
									<a data-link="<?php echo $img['link']?>" class="intagram-image-hash" title="<span><?php echo $img['caption']['text'] ?></span> <span><?php echo "<a target='_blank' class='link' href='".$img['link']."'>".$img['link']."</a></span>" ?>" href="<?php echo $img['images']['standard_resolution']['url'] ?> ">
										<img src="<?php echo $img['images']['thumbnail']['url']; ?>" >
									</a>
								</li>
								<?php endforeach; ?>
	
							</ul>
						<?php endif; ?>
						
						<?php if(isset($instagram_feed)): ?>
							
							<h3 class="text-primary"><i class="fa fa-instagram"></i> Latest from <a href="http://instagram.com/fabtotum" target="_blank">@fabtotum</a></h3>
							
							<ul class="list-unstyled blog-photos margin-bottom-30 pull-right">
								<?php foreach($instagram_feed['data'] as $img): ?>
	
								<li>
									<a data-link="<?php echo $img['link']?>" class="intagram-image-feed" title="<span><?php echo $img['caption']['text'] ?></span> <span><?php echo "<a target='_blank' class='link' href='".$img['link']."'>".$img['link']."</a></span>" ?>" href="<?php echo $img['images']['standard_resolution']['url'] ?> ">
										<img src="<?php echo $img['images']['thumbnail']['url']; ?>" >
									</a>
								</li>
								<?php endforeach; ?>
	
							</ul>
						<?php endif; ?>

					</div>

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

				</div>
				
				<?php endif; ?>
				
				<div id="power-off-img" style="display:none;"><img class="img-responsive" src="/assets/img/power-off.png">
				</div>
				<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
				<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/pace/pace.min.js"></script>
				<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
				<script src="<?php echo base_url() ?>application/layout/assets/js/libs/jquery-2.1.1.min.js"></script>
				<!-- BOOTSTRAP JS -->
				<script src="<?php echo base_url() ?>application/layout/assets/js/libs/jquery-ui-1.10.3.min.js"></script>
				<script src="<?php echo base_url() ?>application/layout/assets/js/app.config.js"></script>
				<script src="<?php echo base_url() ?>application/layout/assets/js/bootstrap/bootstrap.min.js"></script>
				

				<script src="<?php echo base_url() ?>application/layout/assets/js/notification/SmartNotification.min.js"></script>

				<!-- JQUERY VALIDATE -->
				<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
				<!-- browser msie issue fix -->
				<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
				<!-- SmartClick: For mobile devices -->

				<script src="<?php echo base_url() ?>application/layout/assets/js/plugin/magnific-popup/jquery.magnific-popup.min.js"></script>
				<script src="<?php echo base_url() ?>application/layout/assets/js/fabtotum.js"></script>

				<!--[if IE 7]>
				<h1>
				Your browser is out of date, please update your browser by going to www.microsoft.com/download
				</h1>
				<![endif]-->
				<!-- MAIN APP JS FILE -->
				<script src="<?php echo base_url() ?>application/layout/assets/js/app.min.js"></script>

				<script type="text/javascript">
					
					var max_idle_time = 0;
					var number_tasks = 0;
					var number_updates = 0;
					var number_notifications  = 0;
					var setup_wizard = false;
				

					runAllForms();
					
					$(function() {
						
						clearInterval(idleInterval);
						clearInterval(safety_interval);
						clearInterval(notifications_interval);
						
					
						
						
						$('.intagram-image-hash').magnificPopup({
							type:'image'
						});
						
						$('.intagram-image-feed').magnificPopup({
							type:'image'
						});
						
						

						$(".power-off").on('click', ask_power_off);

						$("#forget-password").on('click', password_modal);
						$("#send-mail").on('click', send_mail);

						$("#login-form").validate({
	
							rules : {
								email : {
								required : true,
								email : true
								},
								password : {
								required : true
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

						$( ".trigger").on('keydown',function(e) {
							if(e.which == 13) {
							$("#login-button").trigger('click');
							}
						});

						$( ".trigger").on('keypress',function(e) {
							if(e.which == 13) {
							$("#login-button").trigger('click');
							}
						});

						$("#login-button").click(function(){

							var $valid = $("#login-form").valid();
	
							if(!$valid){
		
								return false;
							}
	
							$("#login-button").addClass('disabled');
							
							$("#login-button").html('Login..');
							
							$("#login-form").submit();
	
						});
	
						if (!are_cookies_enabled()) {
							$("#cookies").slideDown('slow', function() {
							});
						}
	
					});

					function are_cookies_enabled() {
						
						var cookieEnabled = (navigator.cookieEnabled) ? true : false;

						if ( typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
							document.cookie = "testcookie";
							cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
						}
						return (cookieEnabled);
					}

					function password_modal() {

						$('#password-modal').modal({
							keyboard : false
						});

					}

					function send_mail(){

						$("#error-message").hide();
						$("#send-mail").addClass('disabled');
						$("#send-mail").html('Sending...');
	
						$.ajax({
							url: "<?php echo site_url('login/reset_mail') ?>",
							data: {email: $("#mail-for-reset").val()},
							type: 'POST',
							dataType : 'json'
							}).done(function( response ) {
	
								$("#send-mail").removeClass('disabled');
								$("#send-mail").html('Send Mail');
		
								if(response.user == 0){
									$("#error-message").show();
									return false;
								}
		
								if(response.user == 1){
		
									$("#error-message").hide();
			
									if(response.sent == 1){
			
										$('#password-modal').modal('hide')
			
										$.smallBox({
										title : "Success",
										content : "<i class='fa fa-check'></i>A message was be sent to that address containing a link to reset your password ",
										color : "#659265",
										iconSmall : "fa fa-thumbs-up bounce animated",
										timeout : 4000
										});
			
									}
		
								}
	
							});

						}

						function ask_power_off(){

						$.SmartMessageBox({
							title: "Shutdown now ?",
							buttons: '[No][Yes]'
							}, function(ButtonPressed) {

								if (ButtonPressed === "Yes") {
		
								shutdown();
								}
								if (ButtonPressed === "No") {
		
								}

							});
						}
				</script>
	</body>
</html>