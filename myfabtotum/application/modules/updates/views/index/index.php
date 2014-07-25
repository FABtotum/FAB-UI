<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-refresh">
			</i>
			Updates
		</h1>
	</div>
</div>

<div class="row">

    <div class="col-sm-12">
        <div class="well ">
        
            <?php if($internet): ?>
                
                
                <table class="table table-striped table-forum">
                
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
                            <td>
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
                            <td>
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
            
            <!-- NO UPDATE OR NO INTERNET CONNECTION -->
            
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