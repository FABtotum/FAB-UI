<div class="tasks">

	<?php if(!$running): ?>
	
	<div class="alert alert-info fade in">
		<i class="fa-fw fa fa-info"></i>
		 No tasks running at this moment.
	</div>
	
	<?php else: ?>
    
        <div class="table-responsive">
        
            <table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th colspan="3">Running Tasks</th>
				</tr>
			</thead>
			<tbody>

				<tr>
					<td><?php echo $running['controller'] ?></td>
					<td><?php echo $running['status'] ?></td>
					<td><?php echo mysql_to_human($running['start_date']) ?></td>
				</tr>

			

			</tbody>

		</table>
        
        </div>
		
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

				<?php foreach($lasts as $task) : 
				
                
              
                    
                    $_status_icon = '';
                    
                    switch($task['controller']){
                        
                        case 'scan':
                            $_status_icon = 'fab-lg fab-fw icon-fab-scan';
                            break;
                        case 'print':
                            $_status_icon = 'fab-lg fab-fw icon-fab-print';
                            break;
                        case 'updates':
                            $_status_icon = 'fa fa-refresh';
                            break;
                        case 'objectmanager':
                            $_status_icon = 'fab-lg fab-fw icon-fab-manager';
                            break;
                    }   

                    $_status = '';
                    
                    switch($task['status']){
                        
                        case 'performed':
                            $_status = 'fa fa-check txt-color-green';
                            break;
                        case 'running':
                            $_status = 'fa fa-cog fa-spin txt-color-green';
                            break;
                        case 'canceled':
                            $_status = 'fa fa-times txt-color-red';
                        
                    }

                ?>
                
				<tr>
					<td style="width: 50px;" class="text-center"><?php echo $_status_icon != '' ? '<a title="'.ucfirst($task['controller']).'" href="javascript:void(0);"><i class=" '.$_status_icon.'"></i></a>'  :  $task['controller'] ?></td>
					<td style="width: 50px;" class="text-center"><a href="javascript:void(0);" title="<?php echo ucfirst($task['status'])  ?>"><i class="<?php echo $_status?>"></i></a></td>
					<td><b><?php echo ucfirst($task['type']) ?></b> - <b>start:</b> <?php echo mysql_to_human($task['start_date']) ?> <b>end:</b> <?php echo mysql_to_human($task['finish_date']) ?></td>
				</tr>

				<?php endforeach; ?>

			</tbody>

		</table>
	</div>
	
	<?php else: ?>
	
	<?php endif; ?>

</div>