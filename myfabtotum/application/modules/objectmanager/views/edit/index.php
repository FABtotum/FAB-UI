<div class="row">
	<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="icon-fab-manager fab-fw"></i> Objectmanager <span> > Edit</span>
		</h1>
	</div>
	<div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 text-align-right">
		<div class="page-title">
			<a href="<?php  echo site_url('objectmanager')?>" class="btn btn-default"> Back to objects</a>
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
						<label class="col-md-1 control-label">
							Name
						</label>
						<div class="col-md-11">
							<input type="text" name="obj_name" class="form-control" value="<?php echo $_object->obj_name; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">
							Description
						</label>
						<div class="col-md-11">
							<textarea class="form-control" name="obj_description" rows="5"><?php echo $_object->obj_description; ?></textarea>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<button class="btn btn-primary" type="submit">
						<i class="fa fa-save"></i> Submit
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-sm-12 text-align-right">
        <div class="page-title">
			<a href="<?php  echo site_url('objectmanager/file/add/'.$_object->id)?>" class="btn btn-default">  Add Files</a>
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