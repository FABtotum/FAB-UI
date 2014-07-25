<div class="step-pane <?php echo $_running ? "" : "active" ?>" id="step1">

	<div class="row">
		<div class="col-sm-6">
			<h2>
				<strong>Step 1 </strong> - Select an object to create
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="row">
				<?php foreach ($objects as $obj): ?>
				<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">

					<div class="obj" object-id="<?php echo $obj->id; ?>" id="obj-<?php echo $obj->id; ?>">
						<div class="obj-body">
							<span class="obj-name"> <?php echo $obj->obj_name; ?>
							</span>
						</div>

					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="well">
				<dl class="dl-horizontal">
					<dt>Name:</dt>
					<dd id="obj_name"></dd>
					<dt>Description:</dt>
					<dd id="obj_description"></dd>
					<dt>Created on</dt>
					<dd id="date_insert"></dd>
					<dt>Last update on</dt>
					<dd id="date_updated"></dd>
				</dl>
			</div>
		</div>
	</div>
</div>
