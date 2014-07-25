<div class="tasks">

	<?php if(!$running): ?>
	
	<div class="alert alert-info fade in">
		<i class="fa-fw fa fa-info"></i>
		 No tasks running at this moment.
	</div>
	
	<?php else: ?>
		
	<?php endif; ?>

	

	<?php if(count($lasts) > 0): ?>
	
	<div class="table-responsive">

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th colspan="3">Last tasks</th>
				</tr>
			</thead>
			<tbody>

				<?php foreach($lasts as $task) : ?>
				
				<tr>
					<td><?php echo $task['type'] ?></td>
					<td><?php echo $task['status'] ?></td>
					<td><?php echo mysql_to_human($task['start_date']) ?></td>
				</tr>

				<?php endforeach; ?>

			</tbody>

		</table>
	</div>
	
	<?php else: ?>
	
	<?php endif; ?>


</div>
