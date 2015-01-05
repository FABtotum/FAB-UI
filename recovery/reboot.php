<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reboot'])) {
	
	shell_exec('sudo reboot');
	$reboot = true;
	
}

include 'header.php';
?>
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
				<li>
					<a href="/recovery/index.php">Recovery</a>
				</li>
				<li>
					Reboot
				</li>
			</ol>
		</div>
		<div id="content">
			<?php if(isset($reboot)): ?>
			<div class="row">
				<div class="col-sm-12">
					<div class="alert alert-success fade in">
						Server rebooting...
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-12">
					<div class="well">
						<form method="POST">
							<p>
								Do you want to reboot now? 
								<button type="submit" class="btn btn-primary" name="reboot">Yes</button>
								<a href="/recovery/index.php" class="btn btn-primary">No</a>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	include 'footer.php';
	?>

	<script type="text/javascript"></script>

</body>
</html>
