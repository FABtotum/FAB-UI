<!--<div class="widget-body-toolbar"></div>-->
<table class="table table-bordered table-striped table-hover has-tickbox smart-form" id="objects_table">
    <thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th class="hidden-xs">Description</th>
		</tr>
	</thead>
    
    <tbody>
		<?php foreach($files as $_file): ?>
			<tr class="obj" data-id="<?php echo $_file->id; ?>">
				<td><label class="checkbox">	<input type="checkbox" name="checkbox-file" data-file-id="<?php echo $_file->id; ?>" data-file-path="<?php echo $_file->full_path; ?>" /> <i></i></label></td>
				<td><?php echo $_file->raw_name ?>	</td>
				<td class="hidden-xs"><?php echo $_file->note ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>