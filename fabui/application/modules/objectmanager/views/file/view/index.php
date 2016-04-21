<?php echo file_header_toolbar($object, $_file, 'edit') ?>
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
										<button class="btn btn-default pull-left" type="button" id="load-content"><i class="fa fa-angle-double-down"></i> view content </button>
									<?php endif; ?>
									
									
									<label class="checkbox-inline" style="padding-top:0px;">
										 <input type="checkbox" class="checkbox" disabled="disabled" id="also-content">
										 <span>Save also content </span>
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