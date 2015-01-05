<div class="row">
    <div class="col-sm-4">
        <h1 class="page-title txt-color-blueDark"><i class="fa icon-fab-manager fab-fw fa-fw "></i> Objectmanager > <span>ASC</span> > <span>STL</span></h1>
    </div>
    
    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/manage/'.$_object.'/'.$_file->id)?>" class="btn btn-primary fab-buttons <?php echo $_task ? 'disabled' : ''; ?>"> <i class="fa fa-th-large"></i> Manage</a>&nbsp;&nbsp;
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object)?>" class="btn btn-primary  fab-buttons <?php echo $_task ? 'disabled' : ''; ?>"> <i class="icon-fab-manager"></i> Back to object</a>
		</div>
	</div>
    
</div>


<div class="row">
	 <div class="col-sm-12">
    	<a style="display:none;" id="stop-process" class="btn btn-danger pull-right margin-bottom-10"> <i class="fa fa-stop"></i> Stop</a>
    </div>
</div>

<div class="row setting" style="<?php echo $_task ? 'display:none;' : ''; ?>">   
    <div class="col-sm-12">
        <div class="well">
        
        	<h5>This experimental feature takes the selected cloud data and process it into a solid <strong>STL</strong> file that can be printed</h5>
            <form class="form-horizontal">
            
                <fieldset>
                
                    <legend><?php echo $_file->raw_name; ?></legend>    
                    <div class="form-group">
                    
                        <label class="col-md-1 control-label">Output file name</label>
                        <div class="col-md-11">
                            <div class="input-group">
                                <input id="output" class="form-control" type="text" value="<?php echo $_file->raw_name; ?>" />
                                <span class="input-group-addon">.STL</span>
                            </div>

                        </div>
                    </div>
                    
                </fieldset>
                <div class="form-actions">
					<button class="btn btn-primary" type="button" id="procees-button">
						<i id="procees-button-icon" class="fa fa-cogs"></i> Process
					</button>
				</div>
            
            
            </form>
        
        </div>
    </div>



</div>



<div class="row monitor" style="<?php echo $_task ? '' : 'display:none;'; ?>">
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
<div class="row monitor" style="<?php echo $_task ? '' : 'display:none;'; ?>">
    <div class="col-sm-12">
        <div class="well" id="editor" style="height: 200px; ">
        </div>
    </div>
</div>
<div class="row complete" style="display: none;"> 
    <div class="col-sm-12">
        
        <div class="well text-center">
            <h1 class=" text-success">
        		<i class="fa fa-check fa-lg"></i> Reconstrucion  complete
            </h1>
            
            <hr />
            <a href="<?php echo site_url('objectmanager/edit/'.$_object); ?>" class="btn btn-default">Back to Object</a>&nbsp;&nbsp;
            <a href="javascript:print();" class="btn btn-default">Print</a>
            
        </div>
    </div>
</div>