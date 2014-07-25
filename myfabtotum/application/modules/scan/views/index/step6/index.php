<div
	class="step-pane <?php echo  $_task && $_task_attributes->step == 6 ? 'active': '' ?>"
	id="step6">

	<div class="row">
		<div class="col-sm-6">
			<h2 class="text-primary">End</h2>
		</div>
	</div>

	<div class="row">

		<div class="col-sm-12">

			<div class="well well-light text-center">

				<h5>The scan is finished and the object is ready, what do you want to do now?</h5>

				<p>
					<button type="button" id="print-object" class="btn btn-default btn-xl">
						<i class="glyphicon glyphicon-print"></i> Print
					</button>
					
					<button type="button" id="view-object" class="btn btn-default btn-xl">
						<i class="glyphicon glyphicon-print"></i> View Object
					</button>
				</p>

			</div>

		</div>

	</div>

</div>


