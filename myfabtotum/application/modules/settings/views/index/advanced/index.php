<div class="tab-pane animate fadeIn fade in active" id="tab2">
    <div class="row margin-top-10">
        <div class="col-md-12">
            <div class="well no-border">
                <form id="advanced-form" class="form-horizontal" action="<?php echo site_url('settings/advanced') ?>" method="post">
                    <fieldset>
                        <legend><a rel="tooltip" data-placement="top" data-original-title="For expert user only"><span class="badge bg-color-red">!</span></a> Boot script</legend>
                        <div class="form-group">
                            <div class="col-md-12">
								<div class="well" id="editor" style="height: 400px; display: none;"><?php echo $_boot_script ?></div>
							</div>   
                        </div>
                    </fieldset>
                    <div class="form-actions">
						<button class="btn btn-primary" id="submit">
							<i class="fa fa-save">
							</i>
							Save
						</button>
					</div>
                    <input type="hidden" name="file_content" id="file_content" />
                </form>
            </div>
        </div>
    </div>
</div>