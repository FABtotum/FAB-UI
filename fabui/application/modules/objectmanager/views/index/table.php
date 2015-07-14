<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-6">

			<div class="form-inline">
				<div class="form-group">
					<select class="form-control bulk-select">
						<option value="">Bulk Actions</option>
						<option value="delete">Delete</option>
					</select>
				</div>
				<button class="btn btn-primary bulk-button" type="button">
					Apply
				</button>

			</div>
		</div>

		
			
			
			<!--
				
			<div class="col-sm-6">	
				
			<p>Disk used
				<span class="pull-right"><?php echo $_disk_used_percent ?>%</span>
			</p>
			
			<div class="progress progress-sm">
				<div class="progress-bar bg-color-greenLight" role="progressbar" aria-valuetransitiongoal="<?php echo $_disk_used_percent ?>" ></div>
			</div>
			-->
			<!--
			<span class="easy-pie-title">Disk Used</span>			
			<div class="easy-pie-chart txt-color-blue easyPieChart" data-percent="<?php echo $_disk_used_percent ?>" data-pie-size="35">
				<span class="percent percent-sign font-xs"><?php echo $_disk_used_percent ?></span>
			</div>
			
			
			</div>
			-->

		
	</div>
</div>
<table class="table table-striped table-bordered table-hover smart-form has-tickbox" width="100%" id="objects_table">
	<thead>
		<tr>
			<th><label class="checkbox">
				<input class="select-all" type="checkbox" name="checkbox-inline" />
				<i></i> </label></th>
			<th>Name</th>
			<th>Description</th>
			<th width="150">Date</th>
			<th width="75">Files</th>
			<th></th>
		</tr>
	</thead>
	<tbody></tbody>
</table>