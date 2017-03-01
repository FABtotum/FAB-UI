<div class="row">
	<section class="col-sm-12">
		<?php echo $widget; ?>
	</section>
</div>
<!-- -->
<div class="modal fade" id="change-value-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Set new value for extruder step</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input id="feeder-step-new-value" type="number" class="form-control" step="0.01" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" id="change-extruder-step-value-button" class="btn btn-primary"><i class="fa fa-check"></i> Change</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->