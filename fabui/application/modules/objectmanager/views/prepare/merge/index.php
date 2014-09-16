<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > Manage file > <strong>Merge</strong></span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager/edit/'.$obj_id)?>" class="btn btn-default"> Back to object</a>
		</div>
	</div>
</div>

<div class="row files">
    <div class="col-sm-12">
        <div class="well">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input id="output_name" class="form-control" type="text" placeholder="Output file name" value="" />
                    </div>
                </div>
            </div>
       
            <?php echo $table; ?>
            
            <div class="form-horizontal">
                <div class="form-actions">
                    <button class="btn btn-primary" type="button" id="merge-button"><i id="procees-button-icon" class="fa fa-cogs"></i> Merge</button>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row response" style="display: none;"> 
    <div class="col-sm-12">
        
        <div class="well text-center">
            <h1 class=" text-success">
        		<i class="fa fa-check fa-lg"></i> Merge  complete
            </h1>
            <hr />
            <a href="javascript:go_to_file();" class="btn btn-default">Go to file</a>&nbsp;&nbsp;
            <a href="<?php echo site_url('objectmanager/edit/'.$obj_id); ?>" class="btn btn-default">Back to Object</a>
            
            
        </div>
    </div>
</div>