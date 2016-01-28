<div class="row margin-bottom-10">
	<div class="col-sm-12">
		<?php if(in_array(strtolower($_file->file_ext), $preview_files)): ?>
		<a data-placement="bottom" href="<?php echo site_url('objectmanager/file/preview/'.$_object_id.'/'.$_file->id) ?>" rel="tooltip" data-original-title="A web-based 3D viewer for GCode files." style="margin-left:5px;" class="btn bg-color-purple txt-color-white pull-right"><i class="fa fa-eye"></i> Preview </a>
		<?php endif; ?>
		
		<a data-placement="bottom" href="<?php echo site_url('objectmanager/download/file/'.$_file->id) ?>" rel="tooltip" data-original-title="Save data on your computer. You can use it in the third party software." style="margin-left:5px;" class="btn btn-info txt-color-white pull-right"><i class="fa fa fa-download"></i>  Download </a>
		
		<?php if(in_array(strtolower($_file->file_ext),$printables_files)): ?>
			<?php $type = strtolower($_file->print_type) == 'additive' ? 'print' : 'mill';  ?>
			<a style="margin-left:5px;" rel="tooltip" data-placement="bottom" data-original-title="<?php echo ucfirst($type); ?> this file" href="<?php echo site_url('make/'.$type.'?obj='.$_object_id.'&file='.$_file->id)?>" class="btn btn-success pull-right"><i class="fa fa-play rotate-90"></i> <?php echo ucfirst($type); ?></a>
		<?php endif; ?>
		
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="well well-light">
			<div class="row">
				<div class="col-sm-8">
					<div class="form-horizontal">			
						<fieldset>
							<div class="form-group">
								<label class="col-md-2 control-label">Name</label>
								<div class="col-md-10">
									<input type="text" id="name" name="name" class="form-control" value="<?php echo $_file -> raw_name; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Note</label>
								<div class="col-md-10">
									<textarea style="resize: none !important;" id="note" name="note" class="form-control" rows="2"><?php echo $_file -> note; ?></textarea>
								</div>
							</div>		
						</fieldset>
					</div>
				</div>
				<?php if($_file->print_type == 'additive'): ?>
				<div class="col-sm-4">
					<div class="row">
						<div class="col-sm-12 margin-bottom-10">
							<span class="text">Model size <span class="pull-right"><strong><?php echo $dimesions; ?></strong></span></span>
						</div>
						<div class="col-sm-12 margin-bottom-10">
							<span class="text">Filament used <span class="pull-right"><strong><?php echo $filament; ?></strong></span></span>
						</div>
						<div class="col-sm-12 margin-bottom-10">
							<span class="text">Estimated time print <span class="pull-right"><strong><?php echo $estimated_time; ?></strong></span></span>
						</div>
						<div class="col-sm-12 margin-bottom-10">
							<span class="text">Layers <span class="pull-right"><strong><?php echo $number_of_layers; ?></strong></span></span>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="editor" style="display:none;"></div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="form-horizontal">
						<div class="form-actions">
							<div class="row">
								
								<div class="col-sm-12">
									<?php if(!$is_stl): ?>
										<button class="btn btn-default pull-left" type="button" id="load-content"><i class="fa fa-angle-double-down"></i> Open Gcode </button>
									<?php endif; ?>
									
									
									<label class="checkbox-inline" style="padding-top:0px;">
										 <input type="checkbox" class="checkbox" disabled="disabled" id="also-content">
										 <span>Save gcode also</span>
									</label>
									<button class="btn btn-primary" type="button" id="save"><i class="fa fa-save"></i> Save </button>
								</div>
								
								
								
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>