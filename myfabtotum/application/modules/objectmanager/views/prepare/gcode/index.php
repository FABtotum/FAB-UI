<div class="row">
    <div class="col-sm-6">
        <h1 class="page-title txt-color-blueDark"><i class="fa fa-puzzle-piece fa-fw "></i> Objectmanager > <span>STL</span> > <span>GCODE</span></h1>
    </div>
</div>
<div class="row setting">
    <div class="col-sm-12">
       
        <div class="well">
            
            <form class="form-horizontal">
                
                <fieldset>
                    
                    <legend><?php echo $_file->file_name; ?></legend>
                    
                    
                    <div class="form-group">
                        
                        <label class="col-md-1 control-label">Output</label>
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
<div class="row monitor" style="display: none;">
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
<div class="row monitor" style="display: none;">
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
<div class="row monitor" style="display: none;">
    <div class="col-sm-12">
        <div class="well" id="editor" style="height: 200px; ">
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

