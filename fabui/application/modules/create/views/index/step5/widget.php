<div class="row tip" style="display: none;">
	<div class="col-sm-12">
		<div class="alert alert-warning fade in">
			<i class="fa-fw fa fa-warning"></i> <strong class="tip tip-message"></strong>
		</div>
	</div>	
</div>

<div class="row create-monitor" style="display: none;">

    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Elapsed Time</p>
            <h4 class="elapsed-time">00:00:00</h4>
        </div>
    </div>
    <div class="col-md-4 col-lg-4">
        <div class="well well-sm text-center">
            <p>Time left</p>
            <h4 class="estimated-time-left"> Processing.. </h4>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4">
        <div class="well  ">
        		<a rel="tooltip" data-action="zup"   title="Change Z height: + 0.1mm" href="javascript:void(0)" class="btn btn-primary controls" ><i class="fa fa-angle-double-down"></i>&nbsp;Z</a>
        		<a rel="tooltip" data-action="zdown" title="Change Z height: - 0.1mm" href="javascript:void(0)" class="btn btn-primary controls" style="margin-left:5px;"><i class="fa fa-angle-double-up"></i>&nbsp;Z</a>
                <a rel="tooltip" title="Stop print process" href="javascript:void(0);"  data-action="stop"      class="btn btn-default txt-color-red          <?php echo $_running ? '' : 'disabled' ?> stop pull-right" id="stop-button"> <i title="Stop"   class="fa fa-stop fa-lg"></i> </a>
				<!--<a title="Pause"  href="javascript:void(0);"  data-action="pause"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Pause" class="fa fa-pause fa-lg"></i> </a>-->
				<a rel="tooltip" title="Turn On/Off the lights" id="light-switch" href="javascript:void(0);"  data-action="light-on"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Lights" class="fa fa-lightbulb-o fa-lg"></i> </a>
				<!--<a rel="tooltip" title="Send mail" id="send-mail" href="javascript:void(0);"  data-action="<?php echo $mail == 1 ? 'send-mail-false' : 'send-mail-true' ?>"  class="btn btn-default <?php echo $mail == 1 ? 'txt-color-green' : 'txt-color-red'; ?> controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Lights" class="fa fa-envelope fa-lg"></i> </a>-->    
        </div>
    </div>
</div>

<div class="row create-monitor" style="display: none;">
    <div class="col-md-12">
        <div class="well text-center">
          
            
            <h4><label class="label label-success">Progress <span id="label-progress"><?php echo $_progress_percent != '' ? '( ' . $_progress_percent . '% )' : ''; ?></span></label></h4>
            
            <div class="bar-holder">
                <div class="progress progress-striped">
    				<div id="lines-progress" class="progress-bar bg-green  active" aria-valuetransitiongoal="<?php echo str_replace(',', '.', $_progress_percent); ?>"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row create-monitor" style="display: none;">
    <div class="<?php echo trim($_file_type) == 'additive' ? 'col-md-4 col-lg-4' : 'col-md-6 col-lg-6' ?> " id="velocity-slider-container">
        <div class="well">
        
           
           	<h5><i class="fa fa-lg fa-fw fa-flash txt-color-yellow"></i> Speed
           		<label id="label-velocity" class="label label-warning pull-right"><?php echo $_velocity == '' ? 100 : $_velocity ?>%</label>	
           	</h5>
           <div id="velocity" data-action="velocity" class="sliders speed-range margin-bottom-10"></div>
           <p class="font-md">&nbsp;</p>
            
        </div>
    </div>
    
    <div class="col-md-4 col-lg-4" id="ext-slider-container" style="<?php echo $_file_type == 'additive' ? '' : 'display:none;' ?>">
        <div class="well">
        	
           
           	<h5><i class="fab-lg fab-fw icon-fab-term txt-color-red"></i> Extruder 
           		<label id="label-temp1-target" class="label label-info pull-right"><?php echo intval($_temperature_target).'&deg;' ?></label>
           		<label id="label-temp1" class="label label-danger pull-right margin-right-5"><?php echo intval($_temperature) . '&deg;'; ?></label>
           	</h5>
            <div id="act-ext-temp" class="noUiSlider"></div>
            <div id="temp1" data-action="temp1" class="sliders extruder-range margin-bottom-10"></div>
            <p class="font-md">&nbsp;</p>
        </div>
    </div>
    
    
    <div class="col-md-4 col-lg-4"  id="bed-slider-container" style="<?php echo $_file_type == 'additive' ? '' : 'display:none;' ?>">
        <div class="well">
           	<h5>
           		<i class="fab-lg fab-fw icon-fab-term txt-color-red"></i> Bed
           		<label id="label-temp2-target" class="label label-info pull-right"><?php echo intval($_bed_temperature_target).'&deg;' ?></label>
           		<label id="label-temp2" class="label label-danger pull-right margin-right-5"><?php echo intval($_bed_temperature) . '&deg;'; ?></label>
           		
           	</h5>
           	
           	<div id="act-bed-temp" class="noUiSlider"></div>
            <div id="temp2" data-action="temp2" class="sliders bed-range margin-bottom-10"></div>
            <p class="font-md">&nbsp;</p>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-6" id="rpm-slider-container" style="<?php echo $_file_type != 'additive' ? '' : 'display:none;' ?>">
    	<div class="well"  >
            
            
            <h5> Motor RPMs
            	<label id="label-rpm" class="label label-info pull-right"><?php echo $_rpm == '' ? 100 : $_rpm ?></label>
            </h5>
            <div id="rpm" data-action="rpm" class="sliders margin-bottom-10"></div>
            <p class="font-md">&nbsp;</p>
            	
            
            
            
        </div>
    </div>
</div>



<div class="row margin-bottom-10">
    <div class="col-sm-12">
        <label style="cursor: pointer;" id="details" class="label label-info pull-right">Console <i class="fa fa-angle-double-up"></i></label>
    </div>
</div>

<div class="row details-container" style="display: none;">
     <div class="col-md-12">
        <pre id="ace-editor" style="height: 250px;"></pre>
     </div>
</div>



