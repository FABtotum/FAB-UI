<style type="text/css">.bulk-button,.details-button{margin-right:5px !important;}</style>
<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-12">
			<a rel="tooltip" data-placement="bottom" data-original-title="Delete all selected objects" data-action="delete"   href="javascript:void(0);" class="btn btn-danger  bulk-button"><i class="fa fa-trash"></i> Delete</a>
			<a rel="tooltip" data-placement="bottom" data-original-title="Download all selected objects" data-action="download" href="javascript:void(0);" class="btn btn btn-info bulk-button"><i class="fa fa-download"></i> Download</a>
			<a href="<?php  echo site_url('objectmanager/add')?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Object</a>
		</div>
	</div>
</div>

<table class="table table-responsive table-striped table-bordered table-hover has-tickbox" id="objects_table">
	<thead>
		<tr>
			<th class="hidden"></th>
			<th class="center" width="20px"></th>
			<th class="center table-checkbox" width="20px"><label class="checkbox-inline"><input type="checkbox" class="checkbox style-0 select-all"><span></span></label></th>
			<th>Name</th>
			<th class="hidden-xs">Description</th>
			<th class="hidden-xs" width="150">Date</th>
			<th class="hidden-xs" width="75">Files</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>