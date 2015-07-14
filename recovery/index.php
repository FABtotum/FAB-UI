<?php
	include 'header.php';
?>
<style>
	a{
		margin-top:10px !important;
	}
</style>
</head>
	<body>
		<header id="header">
			<div id="logo-group">
				<span id="logo"><img src="/assets/img/logo-0.png"></span>
			</div>
		</header>
		<div id="main" role="main">
			<div id="ribbon">
				<ol class="breadcrumb">
					<li>Recovery</li>
				</ol>
			</div>
			<div id="content">
				<div class="row">
					<div class="col-sm-12 text-center">
						<a href="/fabui/"                 class="btn btn-primary">FABui</a>
						<a href="/recovery/jog.php"       class="btn btn-primary">Jog</a>
						<a href="/recovery/wlan.php"      class="btn btn-primary">Network</a>
						<a href="/recovery/info.php"      class="btn btn-primary"><i class="fa fa"></i> Info</a>
						<a href="/recovery/log.php"       class="btn btn-primary">Log</a>
						<a href="/recovery/flash.php"     class="btn btn-primary">Flash Firmware</a>
						<a href="/recovery/setup.php"     class="btn btn-primary">Setup</a>
						<a href="/phpmyadmin"             class="btn btn-primary">Database</a>
						<a href="/recovery/macrosim.php"  class="btn btn-primary">Macro Simulator</a>
						<a href="/recovery/test.php"      class="btn btn-primary"><i class="fa fa-fw fa-wrench"></i> Test</a>
						<a href="/recovery/install"       class="btn btn-primary">Re-Install</a>
						<a href="/recovery/shutdown.php"  class="btn btn-warning ">Shutdown</a>
						<a href="/recovery/reboot.php"    class="btn btn-warning ">Reboot</a>
					</div>
				</div>
			</div>
		</div>
		<?php include 'footer.php'; ?>
	</body>
</html>
