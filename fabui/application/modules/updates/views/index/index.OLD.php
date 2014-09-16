<div class="row">
	<div class="col-xs-12">
		<h1 class="page-title txt-color-blueDark"> <i class="fa fa-fw fa-refresh"> </i> Update Center </h1>
	</div>
	
</div>

<div class="row">

    <div class="col-sm-12">
        <div class="well ">
        	
        	       
            <?php if($internet): ?>
            	
            	
            	
            	<?php if(!$no_update): ?>
                
                
                <table class="table table-striped table-forum">
                	
                	<thead>
                		
                		<tr>
                			<th colspan="4"></th>
                		</tr>
                		
                	</thead>
                
                    <tbody>
                        
                        <?php if($fabui): ?>
                        <tr class="<?php echo $running == true && $update_type == 'fabui' ? 'warning' : ''; ?>">
                            <td class="text-center" style="width: 40px;"><i class="fa fa-tablet fa-2x text-muted"></i></td>
                            <td>
                                <h4>
                                    <a href="javascript:void(0);">FAB UI</a>
                                    <small>Current version: <?php echo $fabui_local; ?></small>
                                </h4>
                            </td>
                            <td class="hidden-xs">
                                <h5>A new update for FAB UI is avaiable</h5>
                                <small>new version is <?php echo $fabui_remote; ?> | <a href="javascript:void(0)">see details</a></small>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-default btn-sm download download-myfab" download-item="fabui">
                                    <i class="fa fa-refresh"></i> Update
                                </a>
                                 <a class="btn btn-default btn-sm delete" style="display: <?php echo $running == true && $update_type == 'fabui' ? '' : 'none' ?>;">
                                   <i class="fa fa-times"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if($marlin): ?>
                        <tr class="<?php echo $running == true && $update_type == 'marlin' ? 'warning' : ''; ?>">
                            <td class="text-center" style="width: 40px;"><i class="fa fa-shield  fa-2x text-muted"></i></td>
                            <td>
                                <h4>
                                    <a href="javascript:void(0);">Marlin Firmware</a>
                                    <small>Current version: <?php echo $marlin_local; ?></small>
                                </h4>
                            </td>
                            <td class="hidden-xs">
                                <h5>A new update for Marlin Firmware is avaiable</h5>
                                <small>new version is <?php echo $marlin_remote; ?> | <a href="javascript:void(0)">see details</a></small>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-default btn-sm download download-marlin" download-item="marlin">
                                    <i class="fa fa-refresh"></i> Update
                                </a>
                                 <a class="btn btn-default btn-sm delete" style="display: <?php echo $running == true && $update_type == 'marlin' ? '' : 'none' ?>">
                                   <i class="fa fa-times"></i>
                                </a>
                            </td>
                            
                        </tr>
                        <?php endif; ?>
                        
                        
                    
                    </tbody>
                    
                </table>
                <?php else: ?>
                	
                	
                	
                <?php endif; ?>
                
                

            <?php else: ?>
            
            <!-- NO UPDATE OR NO INTERNET CONNECTION -->
            	
            	<h2 class="text-center"><i class="fa fa-fw fa-warning fa-2x txt-color-red"></i> <i class="fa fa-fw fa-globe fa-2x txt-color-blue"></i></h2>
            	<h2 class="text-center">No internet connectivity detected </h2>
            	<h2 class="text-center">please <a href="<?php echo site_url("settings/network") ?>">reconnect</a> and try again</h2>
            
            <?php endif; ?>
            
        </div>
    </div>
</div>


<?php if($internet): ?>


    <div class="row progress-container" style="display: none;">
            <div class="col-sm-12">
                <div class="well">
                    <p>
						<span id="status"></span> <span id="velocita"></span> <span id="percentuale" class="pull-right"></span>
					</p>
                    <div class="progress">
						<div id="progress-download" class="progress-bar bg-color-blue"
							role="progressbar" style="width: 0%"></div>
					</div>
                </div>
            </div>
        </div>


<?php endif; ?>