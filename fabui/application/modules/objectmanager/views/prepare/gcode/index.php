<div class="row">
    <div class="col-sm-6">
        <h1 class="page-title txt-color-blueDark"><i class="fa icon-fab-manager fab-fw "></i> Objectmanager > <span>STL</span> > <span>GCODE</span></h1>
    </div>
    
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/manage/'.$_object.'/'.$_file->id)?>" class="btn btn-primary fab-buttons <?php echo $_task ? 'disabled' : ''; ?>"> <i class="fa fa-th-large"></i> Manage</a>&nbsp;&nbsp;
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object)?>" class="btn btn-primary  fab-buttons <?php echo $_task ? 'disabled' : ''; ?>"> <i class="icon-fab-manager"></i> Back to object</a>
		</div>
	</div>
    
</div>
<div class="row setting" style="<?php echo $_task ? 'display:none;' : ''; ?>">
    <div class="col-sm-12">
        <div class="well">
            
            <h5>This experimental feature takes the selected <strong>STL</strong> file and turns it into a printable model (additive manufacturing only).</h5>
            
            <form class="form-horizontal">
                
                <fieldset>
                    
                    <legend><?php echo $_file->file_name; ?></legend>
                    
                    
                    <div class="form-group">
                        
                        <label class="col-md-1 control-label">Output file name</label>
                        <div class="col-md-11">
                            <input id="output" class="form-control" type="text" value="<?php echo $_file->raw_name; ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-1 control-label">Type</label>
                        <div class="col-md-11">
                            <select id="output_type" class="form-control">
                                <option value=".gcode">gcode</option>
                                <option value=".gc">gc</option>
                                <option value=".nc">nc</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        
                        <label class="col-md-1 control-label"> Setting config</label>
                        <div class="col-md-11">
                            <select id="preset-file" class="form-control">
                            
                                <?php foreach($_presets as $_set): ?>
                                
                                    <option value="<?php echo $_set['file'] ?>"><?php echo $_set['name'] ?> - <?php echo $_set['description'] ?></option>
                                
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                    	<div class="col-md-1"></div>
                    	<div class="col-md-11">
                    		<div class="row margin-bottom-10">
                    			<div class="col-md-12">
                    				<a rel="tooltip" title="Delete config" class="btn btn-default btn-xs pull-right txt-color-red " href="javascript:void(0);" id="delete-slicer-config-button"><i class="fa fa-trash "></i></a>
                    				<a data-toggle="modal" data-backdrop="static" data-target=".add-config-modal"  rel="tooltip" title="Add config" class="btn btn-default btn-xs pull-right txt-color-green " href="#"><i class="fa fa-plus"></i></a>
                    				<a rel="tooltip" title="Download config" class="btn btn-default btn-xs pull-right" id="download-slicer-config-button" href="javascript:void(0);"><i class="fa fa-download"></i></a>
                    				<a rel="tooltip" title="Save config" class="btn btn-default btn-xs pull-right txt-color-blue" href="javascript:void(0);" id="save-config"><i class="fa fa-save"></i></a>
                    			</div>
                    		</div>
                    		<div class="row">
                    			<div class="col-md-12">
                    				<div style="height: 200px;" id="slicer-config"><?php echo file_get_contents($_presets[0]['file']) ?></div>
                    			</div>
                    		</div>
                    	</div>
                    </div>
                    
                </fieldset>
                
                <div class="form-actions">
					<button class="btn btn-primary" type="button" id="procees-button">
						<i id="procees-button-icon" class="fa fa-cubes"></i> Create GCode
					</button>
				</div>
            </form>
        </div>
    </div>
</div>

<div class="row monitor" style="<?php echo $_task ? '' : 'display:none;'; ?>">
	<div class="col-sm-12">
		<a rel="tooltip" title="Stop slicing process" class="pull-right btn btn-danger stop"><i class="fa fa-stop"></i> Stop </a>
	</div>
</div>

<div class="row monitor margin-top-10" style="<?php echo $_task ? '' : 'display:none;'; ?>">
	<div class="col-sm-12">
		<div class="well">
			<div class="row">
				
				 <div class="col-md-4 col-lg-4">
			        <div class="well text-center">
			            <p>Elapsed Time</p>
			            <h2 class="elapsed-time">00:00:00</h2>
			        </div>
			    </div>
			    
			     <div class="col-md-8">
			        <div class="well text-center">
			            <p>Progress <span id="label-progress"></span></p>
			            <div class="bar-holder">
			                <div class="progress">
			    				<div id="lines-progress" class="progress-bar bg-color-blue" aria-valuetransitiongoal="0" aria-valuenow="0" style="width:0%;"></div>
			                </div>
			            </div>
			            
			        </div>
			    </div>
				
			</div>
		</div>
	</div>
    <!--
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
    -->
</div>
<!--
<div class="row monitor" style="<?php echo $_task ? '' : 'display:none;'; ?>">
    <div class="col-md-12">
        <div class="well text-center">
            <p>Progress <span id="label-progress"></span></p>
            <div class="bar-holder">
                <div class="progress">
    				<div id="lines-progress" class="progress-bar bg-color-blue" aria-valuetransitiongoal="0" aria-valuenow="0" style="width:0%;"></div>
                </div>
            </div>
            
        </div>
    </div>
</div>
-->
<div class="row monitor" style="<?php echo $_task ? '' : 'display:none;'; ?>">
    <div class="col-sm-12">
    	<div class="well">
        	<pre id="editor" style="height: 200px; "></pre>
        </div>
    </div>
</div>
<div class="row complete" style="display: none;"> 
    <div class="col-sm-12">
        
        <div class="well text-center">
            <h1 class=" text-success">
        		<i class="fa fa-check fa-lg"></i> Slicing complete
            </h1>
            
            <hr />
            <a href="<?php echo site_url('objectmanager/edit/'.$_object); ?>" class="btn btn-default">Back to Object</a>
            
            <a href="javascript:print();" class="btn btn-default">Print</a>
            
        </div>
    </div>
</div>



<div class="modal fade add-config-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
	      		<div class="modal-header">
	      			<h4 class="modal-title"><i class="fa fa-list"></i> Add new config file</h4>
	      		</div>
	      		<div class="modal-body">
	      			<div class="row">
	      				
	      				<div class="col-md-12">
	      					<div class="form-group">
	      						<input id="config-name" class="form-control" type="text" placeholder="Name">
	      					</div>
	      					<div class="form-group">
	      						<input id="config-description" class="form-control" type="text" placeholder="Description">
	      					</div>
	      					<div class="form-group">
	      						
	      						<form enctype="multipart/form-data"
        									action="<?php echo site_url('objectmanager/slicer_config_upload'); ?>"
        									class="dropzone" id="mydropzone"></form>
	      						
	      					</div>
	      					
	      				</div>
	      			</div>
	      		</div>
	      		<div class="modal-footer">
	      			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      			<button id="upload-config" type="button" class="btn btn-primary"> <i class="fa fa-save"></i> Save </button>
	      		</div>
			</div>
		</div>
</div>
<form id="download-slicer-config-form" method="POST" action="<?php echo site_url('objectmanager/download_slicer_config') ?>">
	<input type="hidden" name="dsc" id="dsc" value="">
	<input type="hidden" name="nsc" id="nsc" value="">
</form>
