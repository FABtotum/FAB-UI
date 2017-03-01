<div class="step-pane" id="step2">
	<div class="row">
		<div class="col-sm-6">
			<h6 class="text-primary">Select a file</h6>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div id="files-container"></div>
		</div>
	</div>

	<div class="row slicer" style="display: none;">
		<div class="col-sm-12">
			<h2 class="text-danger text-center"><i class="fa fa-warning"></i> Warning</h2>
			<h2 class="text-danger text-center">This file can not be printed just as it is, it must be processed before</h2>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-warning"></i> Warning</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<h5 class="text-center">This file can not be printed just as it is, it must be processed before</h5>

							<h5 class="text-center">Do you want to process it?</h5>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cancel
					</button>
					<button type="button" class="btn btn-primary" id="process-button">
						Process
					</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div>