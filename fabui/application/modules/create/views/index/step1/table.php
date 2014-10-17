<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-12">
			<a href="<?php  echo site_url('objectmanager/add')?>" class="btn btn-default pull-right"> Add new object</a>
		</div>
	</div>
</div>
<table class="table table-striped table-hover has-tickbox smart-form" id="objects_table">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th class="hidden-xs">Description</th>
			<th class="hidden-xs">Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($objects as $_obj): ?>
			<tr class="obj" data-id="<?php echo $_obj->id; ?>">
				<td><label class="radio">	<input type="radio" name="checkbox-inline"><i></i></label></td>
				<td><?php echo $_obj->obj_name ?>	</td>
				<td class="hidden-xs"><?php echo $_obj->obj_description ?></td>
				<td style="width: 200px;" class="hidden-xs"><?php echo mysql_to_human($_obj->date_insert); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>