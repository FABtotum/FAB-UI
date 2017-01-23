<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="FABtotum Web User Interface">
		<meta name="author" content="FABtotum Development Team">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="HandheldFriendly" content="true">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<!-- END META TAG -->
		<title><?php echo $_layout_title ?></title>
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon"          href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<!-- END FAVICONS -->
		<!-- CSS FILES -->
		<?php echo $_css_files ?>
		<!-- END CSS FILES -->
		<!-- JS HEADER -->
		<?php echo $_header_js_files; ?>
		<!-- END JS HEADER -->
		<!-- CSS IN PAGE -->
		<?php echo $_css_in_page; ?>
		<!-- END CSS IN PAGE -->
	</head>
	<body class="<?php echo $_skin; ?> <?php echo isset($_SESSION['user']['layout']) ? $_SESSION['user']['layout'] : '' ?> ">
		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">
				<span id="logo">
					<img src="/assets/img/<?php echo $_skin == 'smart-style-0' ? 'logo-0.png' : 'logo-3.png' ?>">
				</span>
				<span id="fabtotum-activity" class="activity-dropdown">
					<i class="fa fa-user"></i>
					<b class="badge">0</b>
				</span>
				<!-- AJAX DROPDOWN -->
				<div class="ajax-dropdown">
					<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default update-list  notification">
							<input type="radio" name="fabtotum-activity" id="<?php echo site_url('updates/notification') ?>">
							<span>Updates (0)</span>
						</label>
						<label class="btn btn-default task-list notification">
							<input type="radio"  name="fabtotum-activity" id="<?php echo module_url('controller').'ajax/tasks.php' ?>">
							<span>Tasks (0)</span>
						</label>
					</div>
					<div class="ajax-notifications custom-scroll">
						<div class="alert alert-transparent"></div>
					</div>
					<span>
						<button id="refresh-notifications" type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
							<i class="fa fa-refresh"></i>
						</button>
					</span>
				</div>
				<!-- END AJAX DROPDOWN -->
			</div>
			
			<div class="pull-right">
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span>
						<a href="javascript:void(0);" title="Collapse Menu" data-action="toggleMenu"><i class="fa fa-reorder"></i> </a>
					</span>
				</div>
				<!-- end collapse menu button -->
				
				<!-- PROFILE BUTTON 
				<div class="btn-header transparent pull-right hidden-xs">
					<span>
						<?php echo anchor( 'profile', ' <i class="fa fa-user"></i> ', 'rel="tooltip" data-placement="left" title="Profile" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- END PROFILE BUTTON -->
				
				
				
				
				<!-- LOGOUT BUTTON -->
				<div id="log-out" class="btn-header transparent pull-right hidden-xs">
					<span>
						<?php echo anchor( 'login/out', '<i class="fa fa-power-off"></i>', ' rel="tooltip" data-placement="left"  title="Power Off/Log Out" data-user-name="'.$_SESSION['user']['first_name'].'" data-logout-msg="What do you want to do?" data-action="fabUserLogout" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- END LOGOUT BUTTON -->
				
				
				<!-- #Voice Command: Start Speech
						<div id="speech-btn" class="btn-header transparent pull-right hidden-sm hidden-xs">
							<div> 
								<a href="javascript:void(0)" title="Voice Command" data-action="voiceCommand"><i class="fa fa-microphone"></i></a> 
								<div class="popover bottom"><div class="arrow"></div>
									<div class="popover-content">
										<h4 class="vc-title">Voice command activated <br><small>Please speak clearly into the mic</small></h4>
										<h4 class="vc-title-error text-center">
											<i class="fa fa-microphone-slash"></i> Voice command failed
											<br><small class="txt-color-red">Must <strong>"Allow"</strong> Microphone</small>
											<br><small class="txt-color-red">Must have <strong>Internet Connection</strong></small>
										</h4>
										<a href="javascript:void(0);" class="btn btn-success" onclick="commands.help()">See Commands</a> 
										<a href="javascript:void(0);" class="btn bg-color-purple txt-color-white" onclick="$('#speech-btn .popover').fadeOut(50);">Close Popup</a> 
									</div>
								</div>
							</div>
						</div>
						<!-- end voice command -->

				
				
			</div>
			
			<div class="pull-right emergency-container">
				
				<!-- RESET CONTROLLER BUTTON -->
				<div class="btn-header transparent">
					<span>
						<?php echo anchor( '#', '<i class="fa fa-bolt"></i>', 'rel="tooltip" data-placement="left" data-html="true" data-original-title="Reset Controller.<br>This will reset control board"  style="cursor: pointer !important" data-action="resetController" data-reset-msg="This button will reset control board, continue?"'); ?>
					</span>
				</div>
				<!-- END RESET CONTROLLER BUTTON-->
				
			</div>
			
			
			<div class="pull-right emergency-container">
				<!-- EMERGENCY BUTTON -->
				<div class="btn-header transparent">
					<span>
						<?php echo anchor( '#', '<i class="fa fa-warning "></i>', 'rel="tooltip" data-placement="left" data-html="true" data-original-title="Emergency Button. <br>This will stop all operations on the FABtotum"  style="cursor: pointer !important" data-action="emergencyButton" data-reset-msg="This button will stop all the operations, continue?"'); ?>
					</span>
				</div>
				<!-- END EMERGENCY BUTTON-->
			</div>
			
			<!-- JOG SHORTCUT BUTTONS -->
			<div class="pull-right pad-container hidden-xs" style="position: relative;">
				<div class="btn-header transparent">
					<span id="jog-shortcut">
						<a href="javascript:void(0)" style="cursor: pointer !important;" title="Jog" rel="tooltip" data-placement="left" data-html="true" data-original-title="Jog"><i class="fa fa-gamepad"></i></a>
					</span>
					
					<div class="top-ajax-jog-dropdown">
						<div class="">
							<div class="btn-group-vertical">
								<a  href="javascript:void(0)" data-attribue-direction="up-left"  data-attribute-keyboard="103" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-left fa-1x fa-rotate-45"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="left"      data-attribute-keyboard="100" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-left "> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="down-left" data-attribute-keyboard="97" class="btn btn-default btn-circle btn-xl jog directions "> <i class="fa fa-arrow-down fa-rotate-45 "> </i> </a>
							</div>
							<div class="btn-group-vertical">
								<a href="javascript:void(0)" data-attribue-direction="up"   data-attribute-keyboard="104" class="btn btn-default btn-circle btn-xl jog directions btn-xl "> <i class="fa fa-arrow-up fa-1x"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="home" data-attribute-keyboard="101" class="btn btn-default btn-circle btn-xl jog zero_all "> <i class="fa fa-bullseye"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="down" data-attribute-keyboard="98"  class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-down "> </i> </a>
							</div>
							<div class="btn-group-vertical">
								<a href="javascript:void(0)" data-attribue-direction="up-right"   data-attribute-keyboard="105" class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-up fa-1x fa-rotate-45"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="right"      data-attribute-keyboard="102" class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-right"> </i> </a>
								<a href="javascript:void(0)" data-attribue-direction="down-right" data-attribute-keyboard="99"  class="btn btn-default btn-circle btn-xl jog directions"> <i class="fa fa-arrow-right fa-rotate-45"> </i> </a>
							</div>
							<div class="btn-group-vertical" style="margin-left: 10px;">
								<a rel="tooltip" data-placement="right" data-original-title="Move Z Up" href="javascript:void(0)"  class="btn btn-default jog axisz" data-attribute-step="1" data-attribute-function="zdown"> <i class="fa fa-angle-double-up"> </i>&nbsp;Z </a>
								<hr/>
								<a rel="tooltip" data-placement="right" data-original-title="Move Z Down" href="javascript:void(0)" class="btn btn-default jog axisz" data-attribute-step="1" data-attribute-function="zup"> <i class="fa fa-angle-double-down"> </i>&nbsp; Z </a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END JOG SHORTCUT BUTTONS -->
			<!-- TEMPERATURES CONTROL BUTTONS -->
			<div class="pull-right top-bar-temperatures hidden-xs" style="position: relative;">
				<span id="top-temperatures" style="float:right;">
						<a href="javascript:void(0);" title="Temperatures controls" rel="tooltip" data-placement="left" data-html="true" data-original-title="Temperatures<br> controls" >
							<span><i class=" icon-fab-term"></i> </span>
							<?php if($_show_nozzle_temp): ?>
								<span>N: <span id="top-bar-nozzle-actual">-</span>/<span id="top-bar-nozzle-target">-</span>&deg; - </span>
							<?php endif; ?>
							<span>
								B: <span id="top-bar-bed-actual">-</span>/<span id="top-bar-bed-target">-</span>&deg;
							</span>
						</a>
				</span>
				<div class="top-ajax-temperatures-dropdown <?php echo !$_show_nozzle_temp ? 'no-nozzle-temp' : ''; ?>">
					<?php if($_show_nozzle_temp): ?>
					<h4><i class=" icon-fab-term"></i> Nozzle</h4>
					<div id="top-act-ext-temp"  class="noUiSlider top-act-ext-temp"></div>
					<div id="top-ext-target-temp" class="noUiSlider top-ext-target-temp top-extruder-range"></div>
					<hr class="simple margin-top-60">
					<?php endif; ?>
					<h4 class="margin-top-10"><i class=" icon-fab-term"></i> Bed</h4>
					<div id="top-act-bed-temp" class="noUiSlider top-act-bed-temp"></div>
					<div id="top-bed-target-temp" class="noUiSlider top-bed-target-temp top-bed-range"></div>
				</div>
			</div>
			<!-- END TEMPERATURES CONTROL BUTTONS -->	
		</header>
		<!-- END HEADER -->
		<!-- LEFT PANEL -->
		<aside id="left-panel">
			<div class="login-info">
				<span>
					<a href="<?php echo site_url('profile') ?>" id="">
						<img id="user-avatar" src="<?php echo $_SESSION['user']['avatar'] != '' ?  $_SESSION['user']['avatar'] : '/assets/img/male.png';?>"  />
						<span id="user_basic_info"><?php echo $_SESSION['user']['first_name'];; ?> <?php echo $_SESSION['user']['last_name']; ?> </span>
					</a>	
				</span>
			</div>
			<!-- MENU -->
			<nav>
				<ul><?php echo $_sidebar_menu_items; ?></ul>
			</nav>
			<!-- END MENU -->
			<span class="minifyme" data-action="minifyMenu"><i class="fa fa-arrow-circle-left hit"></i></span>
		</aside>
		<!-- END LEFT PANEL -->
		<!-- MAIN PANEL -->
		<div id="main" role="main">
			<!-- RIBBON -->
			<div id="ribbon">
				<!-- LOCK -->
				<span class="ribbon-button-alignment lock-ribbon">
					<span class="btn btn-ribbon "  rel="tooltip" data-placement="right" data-original-title="Lock Screen" data-html="true"><i class="fa fa-lock"></i></span>
				</span>
				<span class="ribbon-button-alignment">
					<span id="refresh" data-action="resetWidgets" class="btn btn-ribbon" data-title="refresh" rel="tooltip" data-placement="right" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings."
					data-html="true">
						<i class="fa fa-refresh"></i>
					</span>
				</span>
				
				<!-- BREADCRUMBS -->
				<?php //echo $_breadcrumbs ?>
				<ol class="breadcrumb"></ol>
				<!-- END BREADCRUMBS -->
				<!-- CUSTOM RIBBON -->
				<?php echo $_custom_ribbon; ?>
				<!-- END CUSTOM RIBBON -->
				
				<div id="top-alert-messages" class="hidden-xs pull-right">
					
				</div>
				
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content"><?php echo $_controller_view ?></div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
		<!-- FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<button data-toggle="modal" data-backdrop="static" data-target=".bug-modal" class="btn btn-xs bg-color-orange txt-color-white pull-right internet" style="margin-left: 5px;display:none" > 
						<i class="fa fa-bug"></i>&nbsp;<span class="hidden-mobile">Report a bug</span>
					</button>
					<button data-toggle="modal" data-backdrop="static" data-target=".suggestion-modal" class="btn btn-xs bg-color-blue txt-color-white pull-right internet" style="display:none">
						<i class="fa fa-stack-overflow"></i>&nbsp;<span class="hidden-mobile">Request a feature</span>
					</button>
					<span class="txt-color-white ">FAB UI <em class="font-xs txt-color-orangeDark">beta</em> v.<?php echo $_SESSION['fabui_version'] ?></span>
					
				</div>
			</div>
		</div>
		<!-- END FOOTER -->
		<!-- SUGGESTIONS MODAL -->
		<div class="modal fade suggestion-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
		      		<div class="modal-header">
		      			<h4 class="modal-title"><i class="fa fa-stack-overflow"></i> Request a feature</h4>
		      		</div>
		      		<div class="modal-body">
		      			<div class="row">
		      				<div class="col-md-12">
		      					<h5>Help us to improve even more your FABtotum experience</h5>
		      					<div class="form-group">
		      						<input id="suggestion-title" class="form-control" type="text" placeholder="Subject">
		      					</div>
		      					<div class="form-group">
		      						<textarea id="suggestion-text" class="form-control" placeholder="Content" rows="5"></textarea>
		      					</div>
		      				</div>
		      			</div>
		      		</div>
		      		<div class="modal-footer">
		      			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		      			<button id="send-suggestion" type="button" class="btn btn-primary"> <i class="fa fa-envelope-o"></i> Send </button>
		      		</div>
				</div>
			</div>
		</div>
		<!-- END SUGGESTIONS MODAL -->
		<!-- BUG REPORT MODAL -->
		<div class="modal fade bug-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog  modal-lg">
				<div class="modal-content">
		      		<div class="modal-header">
		      			<h4 class="modal-title"><i class="fa fa-bug"></i> Report a bug</h4>
		      		</div>
		      		<div class="modal-body">
		      			<div class="row">
		      				<div class="col-md-12">
		      					<h6>Please first make sure FABUI is up-to-date.</h6>
		      					<h5>Our support forum is now live. Visit <a target="_blank" href="http://fabtotum.com/support">http://fabtotum.com/support</a></h5>
		      					<p><strong></strojg>Note: use this form only to report software's bugs</strong></p>
		      					<div class="form-group">
		      						<input id="bug-title" class="form-control" type="text" placeholder="Subject">
		      					</div>
		      					<div class="form-group">
		      						<textarea id="bug-text" class="form-control" placeholder="" rows="5"></textarea>
		      					</div>
		      				</div>
		      			</div>
		      		</div>
		      		<div class="modal-footer">
		      			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		      			<button id="send-bug" type="button" class="btn btn-primary"> <i class="fa fa-envelope-o"></i> Send </button>
		      		</div>
				</div>
			</div>
		</div>
		<!-- END BUG REPORT MODAL --> 
		<!-- FORM LOCK SCREEN -->
		<form id="lock-screen-form" action="<?php echo site_url('login/lock')?>" method="POST"></form>
		<!-- END FORM LOCK SCREEN -->
		<!-- POWER OFF IMG -->
		
		<a class="fancybox-shutdown hidden" title="Now you can switch off the power" href="/assets/img/power-off.png"><img class="img-responsive" src="/assets/img/power-off.png"></a>
		
		<!--<div id="power-off-img" style="display:none;"><img class="img-responsive" src="/assets/img/power-off.png"></div>-->
		<!-- END POWER OFF IMG -->
		<!-- JAVASCRIPT VARS -->
		<script type="text/javascript">
			var fabui = true;
			var number_updates = 0;
			var number_tasks = 0;
			var number_notifications = 0;
			var max_idle_time = <?php echo isset($_SESSION['user']['lock-screen']) ? $_SESSION['user']['lock-screen'] : 0 ?>;
			var MODULE = '<?php echo $this->router->fetch_class(); ?>';
			var pressedEmergencyButton = false;
			var PRINTER_BUSY = <?php echo $_printer_busy; ?>;
			var MAX_NOZZLE_TEMP = <?php echo $_max_temp; ?>;
			var SHOW_FEEDER = <?php echo $_show_feeder == 1 ? 'true' : 'false'; ?>;
		</script>
		<!-- END JAVASCRIPT VARS -->
		<!-- JAVASCRIPT INCLUSIONS -->
		<?php echo $_js_files ?>
		<!-- END JAVASCRIPT INCLUSIONS -->
		<!-- IN PAGE JAVASCRIPT -->
		<?php echo $_js_in_page; ?>
		<!-- END IN PAGE JAVASCRIPT -->
	</body>
</html>