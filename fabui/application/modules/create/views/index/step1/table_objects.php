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
			<tr class="obj" data-id="<?php echo $_obj['id']; ?>">
				<td><label class="radio">	<input type="radio" name="checkbox-inline"><i></i></label></td>
				<td><strong><?php echo $_obj['obj_name'] ?></strong></td>
				<td class="hidden-xs"><i><?php echo $_obj['description'] ?></i></td>
				<td style="width: 200px;" class="hidden-xs"><?php echo date('d/m/Y', strtotime($_obj['date_insert'])); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>