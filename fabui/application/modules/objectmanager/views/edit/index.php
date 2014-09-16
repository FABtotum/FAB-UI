<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > Edit</span> > <span><strong id="label-obj-name"><?php echo $_object -> obj_name; ?></strong></span></h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager')?>" class="btn btn-default"><i class="icon-fab-manager"></i> Back to objects</a>&nbsp;&nbsp;
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<form class="form-horizontal" action="<?php echo site_url('objectmanager/edit/'.$_object->id) ?>" method="POST">
				<fieldset>
					<legend>
						Object
					</legend>
					<div class="form-group">
						<label class="col-md-1 control-label"> Name </label>
						<div class="col-md-11">
							<input type="text" id="obj_name" name="obj_name" class="form-control" value="<?php echo $_object -> obj_name; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label"> Description </label>
						<div class="col-md-11">
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
</div>
<section id="widget-grid">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php echo $_widget; ?>
		</article>
	</div>
</section>