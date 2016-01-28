<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-12">
			<div class="smart-form">
				<fieldset>
					<div class="row">
						<section class="col col-3">
							<label class="label">Period</label>
							<label class="input">
								<input type="text" name="date-range-picker" value="<?php echo $start_date.' - '.$end_date ?>">
							</label>
						</section>
						<section class="col col-3">
							<label class="label">Make</label>
							<label class="select"><?php echo form_dropdown('type', $type_options, $type, 'id="type"'); ?> <i></i></label>
						</section>
						
						<section class="col col-3">
							<label class="label">Status</label>
							<label class="select"><?php echo form_dropdown('status', $status_options, $status, 'id="status"'); ?> <i></i></label>
						</section>
						
						<section class="col col-3">
							<label class="label">&nbsp;</label>
							<a style="padding: 6px 12px" class="btn btn-primary btn-block" id="search"><i class="fa fa-search"></i></a>
						</section>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<table class="table table-bordered table-striped" id="history">
	<thead>
		<tr>
			<th></th>
			<th><i class="fa fa-calendar"></i> <span class="hidden-xs">When</span></th>
			<th><i class="fa fa-play rotate-90 txt-color-blue"></i> <span class="hidden-xs">Make</span></th>
			<th>Status</th>
			<th>Description</th>
			<th><i class="fa fa-clock-o"></i> <span class="hidden-xs">Duration Time</span></th>
			<th class="hidden"></th>
			<th class="hidden"></th>
			<th class="hidden"></th>
			<th class="hidden"></th>
			<th class="hidden"></th>
			<th class="hidden"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($tasks as $task): ?>
			
			<?php
				
				
				$task['task_attributes'] = str_replace(PHP_EOL, '<br>',  $task['task_attributes']) ;
			 	$attributes = json_decode($task['task_attributes'], true);
				
				$when = strtotime($task['finish_date']) > strtotime("-1 day") ? get_time_past($task['finish_date']).' ago' : date('d M, Y', strtotime($task['finish_date']));
				 				
				$duration_time = dateDiff(strtotime($task['finish_date']), strtotime($task['start_date']));
				$duration_time_string = '';
				foreach($duration_time as $key => $val){
					$duration_time_string .= $val.' '.$key.' ';
				}
				
				
				$info = '<h4>';
				
				if($task['file_name'] != '') $info .= '<a href="'.site_url('objectmanager/edit/'.$task['id_object']).'"><i class="fa fa fa-file-o"></i> '.$task['raw_name'].'</a>';
				if($task['object_name'] != '') $info .= '<small><i class="fa fa fa-folder-open-o"></i> '.$task['object_name'].'</small>';
				if(isset($attributes['mode_name']) && $attributes['mode_name'] != '') $info .= '<a href="#">'.ucfirst($attributes['mode_name']).'</a><small> </small>';						
				
				$info .= '</h4>';
					
			?>
			
			<tr>
				<td class="center" width="20px"><a href="#" > <i class="fa fa-chevron-right fa-lg" data-toggle="row-detail" title="Show Details"></i> </a></td>
				<td width="100"><?php echo $when; ?></td>
				<td width="80"><strong><i class="<?php echo $icons[$task['type']] ?>"></i> <span class="hidden-xs"><?php echo ucfirst($task['type']); ?></strong></span></td>
				<td width="100"><?php echo $status_label[$task['status']]; ?></td>
				<td><?php echo $info; ?></td>
				<td><strong><?php echo $duration_time_string; ?></strong></td>
				<!-- start date -->
				<td class="hidden"><?php echo date('d M, Y', strtotime($task['start_date'])); ?> at <?php echo date('G:i', strtotime($task['start_date'])) ?></td>
				<!-- finish date -->
				<td class="hidden"><?php echo date('d M, Y', strtotime($task['finish_date'])); ?> at <?php echo date('G:i', strtotime($task['finish_date'])) ?></td>
				<!-- note -->
				<td class="hidden"><?php echo (isset($attributes['note'])) ? $attributes['note'] : ''; ?></td>
				
				<td class="hidden"><?php echo $task['type']; ?></td>
				
				<td class="hidden"><?php echo $task['id_file']; ?></td>
				<td class="hidden"><?php echo $task['id_object']; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
				