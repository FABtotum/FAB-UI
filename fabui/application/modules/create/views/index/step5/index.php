<div class="step-pane <?php echo $_running ? 'active' : '' ?>" id="step5">
	<!--
	<div class="row">
		<div class="col-sm-12">
            <div class="row">
                
                <div class="col-sm-6 col-xs-6">
                    
                    
                </div>
                <div class="col-sm-6 col-xs-6">
                
                    <div class="btn-group margin-bottom-10 pull-right" style="margin-top: 10px;">
                         <a rel="tooltip" title="Stop print process"                     href="javascript:void(0);"  data-action="stop"      class="btn btn-default txt-color-red          <?php echo $_running ? '' : 'disabled' ?> stop pull-right" id="stop-button"> <i title="Stop"   class="fa fa-stop fa-lg"></i> </a>
        				 <a title="Pause"  href="javascript:void(0);"  data-action="pause"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Pause" class="fa fa-pause fa-lg"></i> </a>
        				 <a rel="tooltip" title="Turn On/Off the lights" id="light-switch" href="javascript:void(0);"  data-action="light-on"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Lights" class="fa fa-lightbulb-o fa-lg"></i> </a>
        				 <a rel="tooltip" title="Send mail" id="send-mail" href="javascript:void(0);"  data-action="<?php echo $mail == 1 ? 'send-mail-false' : 'send-mail-true' ?>"  class="btn btn-default <?php echo $mail == 1 ? 'txt-color-green' : 'txt-color-red'; ?> controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Lights" class="fa fa-envelope fa-lg"></i> </a>
        				 
                    </div>
                
                </div>
            
            </div>
		</div>
	</div>
	-->
    <?php echo $_tab5_monitor_widget; ?>
</div>
