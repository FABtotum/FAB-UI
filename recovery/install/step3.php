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
    
    
    <!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-warning"></i> Warning</h4>
				</div>
				<div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="text-center">This file can not be printed just as it is, it must be processed before</h5>
                            
                            <h5 class="text-center">Do you want to process it?</h5>
                        </div>
                    </div>
                   
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cancel
					</button>
					<button type="button" class="btn btn-primary" id="process-button">
						Process
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

    
    
    
    
</div>