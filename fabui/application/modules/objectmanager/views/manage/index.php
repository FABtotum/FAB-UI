<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > Manage file > <strong><?php echo $file->raw_name; ?></strong></span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
            <a href="<?php  echo site_url('objectmanager/file/view/'.$obj_id.'/'.$file->id)?>" class="btn btn-primary"><i class="fa fa-file"></i> Edit</a>&nbsp;&nbsp;
			<a href="<?php  echo site_url('objectmanager/edit/'.$obj_id)?>" class="btn btn-primary"><i class="icon-fab-manager"></i> Back to object</a>
		</div>
	</div>
</div>


<div class="row">
    
    <div class="col-sm-6">
        
        <div class="well">
            <div class="row">
            
                <div class="col-sm-6 text-center">
                    <a href="<?php echo site_url('objectmanager/prepare/'.$first_box_action.'/'.$obj_id.'/'.$file->id); ?>"><?php echo $first_box_img ?></a>
                </div>
                <div class="col-sm-6 text-center">
                    <h1 class="text-primary"><a href="<?php echo site_url('objectmanager/prepare/'.$first_box_action.'/'.$obj_id.'/'.$file->id); ?>"><?php echo $first_box_title; ?></a></h1>
                    <h2><?php echo $first_box_desc; ?></h2>
                </div>
            
            </div>
        </div>
    </div>
    
    
    
    
    
      <div class="col-sm-6 text-center">
        
        <div class="well">
            <div class="row">
            
                 <div class="col-sm-6">
                    <a href="<?php echo site_url('objectmanager/download/'.$file->id) ?>" class="download-scan"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('objectmanager').'assets/img/download-scan.png' ?>" /></a>
                </div>
                <div class="col-sm-6">
                    <h1 class="text-primary">Download</h1>
                    <h2>Save the cloud data on your computer. You can use it in the third party software.</h2>
                </div>
            
            </div>
        </div>
    </div>    
</div>

<!-- ONLY FOR ASC FILE -->
<?php if(strtolower($file->file_ext) == '.asc'): ?>
<div class="row">
    <div class="col-sm-6 text-center">
        
        <div class="well">
            <div class="row">
            
                 <div class="col-sm-6">
                    <a href="<?php echo site_url('objectmanager/prepare/merge/'.$obj_id.'/'.$file->id); ?>"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('objectmanager').'assets/img/merge-scan.png' ?>" /></a>
                </div>
                <div class="col-sm-6">
                    <h1 class="text-primary">Merge</h1>
                    <h2>Combine the point clouds from several scans into one piece without deleting any points.</h2>
                </div>
            
            </div>
        </div>
    </div>
    <div class="col-sm-6 text-center">
        
        <div class="well">
            <div class="row">
            
                 <div class="col-sm-6">
                    <a href="<?php echo site_url('scan').'?obj='.$obj_id; ?>" class="add-scan"><img style="max-width: 50%; display: inline;" class="img-responsive" src="<?php echo module_url('objectmanager').'assets/img/add-scan.png' ?>" /></a>
                </div>
                <div class="col-sm-6">
                    <h1 class="text-primary">Add a new scan</h1>
                    <h2>Add a new scan to the existing object. Merge the scans to increase the final model quality.</h2>
                </div>
            
            </div>
        </div>
    </div>
</div>
<?php  endif; ?>
<!-- ONLY FOR GCODE FILE -->
<?php if(strtolower($file->file_ext) == '.gc' || strtolower($file->file_ext) == '.gcode'  ): ?>
	
	<div class="row">
		<div class="col-sm-6 text-center">
			<div class="well">
				<div class="row">
					<div class="col-sm-6">
						<a href="<?php  echo site_url('objectmanager/file/preview/'.$obj_id.'/'.$file->id)?>"><i class="fa fa-cubes fa-4x" style="font-size: 189px;"></i></a>
					</div>
					<div class="col-sm-6">
						<h1 class="text-primary"><a href="<?php  echo site_url('objectmanager/file/preview/'.$obj_id.'/'.$file->id)?>">GCode Viewer</a></h1>
						<h2>A web-based 3D viewer for GCode files.</h2>
						<p><a target="_blank" href="https://github.com/joewalnes/gcode-viewer">more details</a></p>
					</div>
				</div>
				
			</div>
		</div>
	</div>

<?php endif; ?>



<!-- ONLY FOR STL FILE -->
<?php if(strtolower($file->file_ext) == '.stl'): ?>
		
	<div class="row">
		<div class="col-sm-6 text-center">
			<div class="well">
				<div class="row">
					<div class="col-sm-6">
						<a href="<?php  echo site_url('objectmanager/file/preview/'.$obj_id.'/'.$file->id)?>"><i class="fa fa-cubes fa-4x" style="font-size: 189px;"></i></a>
					</div>
					<div class="col-sm-6">
						<h1 class="text-primary"><a href="<?php  echo site_url('objectmanager/file/preview/'.$obj_id.'/'.$file->id)?>">STL Viewer</a></h1>
						<h2>A web-based 3D viewer for STL files.</h2>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>

<?php endif; ?>

