<div class="tab-pane animate fadeIn fade in active" id="tab_network">
    
    <div class="row margin-top-10">
    	
    	<div class="col-sm-12">
    		
    		<div class="well-sm no-border pull-right">
    			<h4><i class="fa fa-sitemap <?php echo $_SERVER['SERVER_ADDR'] == $lan['ip'] ? ' txt-color-green' : ''; ?>"></i> 
    				Lan: <a target="_blank" href="http://<?php echo $lan['ip'] ?>"><?php echo $lan['ip'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-signal <?php echo $_SERVER['SERVER_ADDR'] == $con_wlan['ip'] ? ' txt-color-green' : ''; ?>"></i>&nbsp;Wifi: <a target="_blank" href="http://<?php echo $con_wlan['ip']; ?>" id="wifi-ip"><?php echo $con_wlan['ip'] ?></a></h4>
    		</div>
    		
    	</div>
    	
    </div>
    
    <div class="row margin-top-10">
        <div class="col-md-12">
            <div class="well no-border">
                <form id="advanced-form" class="form-horizontal" action="<?php echo site_url('settings/network') ?>" method="post">
                    <!--
                    <fieldset>
                        <legend><i class="fa fa-sitemap"></i> &nbsp;Lan</legend>
                        
                        <?php if(sizeof($lan) > 0): ?>
                        	
                        	
                        	<table class="table table-striped table-forum">
                        		
                        		<tbody>
                        			
                        			<tr>
                        				<td style="width: 5%;">
                        					<!--
                        					<label class="radio">
												<input type="radio" checked="true" name="net" data-type="lan" value="lan-<?php echo $lan['name'];?>" /><i></i>
											</label>
											
                        				</td>
                        				<td class="text-left">
                        					
                        					<h4>
												<a class="" href="javascript:void(0)"> <?php echo $lan['name'].' '.$lan['type'] ?>
					                            
												</a>
												<small>
													<?php echo $lan[ 'ip'] ?>
												</small>
					                           
											</h4>
                        					
                        				</td>
                        			</tr>
                        		</tbody>
                        		
                        		
                        		
                        	</table>
                        	
                        	
                        	
                        	
                        <?php endif; ?>
                        
                        
                    </fieldset>
                    -->
                    <fieldset>
                        <h4 style="margin-bottom: 10px;"><i class="fa fa-rss"></i> &nbsp; Available networks in range</h4>
                        
                        <?php if(sizeof($wlan)> 0): ?>
                        	
                        	
                        	<table class="table table-striped table-forum">
                        		
                        		<tbody>
                        	
                        	
                        	
	                        	<?php foreach($wlan as $wl): ?>
	                        		
	                        		<tr class="<?php echo $wifi_saved['ssid'] == $wl['essid'] ? '' : '' ?>"> 
										<td style="width:  1%;">
											<label class="radio">
												<input type="radio" data-password="<?php echo $wl['encryption key'] == 'on' ? 'true' : 'false'; ?>" name="net" data-type="wlan" value="wifi-<?php echo $wl['essid'] ?>" />
												<i></i>
											</label>
										</td>
										<td style="width:  30%;" class="text-left">
											<h4>
												<a data-password="<?php echo $wl['encryption key'] == 'on'? 1 :0;  ?>" class="net" href="javascript:void(0)"> <?php echo $wl['essid'] ?><?php if($wl['encryption key'] == 'on') : ?> <i class="fa fa-lock"></i> <?php endif; ?></a>
												<small></small>
											</h4>
										</td>
										<td style="width:  65%;" class="hidden-xs">
				                            
											<div class="progress progress-striped active">
												<div class="progress-bar bg-color-blue" role="progressbar" style="width: <?php echo isset($wl['signal_level']) ? $wl['signal_level'] : 0; ?>%"><?php echo isset($wl['signal_level']) ? $wl['signal_level'] : 0;  ?>%</div>
											</div>
										</td>
									</tr>
	                        		
	                        		
	                        		
	                        		
	                        	<?php endforeach; ?>
	                        	
	                        	</tbody>
                        	
                        	
                        	</table>
                        	
                        	
                        <?php endif; ?>
                        
                    </fieldset>
                    
                    <div class="form-actions">
						<button type="button" class="btn btn-primary" id="save-button">
							<i class="fa fa-save"></i> Save
						</button>
					</div>
                    
                    
                    <input id="net_password" name="net_password" value="" type="hidden">
                    
                </form>
            </div>
        </div>
    </div>
</div>