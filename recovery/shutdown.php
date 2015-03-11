<?php
//shell_exec('sudo python /var/www/fabui/python/gmacro.py shutdown');
//echo "<h2 style='text-align:center;'>Shutdown in progress...</h2>";
?>
<!--
<script type="text/javascript">
	setTimeout(function(){

		document.getElementById('final-message').innerHTML = 'Now you can switch off the power';

	}, 15000);
</script>

<h2 style="text-align:center;" id="final-message"></h2>
-->
<?php
//shell_exec('sudo shutdown now');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['shutdown'])) {


	shell_exec('sudo python /var/www/fabui/python/gmacro.py shutdown');

	shell_exec('sudo poweroff');
	$shutdown = true;

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
					Shutdown
				</li>
			</ol>
		</div>
		<div id="content">
			<?php if(isset($shutdown)): ?>
			<div class="row">
				<div class="col-sm-12">
					<div class="alert alert-success fade in">
						Server shutting down...
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-12">
					<div class="well">
						<form method="POST">
							<p>
								Do you want to shutdown now?
								<button type="submit" class="btn btn-primary <?php echo $shutdown ? 'disabled' : ''; ?>" name="shutdown">Yes</button>
								<a href="/recovery/index.php" class="btn btn-primary <?php echo $shutdown ? 'disabled' : ''; ?>">No</a>
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
