<div class="row create-monitor" style="display: none;">

    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Elapsed Time</p>
            <h2 class="elapsed-time">00:00:00</h2>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Estimated Time</p>
            <h2 class="estimated-time"> - </h2>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Estimated Time left</p>
            <h2 class="estimated-time-left"> - </h2>
        </div>
    </div>

</div>


<div class="row create-monitor" style="display: none;">

    <div class="col-md-12">
        <div class="well text-center">
            <p>Progress <span id="label-progress"><?php echo $_progress_percent != '' ? '( '.$_progress_percent.'% )': ''; ?></span></p>
            <div class="bar-holder">
                <div class="progress">
    				<div id="lines-progress" class="progress-bar bg-color-blue" aria-valuetransitiongoal="0" aria-valuenow="0" style="width:<?php echo str_replace(',', '.', $_progress_percent).'%'; ?>;">
                    <?php echo $_progress_percent != '' ? $_progress_percent.'%' : ''; ?></div>
                </div>
            </div>
            
        </div>
    </div>

</div>


<div class="row create-monitor" style="display: none;">

    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Velocity: <label id="label-velocity">(<?php echo $_velocity ?>%)</label></p>
            <div id="velocity" data-action="velocity" class="sliders margin-bottom-10"></div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Extruder temp: <label id="label-temp1"><?php echo $_temperature; ?></label> °C <i class="fab-lg fab-fw icon-fab-term fab-2x"></i></p>
            <div id="temp1" data-action="temp1" class="sliders margin-bottom-10"></div>
        </div>
    </div>
    
    
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Bed temp: <label id="label-temp2"><?php echo $bed_temp; ?></label> °C <i class="fab-lg fab-fw icon-fab-term fab-2x"></i></p>
            <div id="temp2" data-action="temp2" class="sliders margin-bottom-10"></div>
        </div>
    </div>


</div>



<div class="row">
    <div class="col-sm-12">
        <legend id="details" style="cursor: pointer;">Details
            <i class="fa fa-1x  fa-angle-double-down text-muted pull-right"></i>
        </legend>
    </div>
</div>
<div class="row details-container" style="display: none;">
     <div class="col-md-12">
        <div id="ace-editor" class="well" style="height: 250px;">
        </div>
     </div>
</div>


