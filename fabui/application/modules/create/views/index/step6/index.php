<div class="step-pane" id="step6">
    <div class="row">
        <h1 class="text-center text-success"><i class="fa fa-check fa-lg"></i> <?php echo $label ?> complete </h1>
        
        <h4 class="text-center margin-top-10 margin-bottom-20 laser-print">
        	Check the complete stop of the unit and the Laser Head and retrieve the part
        </h4>
        
    </div>
    <div class="row">
    	<!--
        <div class="col-sm-6">
            <div class="well text-center">
               <h2>Elapsed Time</h2>
               <h2 class="elapsed-time">00:00:00</h2>
            </div>
        </div>
       -->
        <div class="col-sm-12 text-center">
        	<div class="alert alert-info alert-block z-override-alert" style="display:none;">
        		<p class="font-md"> During this <?php echo strtolower($label) ?> you changed Z's height, do you want to save and override the value for the next tasks <!--<span class="z_override"></span>--> ? <a href="javascript:void(0);" class="btn btn-sm btn-primary save-z-override">Yes</a> </p>
        	</div>
        	<a href="javascript:void(0);" class="btn btn-default restart">Restart <?php echo $label ?></a>
        	<a href="javascript:void(0);" class="btn btn-default new">New <?php echo $label ?></a>
        </div>
        
    </div>
</div>