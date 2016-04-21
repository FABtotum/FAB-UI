<style>small {color: #999;} .files-table tbody > tr:hover {cursor:pointer;}</style>
<script type="text/javascript">var recent_files = new Array();</script>
<table class="table files-table table-striped table-hover smart-form has-tickbox" id="recent_table">
	<thead>
		<tr>
			<th></th>
			<th><i class="fa fa-file-o"></i> <span class="hidden-xs">File</span></th>
			<th class="hidden-xs">Note</th>
			<th class="hidden-xs"><i class="fa fa-calendar"></i> <span class="hidden-xs">Last <?php echo $type == 'additive' ? 'print' : 'milling' ?> </span></th>
			<th class="hidden-xs">Status</th>
			<th class="hidden-xs"><i class="fa fa-clock-o"></i> <span class="hidden-xs">Duration</span></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($last_creations as $file): ?>
			<tr class="file-recent-row">
				<td><label class="radio"><input class="recent-obj-file" value="<?php echo $file['id']; ?>" type="radio" name="file-selected"><i></i> </label></td>
				<td><strong><i class="fa fa-cubes txt-color-blue"></i>  <?php echo $file['raw_name'] ?></strong> <span class="hidden-xs">></span> <small class="hidden-xs"><i class="fa fa-folder-open"></i> <?php echo $file['object_name'] ?></small></td>
				<td class="hidden-xs"><?php echo $file['note'] ?></td>
				<td class="hidden-xs"><?php echo strtotime($file['finish_date']) > strtotime("-1 day") ? get_time_past($file['finish_date']) . ' ago' : date('d M, Y', strtotime($file['finish_date'])) ?></td>
				<td class="hidden-xs"><?php echo $status_label[$file['status']] ?></td>
				<td class="hidden-xs"><?php echo $file['duration'] ?></td>
			</tr>
			<script type="text/javascript">
				recent_files[<?php echo $file['id'] ?>] = <?php echo json_encode($file) ?>;
			</script>
		<?php endforeach; ?>
	</tbody>
</table>
