

<div class="row">
	<div class="col-sm-6">
		<div class="well well-sm">
			<div class="smart-timeline">
				<ul class="smart-timeline-list">
					<?php foreach($tasks as $task): ?>
						<?php 
							$task['task_attributes'] = str_replace(PHP_EOL, '<br>',  $task['task_attributes']) ;
						 	$attributes = json_decode($task['task_attributes'], true);
						 ?> 
						<li>
							<div class="smart-timeline-icon <?php echo $task['status'] == 'performed' ? 'bg-color-greenDark' : 'bg-color-red' ?>">
								<i rel='tooltip' title="<?php echo ucfirst($task['type']).' '.$task['status']; ?>" class="<?php echo $icons[$task['type']] ?>"></i> 
							</div>
							<div class="smart-timeline-time">
								
								<small><?php echo  strtotime($task['finish_date']) > strtotime("-1 day") ? get_time_past($task['finish_date']).' ago' : date('d M, Y', strtotime($task['finish_date'])); ?></small>
							</div>
							<div class="smart-timeline-content">
								
								<div class="row">
									<div class="col-sm-12">
										<p>
											<strong><?php echo ucfirst($task['type']) ?></strong> <i class="fa <?php echo $task['status'] == 'performed' ? 'fa-check': 'fa-hand-stop-o'?>"></i>
										</p>
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-6">
										<!-- file -->
										<?php if($task['file_name'] != ''): ?><p><i class="fa fa fa-file-o"></i> <a href="<?php echo site_url('objectmanager/edit/'.$task['id_object']) ?>"><strong><?php echo $task['file_name']; ?></strong></a></p><?php endif; ?>
										
										<!-- objectmanager -->
										<?php if($task['object_name'] != ''): ?><p><i class="fa fa-folder-open-o"></i> <a href="<?php echo site_url('objectmanager/edit/'.$task['id_object']) ?>"><?php echo $task['object_name']; ?></a></p><?php endif; ?>
											
										<!-- started date -->
										<p>
											<span>Started <?php echo date('d M, Y', strtotime($task['start_date'])) ?> at <?php echo date('G:i', strtotime($task['start_date'])) ?></span><br>
										<!-- finish date -->
											<span><?php echo $task['status'] == 'performed' ? 'Finished': 'Stopped';?> <?php echo date('d M, Y', strtotime($task['finish_date']))?> at <?php echo date('G:i', strtotime($task['finish_date'])) ?></span>
										</p>
										
										<?php 
										
											$duration_time = dateDiff(strtotime($task['finish_date']), strtotime($task['start_date']));
											$duration_time_string = '';
											foreach($duration_time as $key => $val){
												$duration_time_string .= $val.' '.$key.' ';
											}
										?>
										<!-- duration time -->
										<p>
											<span><?php echo ucfirst($task['type']) ?> time:  <strong><?php echo $duration_time_string; ?></strong></span><br>  
										
										<?php $keyToCheck = $task['type'] == 'scan' ? 'scan' : 'print'; ?>
										<?php if(($task['status'] == 'stopped') && isset($attributes['monitor'][$keyToCheck]['stats']['percent'])): ?>
										<span>Progress: <?php echo number_format($attributes['monitor'][$keyToCheck]['stats']['percent'], 1) ?> %</p></span>
										<?php endif;?>
										
										</p>
										
									</div>
									
									<div class="col-sm-6">
										<?php if(isset($attributes['note']) && $attributes['note']): ?>
										<p><i class="fa fa-pencil-square-o"></i> <strong>Note:</strong></p> 
										<p><?php echo $attributes['note']; ?></p>
										
										
										<?php endif; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<?php if(in_array($task['type'], array('print', 'mill'))): ?>
											<a href="<?php  echo site_url('make/'.$task['type'].'?obj='.$task['id_object'].'&file='.$task['id_file'])?>" class="btn btn-default"><?php echo ucfirst($task['type']) ?> again</a>
										<?php endif; ?>
									</div>
								</div>
								
								
								
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-6">
		<div class="well ">
			
			<div class="row">
				<div class="col-sm-12 "><h6 class="text-primary"><i class="<?php echo $icons['print'] ?>"></i> Print</h6></div>
				<div class="col-sm-12 ">
					<span class="text">Total time <span class="pull-right"><?php echo $print_total_time ?></span></span>
				</div>
				<div class="col-sm-12 ">
					<span class="text">Completed<span class="pull-right"><?php echo $print_total_performed ?></span></span>
				</div>
				<div class="col-sm-12 ">
					<span class="text">Aborted<span class="pull-right"><?php echo $print_total_stopped ?></span></span>
				</div>
				<div class="col-sm-12">
					<hr class="simple">
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 "><h6 class="text-primary"><i class="<?php echo $icons['mill'] ?>"></i> Mill</h6></div>
				<div class="col-sm-12 ">
					<span class="text">Total time <span class="pull-right"><?php echo $mill_total_time ?></span></span>
				</div>
				<div class="col-sm-12 ">
					<span class="text">Completed<span class="pull-right"><?php echo $mill_total_performed ?></span></span>
				</div>
				<div class="col-sm-12 ">
					<span class="text">Aborted<span class="pull-right"><?php echo $mill_total_stopped ?></span></span>
				</div>
				<div class="col-sm-12">
					<hr class="simple">
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12 "><h6 class="text-primary"><i class="<?php echo $icons['scan'] ?>"></i> Scan</h6></div>
				<div class="col-sm-12 ">
					<span class="text">Total time <span class="pull-right"><?php echo $scan_total_time ?></span></span>
				</div>
				<div class="col-sm-12 ">
					<span class="text">Completed<span class="pull-right"><?php echo $scan_total_performed ?></span></span>
				</div>
				<div class="col-sm-12 ">
					<span class="text">Aborted<span class="pull-right"><?php echo $scan_total_stopped ?></span></span>
				</div>
				<div class="col-sm-12">
					<hr class="simple">
				</div>
			</div>
			
			
		</div>
	</div>
	
</div>