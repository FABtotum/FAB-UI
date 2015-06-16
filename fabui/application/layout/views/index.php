<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!-- META TAG -->
		<?php echo $_layout_meta_tag ?>
		<!-- END META TAG -->
		<title><?php echo $_layout_title ?></title>
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/assets/img/favicon/favicon.ico" type="image/x-icon">
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
				<span id="activity" class="activity-dropdown">
					<i class="fa fa-user"></i>
					<b class="badge">0</b>
				</span>
				<!-- AJAX DROPDOWN -->
				<div class="ajax-dropdown">
					<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default update-list  notification">
							<input type="radio" name="activity" id="<?php echo site_url('controller/updates') ?>">
							<span>Updates (0)</span>
						</label>
						<label class="btn btn-default task-list notification">
							<input type="radio"  name="activity" id="<?php echo module_url('controller').'ajax/tasks.php' ?>">
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
				
				
				
				<!-- PROFILE BUTTON -->
				<div class="btn-header transparent pull-right">
					<span>
						<?php echo anchor( 'profile', ' <i class="fa fa-user"></i> ', 'title="Profile" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- END PROFILE BUTTON -->
				
				<!-- EMERGENCY BUTTON -->
				<div class="btn-header transparent pull-right">
					<span>
						<?php echo anchor( '#', '<i class="fa fa-close"></i>', 'title="Emercengy Button"  style="cursor: pointer !important" data-action="emergencyButton" data-reset-msg="This button will stop all the operations, continue?"'); ?>
					</span>
				</div>
				<!-- END EMERGENCY BUTTON-->
				
				
				<!-- LOGOUT BUTTON -->
				<div id="log-out" class="btn-header transparent pull-right">
					<span>
						<?php echo anchor( 'login/out', '<i class="fa fa-power-off"></i>', 'title="Power Off" data-user-name="'.$_SESSION['user']['first_name'].'" data-logout-msg="What do you want to do?" data-action="userLogout" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- END LOGOUT BUTTON -->
			</div>			
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
				<!-- USB -->
				<span class="ribbon-button-alignment usb-ribbon" style="display:none">
					<span class="btn btn-ribbon "  rel="tooltip" data-placement="right" data-original-title="USB disk inserted" data-html="true"><i class="icon-fab-usb "></i></span>
				</span>
				<!-- END USB -->
				
				<!-- INTERNET -->
				<span class="ribbon-button-alignment internet" style="display:none">
					<span class="btn btn-ribbon "  rel="tooltip" data-placement="right" data-original-title="Connected to internet" data-html="true"><i class="fa fa-globe "></i></span>
				</span>
				<!-- END INTERNET -->
				
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
				<?php echo $_breadcrumbs ?>
				<!-- END BREADCRUMBS -->
				<!-- CUSTOM RIBBON -->
				<?php echo $_custom_ribbon; ?>
				<!-- END CUSTOM RIBBON -->
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
		      					<h6>Please first make sure FABUI is updated to the last version</h6>
		      					<h5>Our support forum is now live. Visit <a target="_blank" href="http://support.fabtotum.com/tickets/">http://support.fabtotum.com/tickets/</a></h5>
		      					<p>Note: use this form only to report software's bugs</p>
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
		<div id="power-off-img" style="display:none;"><img class="img-responsive" src="/assets/img/power-off.png"></div>
		<!-- END POWER OFF IMG -->
		<!-- JAVASCRIPT VARS -->
		<script type="text/javascript">
			var fabui = true;
			var number_updates = 0;
			var number_tasks = 0;
			var number_notifications = 0;
			var max_idle_time = <?php echo isset($_SESSION['user']['lock-screen']) ? $_SESSION['user']['lock-screen'] : 0 ?>;
			var setup_wizard = <?php echo $_setup_wizard == true && (isset($_SESSION['ask_wizard']) && $_SESSION['ask_wizard'] == true) ? 'true' : 'false' ?>;
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