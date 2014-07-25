<div class="step-pane <?php echo $_running ? 'active' : '' ?>" id="step5">
	<div class="row">
		<div class="col-sm-12">
            <div class="row">
                
                <div class="col-sm-6">
                    
                    <div class="onoffswitch-container margin-top-10">
            			<span class="onoffswitch-title">Turn off the printer at the end </span> 
            			<span class="onoffswitch">
            				<input data-action="turn-off" type="checkbox" class="onoffswitch-checkbox" id="turn-off">
            				<label class="onoffswitch-label" for="turn-off"> 
            					<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
            					<span class="onoffswitch-switch"></span>
            				</label> 
            			</span> 
            		</div>
                    <div class="onoffswitch-container margin-top-10">
            			<span class="onoffswitch-title">Take photo at the end </span> 
            			<span class="onoffswitch">
            				<input data-action="photo" type="checkbox" class="onoffswitch-checkbox" id="photo">
            				<label class="onoffswitch-label" for="photo"> 
            					<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
            					<span class="onoffswitch-switch"></span>
            				</label> 
            			</span> 
            		</div>
                
                </div>
                <div class="col-sm-6">
                
                    <div class="btn-group margin-bottom-10 pull-right" style="margin-top: 10px;">
                         <a title="Stop"                     href="javascript:void(0);"  data-action="stop"      class="btn btn-default txt-color-red          <?php echo $_running ? '' : 'disabled' ?> stop pull-right" id="stop-button"> <i title="Stop"   class="fa fa-stop fa-lg"></i> </a>
        				 <a title="Pause"  href="javascript:void(0);"  data-action="pause"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> pull-right"> <i title="Pause" class="fa fa-pause fa-lg"></i> </a>
        				 <a title="Lights" id="light-switch" href="javascript:void(0);"  data-action="light-on"  class="btn btn-default txt-color-red controls <?php echo $_running ? '' : 'disabled' ?> stop pull-right"> <i title="Lights" class="fa fa-lightbulb-o fa-lg"></i> </a>
                    </div>
                
                </div>
            
            </div>
		</div>
	</div>
    <?php echo $_tab5_monitor_widget; ?>
</div>
