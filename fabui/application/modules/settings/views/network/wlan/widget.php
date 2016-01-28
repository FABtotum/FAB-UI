<?php if($info['ip_address']!='' && $info['ssid'] != ''): ?>
	
<div class="row">
	<div class="col-sm-12 col-lg-12 col-sx-12">
		<table class="table ">
			<tbody>
				<tr>
					<td style="border:0px;" width="200px">Connected to</td>
					<td style="border:0px;"><strong><?php echo $info['ssid']; ?></strong></td>
				</tr>
				<tr>
					<td >IP Address</td>
					<td><a href="http://<?php echo $info['ip_address']; ?>" target="_blank"><?php echo $info['ip_address']; ?></a></td>
				</tr>
				<tr>
					<td>MAC Address</td>
					<td><strong><?php echo $info['mac_address']; ?></strong></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>	
<?php else: ?>
<div class="row">
	<div class="col-sm-12">
		<h4><span class="fa-stack fa-lg">
			  <i class="fa fa-wifi fa-stack-1x"></i>
			  <i class="fa fa-ban fa-stack-2x text-danger"></i>
			</span> No wifi set. Please connect to a valid wifi network</h4>
	</div>
</div>
<?php endif; ?>

<div class="row margin-top-10">
	<div class="col-sm-12">
		<button class="btn btn-primary wifi-buttons" id="scan"><i class="fa fa-search"></i> Scan for networks</button>
		<button class="btn btn-success wifi-buttons" id="hidden" style="margin-left:5px;"><i class="fa fa-user-secret"></i> Connect to hidden WiFi</button>
		<div class="nets-table margin-top-10"></div> 
	</div>
</div>
<form action="<?php echo site_url('settings/network/wlan'); ?>" method="POST" id="connect-form">
	<input type="hidden" id="essid" name="essid" value="" />
	<input type="hidden" id="password" name="password" value="" />
	<input type="hidden" id="type" name="type" value="" />
	<input type="hidden" id="response" name="response" value="">
	<input type="hidden" id="action" name="action" value="">
</form>