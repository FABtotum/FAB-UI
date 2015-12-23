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
            
                <?php
                
                 $_status_icon = '';
                    
                    switch($running['controller']){
                        
                        case 'scan':
                            $_status_icon = 'fab-lg fab-fw icon-fab-scan';
                            break;
                        case 'create':
                            $_status_icon = 'fab-lg fab-fw icon-fab-print';
                            break;
                        case 'updates':
                            $_status_icon = 'fa fa-refresh';
                            break;
                        case 'objectmanager':
                            $_status_icon = 'fab-lg fab-fw icon-fab-manager';
                            break;
                        case 'settings':
                            $_status_icon = 'fa fa-cogs';
                            break;
						case 'maintenance':
                            $_status_icon = 'fa fa-wrench';
                            break;
                    }   

                    $_status = '';
                    
                    switch($running['status']){
                        
                        case 'performed':
                            $_status = 'fa fa-check txt-color-green';
                            break;
                        case 'running':
                            $_status = 'fa fa-cog fa-spin txt-color-green';
                            break;
                        case 'canceled':
                            $_status = 'fa fa-times txt-color-red';
                            break;
                        case 'stopped':
                            $_status = 'fa fa-stop txt-color-red';
                            break;
                        
                    }

                
                
                
                
                ?>

				<tr class="warning">
					<td style="width: 50px;" class="text-center"><?php echo $_status_icon != '' ? '<a title="'.ucfirst($running['controller']).'" href="javascript:void(0);"><i class=" '.$_status_icon.'"></i></a>'  :  $running['controller'] ?></td>
						<td style="width: 50px;" class="text-center"><a href="javascript:void(0);" title="<?php echo ucfirst($running['status'])  ?>"><i class="<?php echo $_status?>"></i></a></td>
						<td><b><?php echo ucfirst($running['type']) ?></b> -  <i class="fa fa-clock-o"></i> <?php echo elapsed_time( $running['status'] == 'running' ?  $running['start_date'] : $running['finish_date']) ?> </td>
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
                        case 'create':
                            $_status_icon = 'fab-lg fab-fw icon-fab-print';
                            break;
                        case 'updates':
                            $_status_icon = 'fa fa-refresh';
                            break;
                        case 'objectmanager':
                            $_status_icon = 'fab-lg fab-fw icon-fab-manager';
                            break;
                        case 'settings':
                            $_status_icon = 'fa fa-cogs';
                            break;
                       	case 'maintenance':
                            $_status_icon = 'fa fa-wrench';
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
                         case 'stopped':
                            $_status = 'fa fa-stop txt-color-red';
                            break;
                        
                    }
                ?>
				<tr>
					<td style="width: 50px;" class="text-center"><?php echo $_status_icon != '' ? '<a title="'.ucfirst($task['controller']).'" href="javascript:void(0);"><i class=" '.$_status_icon.'"></i></a>'  :  $task['controller'] ?></td>
					<td style="width: 50px;" class="text-center"><a href="javascript:void(0);" title="<?php echo ucfirst($task['status'])  ?>"><i class="<?php echo $_status?>"></i></a></td>
					<td><b><?php echo ucfirst($task['type']) ?></b> -  <i class="fa fa-clock-o"></i> <?php echo elapsed_time( $task['status'] == 'running' ?  $task['start_date'] : $task['finish_date']) ?> </td>
				</tr>

				<?php endforeach; ?>

			</tbody>

		</table>
	</div>
	
	<?php else: ?>
	
	<?php endif; ?>

</div>