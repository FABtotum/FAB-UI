<div class="row">
	<div class="col-sm-12">
		<div class="text-center error-box tada animated">
			<h2 class="font-xl"><strong><i class="fa fa-fw fa-warning fa-lg text-warning"></i> Update server <u>unreachable </u></strong></h2>
				
			<?php if(!$internet_available): ?>
				<p class="lead">
					Check your <strong><a href="<?php echo site_url('settings/network/wlan'); ?>">internet connectivity </a></strong> and try again
				</p>
			<?php elseif($remote_version == false): ?>
				
				<p class="lead">
					Update server under maintenace<br> Please check back shortly
				</p>
				<i class="fa fa-wrench fa-4x "></i>
			<?php endif; ?>
		</div>
	</div>
</div>