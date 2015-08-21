<?php

$totalLasts = count($lasts);
$countLasts = 0;
?>

<div class="tab-content">
	
	
	<div class="tab-pane active fade in" id="running">
		
		<div class="row custom-scroll">
		<?php if($running): ?>
			
			<div class="col-xs-3 col-sm-3">
				<time datetime="<?php echo $running['finish_date'] ?>" class="icon">
					<strong><?php echo date("M", strtotime($running['start_date'])); ?></strong>
					<span><?php echo date("d", strtotime($running['start_date'])); ?></span>
				</time>
			</div>
			
			<div class="col-xs-9 col-sm-9">
					<h6 class="no-margin">
						<a href="<?php echo site_url($running['controller']); ?>"><?php echo getTaskTitle($running['controller'], $running['type']); ?></a>
					</h6>
					
					<p></p>
					
					<ul class="list-inline">
						<li>
							<i class="fa fa-calendar"></i>
							<a href="javascript:void(0);"> <?php echo date('F j, Y, g:i a', strtotime($running['start_date'])); ?> </a>
						</li>
					</ul>
				</div>
			
		<?php else: ?>
			
			<div class="col-sm-12">
				<div class="alert alert-info fade in">
					<i class="fa-fw fa fa-info"></i>
					 No tasks running at this moment.
				</div>
			</div>
		<?php endif; ?>
			
		</div>
		
	</div>

	<div class="tab-pane fade in" id="lasts">
	
		<div id="lasts-wrap" class="row" style="height: 500px; overflow-y: auto;">
		
			
			<?php foreach($lasts as $task): ?>
				
				<?php $countLasts++; ?>
				
				<div class="col-xs-3 col-sm-3">
					<time datetime="<?php echo $task['finish_date'] ?>" class="icon">
						<strong><?php echo date("M", strtotime($task['finish_date'])); ?></strong>
						<span><?php echo date("d", strtotime($task['finish_date'])); ?></span>
					</time>
				</div>
				
				
				<div class="col-xs-9 col-sm-9">
					<h6 class="no-margin">
						<a href="javascript:void(0);"><?php echo getTaskTitle($task['controller'], $task['type']); ?></a>
					</h6>
					
					<p></p>
					
					<ul class="list-inline">
						<li>
							<i class="fa fa-calendar"></i>
							<a href="javascript:void(0);"> <?php echo date('F j, Y, g:i a', strtotime($task['finish_date'])); ?> </a>
						</li>
					</ul>
				</div>
				
				<?php if($countLasts < $totalLasts): ?>
				<div class="col-sx-12 col-sm-12"><hr></div>
				<?php endif; ?>
			<?php endforeach; ?>
			
			
			<div class="col-sx-12 col-sm-12"><hr></div>
			<div class="col-sx-12 col-sm-12 text-center">
				<a href="javascript:void(0)" class="btn btn-sm btn-default tasks-load-more"><i class="fa fa-arrow-down text-muted"></i> LOAD MORE</a>
			</div>
			
		</div>
	</div>
</div>



<?php 

function getTaskTitle($controller, $type){
	
	$icon = '';
	$title = '';
	
	switch($controller){
		
		case 'updates':
			$icon = 'fa fa-refresh';
			$title = 'Update';			
			
			if($type == 'marlin'){
				$title .= ' - Firmware';
			}
			
			if($type == 'fabui'){
				$title .= ' - FABUI';
			}
			
			
			
			break;
		case 'create':
			$icon = 'icon-fab-print';
			$title = 'Create';
			break;
		case 'scan':
			$icon = 'icon-fab-scan';
			$title = 'Scan';
			break;
		default:
			$icon = '';
			$title = ucfirst($controller);
			break;
		
	}
	
	return '<i class="'.$icon.'"></i> '.$title;
	
}


?>