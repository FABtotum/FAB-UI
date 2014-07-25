<div class="step-pane <?php echo  ($_task && $_task_attributes->step == 4) || ($_task &&  $_task_attributes->step == 0) ? 'active': '' ?> " id="step4">
    <!-- FIRST ROW -->
    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-primary">Scanning <a href="javascript:void(0);" rel="popover" data-placement="right" data-original-title="Info" data-content=""><span class="badge bg-color-blue">i</span></a> <a id="stop-button" class="btn btn-default txt-color-red controls <?php echo $_task ? '' : 'disabled' ?> stop pull-right" href="#"><i class="fa fa-stop fa-lg"></i></a></h2>
        </div>
    </div>
    <!-- END FIRST ROW -->
    
    <!-- TIMERS -->
    <div class="row">
        <div class="col-sm-4">
            <div class="well well-sm text-center">
                <p>Elapsed Time</p>
                <h2 class="elapsed-time">00:00:00</h2>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="well well-sm text-center">
                <p>Estimated Time</p>
                <h2 class="estimated-time"> - </h2>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="well well-sm text-center">
                <p>Estimated Time left</p>
                <h2 class="estimated-time-left"> - </h2>
            </div>
        </div>
    </div>
    <!-- END TIMERS -->
    
    <!-- PROGRESS SCAN -->
    <div class="row">
        <div class="col-sm-12">
            <div class="well text-center">
                <p>Scan <span class="progress-status"><?php echo  $_task  ? number_format($_scan_monitor->scan->stats->percent, 2, ',', ' ').'%' : '0%' ?></span></p>
                <div class="bar-holder">
                    <div class="progress  progress-striped active">
				        <div id="lines-progress" class="progress-bar bg-color-blue" aria-valuetransitiongoal="0" aria-valuenow="0" style="width: <?php echo  $_task  ? number_format($_scan_monitor->scan->stats->percent, 2, '.', ' ').'%' : '0%' ?>;"></div>
				    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PROGRESS SCAN -->
    
    <!-- PROGRESS PPROCESS -->
    <div class="row" id="pprocess-progress-container">
        <div class="col-sm-12">
            <div class="well text-center">
                <p>Post-processing <span class="pprocess-progress-status"><?php echo  $_task  ? number_format($_pprocess_monitor->post_processing->stats->percent, 2, ',', ' ').'%' : '0%' ?></span></p>
                <div class="bar-holder">
                    <div class="progress  progress-striped active">
				        <div id="pprocess-lines-progress" class="progress-bar bg-color-redLight" aria-valuetransitiongoal="0" aria-valuenow="0" style="width: <?php echo  $_task  ? number_format($_pprocess_monitor->post_processing->stats->percent, 2, '.', ' ').'%' : '0%' ?>;"></div>
				    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PROGRESS PPROCESS -->
    
    <div class="row">
        
        <div class="col-sm-6" id="images-container">
            <legend>Images <i class="fa fa-1x  fa-angle-double-down text-muted pull-right"></i></legend>
            <div class="well">
                <div class="laser"></div>
            </div>
        
        </div>
        
        <div class="col-sm-6" id="console-container">
            <legend>Console <i class="fa fa-1x  fa-angle-double-down text-muted pull-right"></i></legend>
            <div class="well"></div>
        </div>
        
    </div>
    
    <!--
	<div class="row">
		<div class="col-sm-8" id="status-container">
			<h2 class="text-primary">Scanning <a id="stop-button" class="btn btn-default txt-color-blue controls <?php echo $_task ? '' : 'disabled' ?> stop pull-right" href="#"><i class="fa fa-power-off fa-lg"></i></a></h2>
			<div class="well well-light">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Mode <span
							class="pull-right scan-mode-label"><?php echo  $_task  ? $_task_attributes->mode_name : '' ?>
						</span> </span>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Quality <span
							class="pull-right scan-quality-label"><?php echo  $_task  ? $_task_attributes->quality_name : '' ?>
						</span> </span>
					</div>
				</div>


				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Slices <span
							class="pull-right slices-label"><?php echo  $_task  ? $_task_attributes->slices : '' ?>
						</span> </span>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> ISO <span
							class="pull-right iso-label"><?php echo  $_task  ? $_task_attributes->iso : '' ?>
						</span> </span>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Image resolution
							<span class="pull-right img-resolution-label"><?php echo  $_task  ? $_task_attributes->width.' X '.$_task_attributes->height : '' ?>
						</span> </span>
					</div>
				</div>

                
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Elapsed time <span
							class="pull-right elapsed-time">00:00:00</span> </span>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Estimated time <span
							class="pull-right estimated-time">00:00:00</span> </span>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						<span class="text"><i class="fa fa-clock-o "></i> Estimated time
							left <span class="pull-right estimated-time-left">00:00:00</span>
						</span>
					</div>
				</div>
                
                
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">
						
						<span class="text"> Scan <span class="pull-right progress-status"><?php echo  $_task  ? number_format($_scan_monitor->scan->stats->percent, 2, ',', ' ').'%' : '0%' ?>
						</span>
						</span>

						<div class="progress progress-sm progress-striped active">
							<div id="lines-progress" class="progress-bar bg-color-blue"
								aria-valuetransitiongoal="0" aria-valuenow="0"
								style="width: <?php echo  $_task  ? number_format($_scan_monitor->scan->stats->percent, 2, '.', ' ').'%' : '0%' ?>;"></div>
						</div>

					</div>
				</div>
                
				<div class="row" id="pprocess-progress-container">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
						style="margin-bottom: 10px;">

						<span class="text"> Post-processing <span
							class="pull-right pprocess-progress-status"><?php echo  $_task  ? number_format($_pprocess_monitor->post_processing->stats->percent, 2, ',', ' ').'%' : '0%' ?>
						</span>
						</span>

						<div class="progress progress-sm progress-striped active">
							<div id="pprocess-lines-progress" class="progress-bar bg-color-redLight"
								aria-valuetransitiongoal="0" aria-valuenow="0"
								style="width: <?php echo  $_task  ? number_format($_pprocess_monitor->post_processing->stats->percent, 2, '.', ' ').'%' : '0%' ?>;"></div>
						</div>

					</div>
				</div>
               



			</div>

		</div>

       
		<div class="col-sm-4" id="images-container">
			<h2 class="text-primary">Images</h2>
			<div class="well well-light">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="laser">
							
						</div>
					</div>
				</div>
			</div>
		</div>
        
        <div class="col-sm-4" id="probing-trace-container">
			<h2 class="text-primary">Trace</h2>
			<div class="well well-light">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						
					</div>
				</div>
			</div>
		</div>
        

	</div>
     -->

</div>


