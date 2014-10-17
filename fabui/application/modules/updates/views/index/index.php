<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-fw fa-refresh"> </i> Update Center </h1>
	</div>

	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> FAB UI beta <span class="txt-color-blue"><i class="fa fa-mobile"></i>&nbsp;v&nbsp;<?php echo $fabui_local ?></span></h5>
				
			</li>
			<li class="sparks-info">
				<h5> FABlin FW<span class="txt-color-purple"><i class="fa fa-shield"></i>&nbsp;v&nbsp;<?php echo $marlin_local ?></span></h5>
			</li>
		</ul>
	</div>
</div>

<?php if(!$internet) : ?>
	
<div class="alert alert-warning fade in">
	<button class="close" data-dismiss="alert"></button>
	<i class="fa-fw fa fa-warning"></i>
	<strong>Warning </strong> No internet connectivity detected. Please <a href="<?php echo site_url("settings/network") ?>">reconnect</a> and try again
</div>

<?php endif; ?>

<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<table class="table table-striped table-forum">
				<thead>	
                	<tr>
                		<th colspan="4"></th>
                	</tr>	
                </thead>
                <tbody>
                	<!-- FAB UI -->
                	<tr class="<?php echo $running == true && $update_type == 'fabui' || $fabui ? 'warning' : ''; ?>">
                		<td class="text-center" style="width: 40px;"><i class="fa fa-tablet txt-color-blue fa-2x text-muted"></i></td>
                		<td>
	                        <h4>
	                            <a href="javascript:void(0);">FAB UI <i class="font-xs txt-color-orangeDark">beta</i></a>
	                            <small>Installed version: <i><?php echo $fabui_local; ?></i></small>
	                        </h4>
	                    </td>
	                    <td>
	                    	<?php if($internet): ?>
	                    		<h4><?php echo $fabui ? '<a> A new update is avaiable.<strong class="txt-color-red"> Get new version ' .$fabui_remote.' now!</strong></a> ' : 'Your FAB UI is updated to the latest version' ?>
	                    			
	                    			<?php if($fabui): ?>
	                    			<small><a data-toggle="modal" style="cursor:pointer" data-target="#fabui_changelog">See details</a></small>
	                    			<?php endif; ?>
	                    		</h4>
	                    		
	                    	<?php endif; ?>
	                    </td>
	                    <td class="text-right">
	                    	<?php if($internet): ?>
                            <a class="btn btn-default btn-sm download download-myfab" download-item="fabui">
                               <i class="fa fa-refresh"></i> Update
                            </a>
                             <a class="btn btn-default btn-sm delete" style="display: <?php echo $running == true && $update_type == 'fabui' ? '' : 'none' ?>;">
                               <i class="fa fa-times"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                	</tr>
                	<!-- END FAB UI -->
                	<!-- MARLIN FW -->
                	
                	<tr class="<?php echo $running == true && $update_type == 'marlin' || $marlin ? 'warning' : ''; ?>">
                		<td class="text-center" style="width: 40px;"><i class="fa fa-shield txt-color-purple  fa-2x text-muted"></i></td>
                		<td>
                            <h4>
                                <a href="javascript:void(0);">FABlin Firmware</a>
                                <small>Installed version: <i><?php echo $marlin_local; ?></i></small>
                            </h4>
                        </td>
                        <td>
                        	<?php if($internet): ?>
                        		<h4><?php echo $marlin ? ' <a>A new update for FABlin Firmware is avaiable.<strong class="txt-color-red"> Get new version '.$marlin_remote.' now!</strong> </a>' : 'Your FABlin Firmware is updated to the latest version' ?>
                        			
                        			<?php if($marlin): ?>
	                    			<small><a data-toggle="modal" style="cursor:pointer" data-target="#fw_changelog">See details</a></small>
	                    			<?php endif; ?>
                        			
                        		</h4>
                        	<?php endif; ?>
                        </td>
                        <td class="text-right">
                        	<?php if($internet): ?>
                            <a class="btn btn-default btn-sm download download-marlin" download-item="marlin">
                                <i class="fa fa-refresh"></i> Update
                            </a>
                             <a class="btn btn-default btn-sm delete" style="display: <?php echo $running == true && $update_type == 'marlin' ? '' : 'none' ?>">
                               <i class="fa fa-times"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                	</tr>
                	
                </tbody>
			</table>
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


<?php if($fabui): ?>
<div class="modal fade" id="fabui_changelog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">FAB UI beta v.<?php echo $fabui_remote ?></h4>
			</div>
			<div class="modal-body">
				<?php echo $fabui_changelog; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					OK
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>



<?php if($marlin): ?>
<div class="modal fade" id="fw_changelog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">FABlin Firmware v.<?php echo $marlin_remote;  ?></h4>
			</div>
			<div class="modal-body">
				<?php echo $fw_changelog; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					OK
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

