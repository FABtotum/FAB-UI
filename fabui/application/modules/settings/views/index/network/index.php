<div class="tab-pane animate fadeIn fade in active" id="tab_network">

    
    <div class="row margin-top-10">
    	<div class="col-sm-12">
    		<p class="pull-right">
    			<?php if(isset($lan['name'])): ?>
   						<i class="fa fa-sitemap <?php echo $_SERVER['SERVER_ADDR'] == $lan['ip'] ? ' txt-color-green' : ''; ?>"></i>&nbsp;
   						Lan: <a target="_blank" href="http://<?php echo $lan['ip'] ?>" id="eth-ip"><?php echo $lan['ip'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
   					<?php endif; ?>
   					<?php if(isset($con_wlan['name'])):?>
   						<i class="fa  fa-wifi <?php echo $_SERVER['SERVER_ADDR'] == $con_wlan['ip'] ? ' txt-color-green' : ''; ?>"></i>&nbsp;
   						Wifi: <a target="_blank" href="http://<?php echo $con_wlan['ip']; ?>" id="wifi-ip"><?php echo $con_wlan['ip'] ?></a>
   						<?php echo isset($networkConfiguration['wifi']['ssid']) &&  $networkConfiguration['wifi']['ssid'] != '' ? '&nbsp;- connected to: <strong id="wifi-ssid-label">'.$networkConfiguration['wifi']['ssid'].'</strong>' : '' ?>
   					<?php endif; ?>
    		</p>
    	</div>
    </div>
    
    <div class="row margin-top-10">
        <div class="col-md-12">
        	
        	<div class="tabs-left">
        	
        		<ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
					<li>
						<a href="#eth" data-toggle="tab"> Ethernet </a>
					</li>
					<li  class="active">
						<a href="#wifi" data-toggle="tab"> Wifi</a>
					</li>
					
				</ul>

        		<div class="tab-content">
        		
        		<div class="tab-pane " id="eth">
        			
        			<div class="well no-border">
        				
        				<div id="eth-form" class="form-inline">
        					<fieldset>
        						<legend><i class="fa fa-sitemap"></i> &nbsp; <label class="text-primary">Set ethernet static IP address</label></legend>
        						
        						<div class="form-group">
        							<label class="font-md" style="margin-top:13px;">169.254.1.</label>  
        						</div>
        						<div class="form-group">
									<input min="2" max="255" type="number" class="form-control font-md" id="eth-endnumber" placeholder="" value="<?php echo $ethEndIp ?>">
								</div>
        					</fieldset>
        					<div class="form-actions">
								<button type="button" class="btn btn-primary" id="eth-save-button">
									<i class="fa fa-save"></i> Save
								</button>
							</div>
        				</div>
        			</div>
        			
        			
        			
        		</div>
        		
        		
				<div class="tab-pane active" id="wifi">
		            <div class="well no-border">
		                <div id="advanced-form" class="form-horizontal" action="<?php echo site_url('settings/network') ?>" method="post">
		                    <fieldset>
		                    	<legend><i class="fa fa-wifi"> </i> &nbsp; <label class="text-primary">Available networks in range</label></legend>
		                        
		                        
		                        <?php if(sizeof($wlan)> 0): ?>
		                        	
		                        	<style>
		                        		.table tbody tr{
		                        			cursor: pointer;
		                        		}
		                        	</style>
		                        	<table class="table table-bordered table-striped">
		                        		<thead>
		                        			<tr>
		                        				
		                        				<th>SSID</th>
		                        				<th class="hidden-xs">Strength Signal</th>
		                        			</tr>
		                        		</thead>
		                        		
		                        		<tbody>
		                        			<?php $count = 1; ?>
		                        			<?php foreach($wlan as $wl): ?>
		                        				
		                        				<tr data-count="<?php echo $count; ?>" data-address="<?php echo $wl['address']; ?>"  data-password="<?php echo $wl['encryption key'] == 'on' ? 'true' : 'false'; ?>">
		                        				
		                        					<td width="30%">
		                        						<?php if($wl['encryption key'] == 'on' ): ?>
		                        							<a href="javascript:void(0);"><i class="fa fa-chevron-right  arrow"></i></a>&nbsp;
		                        							<i class="fa fa-lock"></i>
		                        						<?php endif; ?>
		                        					
		                        						&nbsp;<strong id="net_<?php echo $count?>"><?php echo $wl['essid'] ?></strong>
		                        						<?php if($networkConfiguration['wifi']['ssid'] == $wl['essid']): ?>
		                        						
		                        							<i class="fa fa-check pull-right actual-wifi"></i>
		                        						
		                        						<?php endif; ?>
		                        					</td>
		                        					<td class="hidden-xs">
		                        						<div class="progress">
		                        							<div class="progress-bar  bg-color-blue" aria-valuetransitiongoal="<?php echo $wl['signal_level'] ?>"></div>
		                        						</div>
		                        					</td>
		                        				</tr>
		                        				<?php $count++; ?>
		                        			<?php endforeach; ?>
		                        		</tbody>
		                        		
		                        	</table>
		                        	
		 
		                        <?php endif; ?>
		                        
		                    </fieldset>
		                    
			                <div class="form-actions">
								<button type="button" class="btn btn-primary" id="wifi-save-button">
									<i class="fa fa-save"></i> Save
								</button>
							</div>
		                    <input id="net_password" name="net_password" value="" type="hidden">
		                    
		               </div>
		                
		            </div>
                </div>
            </div>
            
            </div>
        </div>
    </div>
</div> 