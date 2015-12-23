<div class="row">
	<div class="col-sm-12 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object_id)?>" class="btn btn-primary"> <i class="fa fa-arrow-left"></i> Back to object</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
        
            <ul id="myTab1" class="nav nav-tabs bordered">
                <li class="active"><a href="#remote" data-toggle="tab"><i class="fa fa-hdd-o"></i> Local Disk</a></li>
                <li><a href="#usb" class="check-usb" data-toggle="tab"><i class="fa fa-usb"></i> Usb Disk</a></li>
            </ul>
            
            <div id="myTabContent1" class="tab-content padding-10">
            
                <div class="tab-pane fade in active" id="remote">
                    
                    <div>
        				<form enctype="multipart/form-data" method="POST" action="<?php echo site_url('objectmanager/upload'); ?>" class="dropzone" id="mydropzone"></form>
                        <form id="file-form" method="POST" action="<?php echo site_url('objectmanager/file/'.$_action.'/'.$_object_id); ?>">
                            <input type="hidden" id="files" name="files">
                            <input type="hidden" id="usb_files" name="usb_files">
                            <input type="hidden" id="action" name="action" value="<?php echo $_action ?>">
                            <input type="hidden" id="object" name="object" value="<?php echo $_object_id; ?>">
                        </form>
        			</div>
                
                </div>
                
                
                <div class="tab-pane fade in" id="usb">
                
                    
                
                
                </div>
            
            </div> 
        
        
			
			<div class="form-actions">
				<button class="btn btn-primary btn-lg" id="save-object"> <i class="fa fa-save"></i>&nbsp;Save</button>
			</div>
		</div>
	</div>
</div>