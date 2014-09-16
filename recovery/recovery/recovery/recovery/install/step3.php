<?php

/** * UTILITIES */ 
include( "inc/utilities.php"); 
$_wlan_list = scan_wlan(); 
$_lan       = lan();

?>
<div class="tab-pane" id="tab3">
	<br>
	<h3>
		<strong>
			Step 3
		</strong>
		- Setup your network configuration
	</h3>
	<div class="row">
		<table class="table table-striped table-forum smart-form">
			<thead>
				<th colspan="2">
					<i class="fa fa-sitemap text-muted">
					</i>
					LAN Network
				</th>
				<th class="text-right">
					<i class="fa  fa-angle-double-up text-muted">
					</i>
				</th>
				</tr>
			</thead>
			<tbody style="">
				<!-- TR -->
				<tr>
					<td style="width: 20px;">
						<label class="radio">
							<input type="radio" checked="true" name="net" data-type="lan" value="lan-<?php echo $_lan['name'];?>" />
							<i>
							</i>
						</label>
					</td>
					<td class="text-left">
						<h4>
							<a class="net" href="javascript:void(0)"> <?php echo $_lan['name'].' '.$_lan['type'] ?>
                            
							</a>
							<small>
								<?php echo $_lan[ 'ip'] ?>
							</small>
                           
						</h4>
					</td>
					<td>
					</td>
				</tr>
				<!-- end TR -->
			</tbody>
		</table>
	</div>
	<div class="row">
		<table class="table table-striped table-forum smart-form">
			<thead>
				<tr>
					<th colspan="2">
						<i class="fa fa-signal text-muted">
						</i>
						Wi-Fi Network
					</th>
					<th class="text-right">
						<i class="fa  fa-angle-double-down text-muted">
						</i>
					</th>
				</tr>
			</thead>
			<tbody style="display: none;">
				<?php foreach($_wlan_list as $_wlan): ?>
					<!-- TR -->
					<tr>
						<td style="width: 20px;">
							<label class="radio">
								<input type="radio" data-password="<?php echo $_wlan['encryption key'] == 'on' ? 'true' : 'false'; ?>" name="net" data-type="wlan" value="wifi-<?php echo $_wlan['essid'] ?>" />
								<i>
								</i>
							</label>
						</td>
						<td style="width: 250px;" class="text-left">
							<h4>
								<a class="net" href="javascript:void(0)"> <?php echo $_wlan['essid'] ?>
                                 <?php if($_wlan['encryption key'] == 'on') : ?> <i class="fa fa-lock"></i> <?php endif; ?>
								</a>
								<small>
								</small>
							</h4>
						</td>
						<td class="hidden-xs hidden-sm">
                            
							<div class="progress progress-striped active">
								<div class="progress-bar bg-color-blue" role="progressbar" style="width: <?php echo $_wlan['signal_level'] ?>%">
									<?php echo $_wlan[ 'signal_level'] ?>
										%
								</div>
							</div>
						</td>
					</tr>
					<!-- end TR -->
					<?php endforeach; ?>
			</tbody>
		</table>
	</div>
    
    
   	

    
    
    
    
</div>