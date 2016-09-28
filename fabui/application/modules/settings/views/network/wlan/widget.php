<?php if($info['ip_address']!='' && $info['ssid'] != ''): ?>
<style>.net-details{display:none;}</style>	
<div class="row padding-10">
	<div class="col-sm-12 col-lg-12 col-sx-12 net-details">
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

<?php endif; ?>

<div class="row">
	
	<div class="col-sm-12 table-container">
		<table class="table table-striped table-forum">
		
		<tbody>
			<?php foreach($networks as $net): ?>
				<?php if($net['essid'] != ''): ?>
				<?php $action =  $net['address'] == $info['ap_mac_address'] ? 'disconnect' : 'connect'; $protected = $net['encryption key'] == 'on' ? true : false; ?>
					<tr>
						<td class="text-center" style="width: 40px;"><i class=" icon-communication-035 fa-2x text-muted"></i></td>
						<td style="width: 200px">
							<h4><a href="javascript:void(0);"> <?php echo $net['essid']; ?> <?php if($action == 'disconnect'): ?> <i class="fa fa-check pull-right"></i> <?php endif; ?></a>
								<small><?php echo $protected ? 'Protected ('.$net['type'].')' : 'Open'; ?> <i class="fa fa-<?php echo $protected ? 'lock' :'unlock'  ?>"></i></small>
							</h4>
						</td>
						<td class="hidden-xs">
							<div class="progress progress-striped active">
								<div class="progress-bar  bg-color-blue" data-transitiongoal="<?php echo decodeWifiSignal($net['signal_level']) ?>"></div>
							</div>
						</td>
						<td style="width: 100px" class="text-right">
							<button data-type="<?php echo $net['type']; ?>" data-protected="<?php echo $net['encryption key'];?>" data-ssid="<?php echo $net['essid']; ?>" data-action="<?php echo $action; ?>" class="btn btn-info btn-block <?php echo $action; ?>"><?php echo ucfirst($action); ?></button>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
</div>

<div class="widget-footer">
	<button class="btn btn-primary wifi-buttons" id="scan"><i class="fa fa-search"></i> <span class="hidden-xs">Scan</span></button>
	<button class="btn btn-success wifi-buttons" id="hidden" style="margin-left:5px;"><i class="fa fa-user-secret"></i> <span class="hidden-xs">Connect to hidden WiFi</span></button>
</div>
<!--
<div class="row margin-top-10">
	<div class="col-sm-12">
		<button class="btn btn-primary wifi-buttons" id="scan"><i class="fa fa-search"></i> <span class="hidden-xs">Scan for networks</span></button>
		<button class="btn btn-success wifi-buttons" id="hidden" style="margin-left:5px;"><i class="fa fa-user-secret"></i> <span class="hidden-xs">Connect to hidden WiFi</span></button>
		<div class="nets-table margin-top-10"></div> 
	</div>
</div>
-->
<form action="<?php echo site_url('settings/network/wlan'); ?>" method="POST" id="connect-form">
	<input type="hidden" id="essid" name="essid" value="" />
	<input type="hidden" id="password" name="password" value="" />
	<input type="hidden" id="type" name="type" value="" />
	<input type="hidden" id="response" name="response" value="">
	<input type="hidden" id="action" name="action" value="">
</form>