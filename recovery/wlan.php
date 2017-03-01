<?php

require 'utilities.php';

$wlan_list = scan_wlan();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$address = $_POST['address'];
	$password = $_POST['password'];

	$wifi_selected = '';

	foreach ($wlan_list as $wl) {
		if ($wl['address'] == $address) {
			$wifi_selected = $wl;
			break;
		}
	}

	if (setWifi($wifi_selected['essid'], $password, $wifi_selected['type'])) {

		$alert['type'] = 'success';
		$alert['messsage'] = 'New newtwork configuration saved';

	} else {
		$alert['type'] = 'danger';
		$alert['messsage'] = 'Oops an error occured, check the password and try again';
	}

}

$wlan = wlan();

$wifi = true;

if (!isset($wlan['ip'])) {
	$alert['type'] = 'warning';
	$alert['messsage'] = 'No wifi properly configured';
	$wifi = false;
}

$networkConfiguration = networkConfiguration();

$title = 'Wlan Configuration';
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
					<li><a href="/recovery/index.php">Recovery</a></li>
					<li>Wifi Configuration</li>
				</ol>
			</div>
			<div id="content">
				<div class="row">
					<div class="col-sm-12">
						<div class="well">
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="javascript:void(0);" data-toggle="tab">Wifi Configuration</a>
								</li>
								<li>
									<a href="/recovery/eth.php">Ethernet Configuration</a>
								</li>
							</ul>	
							<div class="tab-content padding-10">
								<div class="tab-pane active">
								<?php if(isset($alert)): ?>
									
									<div class="row">
										<div class="col-sm-12">
											<div class="alert alert-<?php echo $alert['type'] ?> fade in">
												<?php echo $alert['messsage'] ?>
											</div>
										</div>
									</div>
									
								<?php endif; ?>
								
								
								<?php if($wifi): ?>
									
									<div class="row">
										<div class="col-sm-12">
											<p>Actual configuration: <strong><?php echo $networkConfiguration['wifi']['ssid']; ?></strong> - IP: <?php echo $wlan['ip'] ?></p>
										</div>
									</div>
									
								<?php endif; ?>
								
								<div class="row">
									<div class="col-sm-12">
										<div class="well">
											<h2></h2>
											
											<form class="form-horizontal" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label"><i class="fa fa-wifi"></i> Available networks in range</label>
														<div class="col-md-10">
															<?php foreach($wlan_list as $wl): ?>
																<div class="radio">
																	<label>
																		<input data-encryption="<?php echo $wl['encryption key']; ?>" name="address" type="radio" class="radiobox" value="<?php echo $wl['address'] ?>" <?php echo $networkConfiguration['wifi']['ssid'] == $wl['essid'] && $wifi ? 'checked="checked"' : '' ?> >
																		<span><?php echo $wl['encryption key'] == 'on' ? '<i class="fa fa-lock"></i>' : ''; ?> <?php echo $wl['essid']; ?> - Strength: <?php echo $wl['signal_level'] ?>/100</span>
																	</label>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
												</fieldset>
												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label"><i class="fa fa-lock"></i> Password</label>
														<div class="col-md-10">
															<input class="form-control" type="password" name="password" id="password">
														</div>
													</div>
												</fieldset>
												
												<div class="form-actions">
													<div class="row">
														<div class="col-md-12">
															<button class="btn btn-primary" type="button" id="save"><i class="fa fa-save"></i> Save configuration</button>
														</div>
													</div>
												</div>
												
											</form>
											
										</div>
									</div>
								</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
		
		<?php
		include 'footer.php';
 ?>
		
		<script type="text/javascript">
			var fabui = false;

			$(function() {

				$("#save").on('click', function() {

					var password = $.trim($("#password").val());
					var encryption = $(".radiobox:checked").attr("data-encryption") == 'on' ? true : false;

					if ($(".radiobox:checked").length == 0) {
						alert('Please select a wifi connection');
						return false;
					}

					if (encryption && (password == '')) {

						alert('please insert password');
						return false;

					}

					openWait('Saving network configuration');
					$(".form-horizontal").trigger('submit');

				});

			});

		</script>

	</body>
</html>

