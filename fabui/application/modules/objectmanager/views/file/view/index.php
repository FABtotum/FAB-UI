<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"> 
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > File > Edit</span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/manage/'.$_object_id.'/'.$_file->id)?>" class="btn btn-default"> <i class="fa fa-th-large"></i> Manage</a>
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object_id)?>" class="btn btn-default"> <i class="icon-fab-manager"></i> Back to object</a>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="well no-border">
            <form action="<?php echo site_url("objectmanager/file/view/".$_object_id."/".$_file->id) ?>" method="post" id="view-form">
                <div class="row">
                	<div class="col-sm-6">
	                	
	                		 <div class="form-group">
		                        <div class="col-md-12">
		                            <h5>Name</h5>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <div class="col-md-12">
		                            <input type="text" id="name" name="name" class="form-control" value="<?php echo $_file->raw_name; ?>" />
		                        </div>
		                    </div>
		                    
		                    <div class="form-group">
		                        <div class="col-md-12">
		                            <h5>Note</h5>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <div class="col-md-12">
		                            <textarea id="note" name="note" class="form-control" rows="2"><?php echo $_file->note; ?></textarea>
		                        </div>
		                    </div>
	                	
                	</div>
                	
                	<?php if(!$is_stl && strtolower($_file->file_ext) != '.nc'): ?>
	                	<div class="col-sm-6">
	                		<div class="form-group">
	                			<div class="col-md-12">
	                				<p><h5>Model size: <span class="text-info"><?php echo $dimesions; ?></span></h5></p>
	                				<p><h5>Filament used: <span class="text-info"><?php echo $filament; ?></span></h5></p>
	                				<p><h5>Estimated time print: <span class="text-info"><?php echo $estimated_time; ?></span></h5></p>
	                				<p><h5>Layers: <span class="text-info"><?php echo $number_of_layers; ?></span></h5></p>
	                				
	                			</div>
	                		
	                		</div>
	                		
	                	</div>
                	<?php endif; ?>
                	
                </div>
                
                <div class="row">
	                <fieldset>
	                
	                    <!--<legend><?php echo $_file->file_name; ?></legend>-->
	                   
	                    
	                    <!-- STL FILE CAN'T BE DISPLAYED -->
	                    <?php if(!$is_stl): ?>
	                    <div class="form-group">
	                        <div class="col-md-12">
	                            <h5 id="file-content-title">Loading content.. <i class="fa fa-spin fa-spinner"></i></h5>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="col-md-12">
	    						<div class="well" id="editor" style="display: none;"><?php //echo $_file_content ?></div>
	    					</div>   
	                    </div>
	                    <?php endif; ?>
	                </fieldset>
                </div>
                <div class="form-actions">
						<button class="btn btn-primary" id="submit" >
							<i class="fa fa-save">
							</i>
							Submit
						</button>
				</div>
                <input type="hidden" name="file_content" id="file_content" />
            </form>
        </div>
    </div>
</div>