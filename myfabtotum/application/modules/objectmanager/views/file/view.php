<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"> 
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > File > Edit</span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/edit/'.$_object_id)?>" class="btn btn-default"> <i class="icon-fab-manager"></i> Back to object</a>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="well no-border">
            <form class="form-horizontal" action="<?php echo site_url("objectmanager/file/view/".$_object_id."/".$_file->id) ?>" method="post" id="view-form">
                <fieldset>
                    <legend><?php echo $_file->file_name; ?></legend>
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
                    <div class="form-group">
                        <div class="col-md-12">
                            <h5>Content</h5>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
    						<div class="well" id="editor" style="display: none;"><?php echo $_file_content ?></div>
    					</div>   
                    </div>
                </fieldset>
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