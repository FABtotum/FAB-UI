<div class="step-pane <?php echo  ($_task && $_task_attributes['step'] == 4) || ($_task &&  $_task_attributes['step'] == 0) ? 'active': '' ?> " id="step4">
    <!-- FIRST ROW -->
    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-primary">Scan in progress <a id="stop-button" class="btn btn-default txt-color-red controls <?php echo $_task ? '' : 'disabled' ?> stop pull-right" href="#"><i class="fa fa-stop fa-lg"></i></a></h2>
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
                <p>Scan <span class="progress-status"><?php echo  $_task  ? number_format($_scan_monitor['scan']['stats']['percent'], 2, ',', ' ').'%' : '0%' ?></span></p>
                <div class="bar-holder">
                    <div class="progress  progress-striped active">
				        <div id="lines-progress" class="progress-bar bg-color-blue" aria-valuetransitiongoal="0" aria-valuenow="0" style="width: <?php echo  $_task  ? number_format($_scan_monitor['scan']['stats']['percent'], 2, '.', ' ').'%' : '0%' ?>;"></div>
				    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PROGRESS SCAN -->
    
    <!-- PROGRESS PPROCESS -->
    <?php //if($_task && is_array($_pprocess_monitor)): ?>
    <div class="row" id="pprocess-progress-container">
        <div class="col-sm-12">
            <div class="well text-center">
                <p>Post-processing <span class="pprocess-progress-status"><?php echo  $_task && isset($_pprocess_monitor['post_processing']['stats']['percent']) ? number_format($_pprocess_monitor['post_processing']['stats']['percent'], 2, ',', ' ').'%' : '0%' ?></span></p>
                <div class="bar-holder">
                    <div class="progress  progress-striped active">
				        <div id="pprocess-lines-progress" class="progress-bar bg-color-redLight" aria-valuetransitiongoal="0" aria-valuenow="0" style="width: <?php echo  $_task && isset($_pprocess_monitor['post_processing']['stats']['percent'])  ? number_format($_pprocess_monitor['post_processing']['stats']['percent'], 2, '.', ' ').'%' : '0%' ?>;"></div>
				    </div>
                </div>
            </div>
        </div>
    </div>
    <?php //endif; ?>
    <!-- END PROGRESS PPROCESS -->
    
    <div class="row">
        
        <!--
        <div class="col-sm-6" id="images-container">
            <legend>Images <i class="fa fa-1x  fa-angle-double-down text-muted pull-right"></i></legend>
            <div class="well">
                <div class="laser"></div>
            </div>
        
        </div>
        -->
        
        <!-- 
        <div class="col-sm-12" id="console-container">
            <legend>Console <i class="fa fa-1x  fa-angle-double-down text-muted pull-right"></i></legend>
            <div class="well"></div>
        </div>
        -->
    </div>
    

</div>


