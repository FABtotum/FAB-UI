<div class="step-pane <?php echo  $_task && $_task_attributes['step'] == 5 ? 'active': '' ?>" id="step5">

    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-primary">What do you wan to do now?</h2>
            
        </div>
    
    </div>
<!--
	<div class="row finish_option_1">
    	
    	
        <div class="col-sm-6">
            
            <div class="well">
                <div class="row">
                
                    <div class="col-sm-6 text-center">
                        <a href="javascript:void(0)" class="reconstruction"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('scan').'assets/img/reconstruction.png' ?>" /></a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <h1 class="text-primary">Reconstruction</h1>
                        <h2>This experimental feature takes the selected cloud data and process it into a solid STL file that can be printed.</h2>
                    </div>
                
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 text-center">
            
            <div class="well">
                <div class="row">
                
                     <div class="col-sm-6">
                        <a href="javascript:void(0)" class="add-scan"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('scan').'assets/img/add-scan.png' ?>" /></a>
                    </div>
                    <div class="col-sm-6">
                        <h1 class="text-primary">Add a new scan</h1>
                        <h2>Add a new scan to the existing object. Merge the scans to increase the final model quality.</h2>
                    </div>
                
                </div>
            </div>
        </div>
       
    </div>
 -->
    <div class="row finish_option_1">
    	<!--
        <div class="col-sm-6 text-center">
            
            <div class="well">
                <div class="row">
                
                     <div class="col-sm-6">
                        <a href="javascript:void(0);" class="merge"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('scan').'assets/img/merge-scan.png' ?>" /></a>
                    </div>
                    <div class="col-sm-6">
                        <h1 class="text-primary">Merge</h1>
                        <h2>Combine the point clouds from several scans into one piece without deleting any points.</h2>
                    </div>
                
                </div>
            </div>
        </div>
       -->
       
       <div class="col-sm-6 text-center">
			<div class="well">
                <div class="row">
                
                     <div class="col-sm-6">
                        <a href="<?php echo site_url("scan"); ?>"  class="add-scan"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('scan').'assets/img/add-scan.png' ?>" /></a>
                    </div>
                    <div class="col-sm-6">
                        <h1 class="text-primary">Make a new scan</h1>
                        <h2>Make a new scan </h2>
                    </div>
                
                </div>
            </div>
		</div>

        <div class="col-sm-6 text-center">
            
            <div class="well">
                <div class="row">
                
                     <div class="col-sm-6">
                        <a target="_blank" href="#" class="download-scan"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('scan').'assets/img/download-scan.png' ?>" /></a>
                    </div>
                    <div class="col-sm-6">
                        <h1 class="text-primary">Download</h1>
                        <h2>Save the cloud data on your computer. You can use it in the third party software.</h2>
                    </div>
                
                </div>
            </div>
        </div>
        
	</div>
	
	
	<div class="row finish_option_2">
		<div class="col-sm-6 text-center">
			<div class="well">
                <div class="row">
                
                     <div class="col-sm-6">
                        <a href="<?php echo site_url("scan"); ?>"  class="add-scan"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('scan').'assets/img/add-scan.png' ?>" /></a>
                    </div>
                    <div class="col-sm-6">
                        <h1 class="text-primary">Make a new scan</h1>
                        <h2>Make a new scan </h2>
                    </div>
                
                </div>
            </div>
		</div>
	</div>
	
	
</div>

