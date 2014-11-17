<div class="row margin-bottom-10">
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		<h1 class="page-title txt-color-blueDark"><i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > Edit</span> > <span><strong id="label-obj-name"><?php echo $_object -> obj_name; ?></strong></span></h1>
	</div>
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<a href="<?php  echo site_url('objectmanager')?>" class="btn btn-primary pull-right"><i class="icon-fab-manager"></i> Back to objects</a>&nbsp;&nbsp;
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="well">
			<form class="form-horizontal" action="<?php echo site_url('objectmanager/edit/'.$_object->id) ?>" method="POST">
				<fieldset>
					
					<div class="form-group">
						<label rel="tooltip" data-placement="top" data-original-title="If is checked everyone can use this object" class="col-md-2 control-label"> Public </label>
						<div class="col-md-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="private" id="private" <?php echo $_object->private == 0 ? 'checked="checked"' : '' ?>></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"> Name </label>
						<div class="col-md-10">
							<input type="text" id="obj_name" name="obj_name" class="form-control" value="<?php echo $_object -> obj_name; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"> Description </label>
						<div class="col-md-10">
							<textarea class="form-control" id="obj_description" name="obj_description" rows="5"><?php echo $_object -> obj_description; ?></textarea>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<button class="btn btn-primary" type="button" id="save-object">
						<i class="fa fa-save"></i> Save
					</button>
				</div>
			</form>
		</div>
	</div>
	
	<div class="col-sm-6">
		
		<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php echo $_widget; ?>
		</article>
	</div>
</section>
		
	</div>
	
</div>
