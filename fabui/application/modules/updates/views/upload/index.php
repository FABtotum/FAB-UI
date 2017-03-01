<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa fa-fw fa-refresh"> </i> Update Center <span>> Upload</span>  </h1>
	</div>

	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> FAB UI beta <span class="txt-color-blue"><i class="fa fa-mobile"></i>&nbsp;v&nbsp;<?php echo $fabui_local ?></span></h5>
				
			</li>
			<li class="sparks-info">
				<h5> FABlin FW<span class="txt-color-purple"><i class="fa fa-shield"></i>&nbsp;v&nbsp;<?php echo $marlin_local ?></span></h5>
			</li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo site_url("updates/upload") ?>">
				<fieldset>
					<legend>If you have a install file in a .zip format, you may install it by uploading it here.</legend>
					<div class="form-group">			
						<div class="col-md-10">
							<label class="radio radio-inline">
								<input type="radio" class="radiobox" name="type" value="fabui" checked="checked">
								<span>FABui</span> 
							</label>
							<label class="radio radio-inline">
								<input type="radio" class="radiobox" name="type" value="fw">
								<span>Firmware</span>  
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-10">
							<input type="file" class="btn btn-default" id="install-file" name="install-file" accept=".zip">
							<p class="help-block">some help text here.</p>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-primary disabled" type="submit" id="install-button"> Install now </button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>