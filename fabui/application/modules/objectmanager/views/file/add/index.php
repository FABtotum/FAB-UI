<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-puzzle-piece fa-fw ">
			</i>
			Objectmanager <span> > File > Add </span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object_id)?>" class="btn btn-primary"> Back to object</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
        
            <ul id="myTab1" class="nav nav-tabs bordered">
                <li class="active"><a href="#remote" data-toggle="tab">Local Disk</a></li>
                <li><a href="#usb" data-toggle="tab">Usb Disk</a></li>
            </ul>
            
            <div id="myTabContent1" class="tab-content padding-10">
            
                <div class="tab-pane fade in active" id="remote">
                    
                    <div>
        				<form enctype="multipart/form-data" method="POST" action="<?php echo site_url('objectmanager/upload'); ?>" class="dropzone" id="mydropzone">
        				</form>
                        <form id="file-form" method="POST" action="<?php echo site_url('objectmanager/file/'.$_action.'/'.$_object_id); ?>">
                            <input type="hidden" id="files" name="files">
                            <input type="hidden" id="usb_files" name="usb_files">
                            <input type="hidden" id="action" name="action" value="<?php echo $_action ?>">
                            <input type="hidden" id="object" name="object" value="<?php echo $_object_id; ?>">
                        </form>
        			</div>
                
                </div>
                
                
                <div class="tab-pane fade in" id="usb">
                
                    <?php if (sizeof($folder_tree) > 0 ): ?>
                        <div class="tree smart-form">
                            <ul>
                                <?php foreach($folder_tree as $folder): ?>
                                
                                    <li><span data-loaded="false" data-folder="<?php echo $folder; ?>"><i class="fa fa-lg fa-folder-open"></i> <?php echo rtrim(str_replace("/media/", '', $folder), '/'); ?></span>
                                        <ul></ul>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php else: ?>
                        
                        <div class="text-center">
                                    <h1><span style="font-size: 50px;" class="icon-fab-usb"></span></h1>
                                    <h1>Please insert USB disk</h1>
                                    <a id="check-usb" class="btn btn-default" href="javascript:void(0);">Reload</a>
                        </div>
                                        
                    <?php endif; ?>
                
                
                </div>
            
            </div> 
        
        
			
			<div class="form-actions">
				<button class="btn btn-primary btn-lg" id="save-object"> <i class="fa fa-save"></i>&nbsp;Save</button>
			</div>
		</div>
	</div>
</div>