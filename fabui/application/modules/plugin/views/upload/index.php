<div class="row">
	<!-- col 
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<h1 class="page-title txt-color-blueDark"><i class="fab-fw icon-fab-plugin"></i> Plugins <span> > Upload</span></h1>
	</div>
-->
</div>
<?php if(isset($error)): ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="alert alert-warning">
				<i class="fa fa-warning"></i> <?php echo $error; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<?php if(isset($installed)): ?>
				<h2>Installing Plugin: <?php echo $file_name; ?></h2>
				<p>Unpacking the package... </p>
				<p>Installing the plugin...</p>
				<p>Plugin installed successfully...</p>
				<a href="<?php echo site_url("plugin") ?>">Return to Plugins page</a>
				
			<?php else: ?>
			<form class="form-inline" enctype="multipart/form-data" method="post" action="<?php echo site_url("plugin/upload") ?>">

				<fieldset>
					<legend>
						If you have a plugin in a .zip format, you may install it by uploading it here.
					</legend>

					<div class="form-group">
						<input type="file" class="btn btn-default" id="plugin-file" name="plugin-file" accept=".zip">
					</div>
					<button type="submit" id="install-button" class="btn btn-primary disabled" style="margin-left:5px;">Install now</button>
					
				</fieldset>
			</form>
			<?php endif; ?>
		</div>
	</div>
</div>
