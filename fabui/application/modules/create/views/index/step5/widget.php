<div class="row create-monitor" style="display: none;">

    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Elapsed Time</p>
            <h4 class="elapsed-time">00:00:00</h4>
        </div>
    </div>
    
    <!--
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Estimated Time</p>
            <h4 class="estimated-time"> Processing.. </h4>
        </div>
    </div>
    -->
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Time left</p>
            <h4 class="estimated-time-left"> Processing.. </h4>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4">
        <div class="well  ">
        	
        	
        		<a rel="tooltip" data-action="zup"   title="Change Z height: + 0.1mm" href="javascript:void(0)" class="btn btn-default controls" ><i class="fa fa-angle-double-down"></i>&nbsp;Z</a>
        		<a rel="tooltip" data-action="zdown" title="Change Z height: - 0.1mm" href="javascript:void(0)" class="btn btn-default controls" style="margin-left:5px;"><i class="fa fa-angle-double-up"></i>&nbsp;Z</a>
        		
        	
        	
            
                 <a rel="tooltip" title="Stop print process" href="javascript:void(0);"  data-action="stop"      class="btn btn-default txt-color-red          <?php echo $_running ? '' : 'disabled' ?> stop pull-right" id="stop-button"> <i title="Stop"   class="fa fa-stop fa-lg"></i> </a>
				 <!--<a title="Pause"  href="javascript:void(0);"  data-action="pause"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Pause" class="fa fa-pause fa-lg"></i> </a>-->
				 <a rel="tooltip" title="Turn On/Off the lights" id="light-switch" href="javascript:void(0);"  data-action="light-on"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Lights" class="fa fa-lightbulb-o fa-lg"></i> </a>
				 <a rel="tooltip" title="Send mail" id="send-mail" href="javascript:void(0);"  data-action="<?php echo $mail == 1 ? 'send-mail-false' : 'send-mail-true' ?>"  class="btn btn-default <?php echo $mail == 1 ? 'txt-color-green' : 'txt-color-red'; ?> controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Lights" class="fa fa-envelope fa-lg"></i> </a>
              
        </div>
    </div>

</div>


<div class="row create-monitor" style="display: none;">

    <div class="col-md-12">
        <div class="well text-center">
            <h2>Progress <span id="label-progress"><?php echo $_progress_percent != '' ? '( '.$_progress_percent.'% )': ''; ?></span></h2>
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

    <div class="<?php echo trim($_file_type) == 'additive' ? 'col-md-4 col-lg-4' : 'col-md-6 col-lg-6' ?> " id="velocity-slider-container">
        <div class="well well-sm text-center">
        	<h4>Speed <i class="fa fa-lg fa-fw fa-flash "></i></h4>
            <h5><span id="label-velocity"><?php echo $_velocity == '' ? 100 : $_velocity ?>%</span> </h5>
            <div id="velocity" data-action="velocity" class="sliders margin-bottom-10"></div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4" id="ext-slider-container" style="<?php echo $_file_type == 'additive' ? '' : 'display:none;' ?>">
        <div class="well well-sm text-center">
        	<h4>Extruder <i class="fab-lg fab-fw icon-fab-term txt-color-red"></i></h4>
            <h5>actual:&nbsp;<span id="label-temp1"><?php echo $_temperature; ?></span>°C&nbsp;&nbsp;-&nbsp;&nbsp;target:<span id="label-temp1-target">&nbsp;<?php echo $_temperature_target ?></span>°C </h5>
            <div id="temp1" data-action="temp1" class="sliders margin-bottom-10"></div>
        </div>
    </div>
    
    
    <div class="col-md-4 col-lg-4"  id="bed-slider-container" style="<?php echo $_file_type == 'additive' ? '' : 'display:none;' ?>">
        <div class="well well-sm text-center">
        	<h4>Bed <i class="fab-lg fab-fw icon-fab-term txt-color-red"></i></h4>
            <h5>actual:&nbsp;<span id="label-temp2"><?php echo $_bed_temperature == '' ? 0 : $_bed_temperature; ?></span>°C&nbsp;&nbsp;-&nbsp;&nbsp;target:<span id="label-temp2-target">&nbsp;<?php echo $_bed_temperature_target ?></span> °C </h5>
            <div id="temp2" data-action="temp2" class="sliders margin-bottom-10"></div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-6" id="rpm-slider-container" style="<?php echo $_file_type != 'additive' ? '' : 'display:none;' ?>">
    	<div class="well well-sm text-center"  >
            <h4>Motor RPMs </h4>
            <h5><span id="label-rpm"><?php echo $_rpm == '' ? 100 : $_rpm ?></span></h5>
            <div id="rpm" data-action="rpm" class="sliders margin-bottom-10"></div>
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
        <pre id="ace-editor" style="height: 250px;"></pre>
     </div>
</div>


