<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
        <meta name="HandheldFriendly" content="True">
		<?php echo $_layout_meta_tag ?>
			<title>
				<?php echo $_layout_title ?>
			</title>
			<!-- FAVICONS -->
			<link rel="shortcut icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon">
			<link rel="icon" href="<?php echo base_url() ?>application/layout/assets/img/favicon/favicon.ico" type="image/x-icon">
			<!-- CSS -->
			<?php echo $_css_files ?>
				<!-- CSS IN PAGE -->
				<?php echo $_css_in_page; ?>
	</head>
	<body class="<?php echo $_skin; ?>">
		<header id="header">
			<div id="logo-group">
				<span id="logo">
					<img src="<?php echo base_url(); ?>application/layout/assets/img/<?php  echo $_skin == 'smart-style-0' ? 'logo-0.png' : 'logo-3.png'?>" />
				</span>
				<?php //$_update_list = myfab_update_list(); 
                        $_update_list = array();
                ?>
					<span id="activity" class="activity-dropdown">
						<i class="fa fa-user">
						</i>
						<b class="badge">
							<?php echo count($_update_list); ?>
						</b>
					</span>
					<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
					<div class="ajax-dropdown">
						<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<label class="btn btn-default update-list active notification">
								<input type="radio" name="activity" id="<?php echo site_url('controller/updates') ?>">
								<span>Updates (0)</span>
							</label>
							<label class="btn btn-default notification">
								<input type="radio" name="activity" id="<?php echo site_url('controller/notifications') ?>">
								notify (0)
							</label>
							<label class="btn btn-default task-list notification">
								<input type="radio"  name="activity" id="<?php echo module_url('controller').'ajax/tasks.php' ?>">
								<span>Tasks (0)</span>
							</label>
						</div>
						<!-- notification content -->
						<div class="ajax-notifications custom-scroll">
							<div class="alert alert-transparent">
								<h4>
									Click a button to show messages here
								</h4>
								This blank page message helps protect your privacy, or you can show the first message here automatically.
							</div>
							<i class="fa fa-lock fa-4x fa-border">
							</i>
						</div>
						<!-- end notification content -->
						<!-- footer: refresh area -->
						<span>
							Last updated on: 12/12/2013 9:43AM
							<button id="refresh-notifications" type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
								<i class="fa fa-refresh">
								</i>
							</button>
						</span>
						<!-- end footer -->
					</div>
					<!-- END AJAX-DROPDOWN -->
			</div>
			<div class="pull-right">
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span>
						<a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i> </a>
					</span>
				</div>
				<!-- end collapse menu -->
				<!-- PROFILE BUTTON -->
				<div class="btn-header transparent pull-right">
					<span>
						<?php echo anchor( 'profile', '<i class="fa fa-user"></i>', 'title="Profile" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- PLUGIN BUTTON -->
				<div class="btn-header transparent pull-right">
					<span>
						<?php echo anchor( 'plugin', '<i class="icon-fab-plugin"></i>', 'title="Plugins" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- LOGOUT BUTTON -->
				<div id="logout" class="btn-header transparent pull-right">
					<span>
						<?php echo anchor( 'login/out', '<i class="fa fa-sign-out"></i>', 'title="Sign Out" style="cursor: pointer !important"'); ?>
					</span>
				</div>
				<!-- multiple lang dropdown : find all flags in the image folder -->
                <!--
				<ul class="header-dropdown-list hidden-xs">
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
						<img alt="" src="<?php echo base_url() ?>application/layout/assets/img/flags/us.png">
						<span> US </span> <i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu pull-right">
							<li class="active">
								<a href="javascript:void(0);">
								<img alt=""	src="<?php echo base_url() ?>application/layout/assets/img/flags/us.png"> US</a>
							</li>
							 eventuali altre lingue 
						</ul>
					</li>
				</ul>
                -->
				<!-- end multiple lang -->
			</div>
		</header>
		<!-- END HEADER -->
		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">
			<!-- User info -->
			<div class="login-info">
				<span>
					<!-- User image size is adjusted inside CSS, it should stay as it -->
					<a href="<?php echo site_url('profile') ?>" id="">
					<?php if($_SESSION['type'] == 'facebook'): ?> 
					<img class="online" src="https://graph.facebook.com/<?php echo $_SESSION['facebook_id'] ?>/picture?type=large" />
					<?php endif; ?>
					<?php if($_SESSION['type'] == 'fabtotum'): ?> 
					<img class="online"  />
					<?php endif; ?>  
					<span><?php echo $_SESSION['first_name'];; ?> <?php echo $_SESSION['last_name']; ?> </span></a>
				</span>
			</div>
			<!-- end user info -->
			<nav id="sidebar-menu">
				<ul>
					<?php echo $_sidebar_menu_items; ?>
				</ul>
			</nav>
			<span class="minifyme">
				<i class="fa fa-arrow-circle-left hit">
				</i>
			</span>
		</aside>
		<!-- END NAVIGATION -->
		<!-- MAIN PANEL -->
		<div id="main" role="main">
			<!-- RIBBON -->
			<div id="ribbon">
                <?php //if(is_internet_avaiable()): ?>
				<span class="ribbon-button-alignment internet" style="display:none">
					<span class="btn btn-ribbon "  rel="tooltip" data-placement="bottom" data-original-title="Connected to internet"
					data-html="true">
						<i class="fa fa-globe">
						</i>
					</span>
				</span>
                <?php //endif; ?>
                <span class="ribbon-button-alignment">
					<span id="refresh" class="btn btn-ribbon" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings."
					data-html="true">
						<i class="fa fa-refresh">
						</i>
					</span>
				</span>
				<?php echo $_breadcrumbs ?>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<?php echo $_controller_view ?>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<!--<script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo base_url() ?>/application/layout/assets/js/plugin/pace/pace.min.js">
		</script>-->
		<!-- JAVASCRIPT FILE INLCUSIONS -->
        <script type="text/javascript">
            var number_updates = 0; 
            var number_tasks = 0;
            var number_notifications = 0;
        </script>
		<?php echo $_js_files ?>
		<!-- JAVASCRIPT IN PAGE -->
		<?php echo $_js_in_page; ?>
	</body>

</html>