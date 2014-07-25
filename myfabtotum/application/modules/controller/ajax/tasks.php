<?php
require_once '/var/www/myfabtotum/script/config.php';
require_once '/var/www/myfabtotum/ajax/lib/database.php';


/** LOAD DB */
$db = new Database();
/** GET ALL RUNNING TASKS */
$_tasks = $db->query('select * from sys_tasks where status="running"');
/** CLOSE DB CONNECTION */
$db->close();


$_tasks_number = count($_tasks);


if($_tasks_number == 0){
?>    
<div class="alert alert-transparent">
    <h4>Click a button to show messages here</h4>
    This blank page message helps protect your privacy, or you can show the first message here automatically.
</div>
<i class="fa fa-lock fa-4x fa-border"></i>
<?    
}else{
?>
<ul class="notification-body">

    <?php foreach($_tasks as $_task): ?>
    <?php 
        $_task_attributes = json_decode($_task['attributes'], TRUE);
        $_monitor         = json_decode(file_get_contents($_task_attributes['monitor']), TRUE);
        
        //print_r($_task_attributes);
        
        $_icon = $_task['type'] == 'print' ? 'icon-fab-print' : 'icon-fab-scan';
    
    ?>
    
	<li>
		<span>
			<div class="bar-holder no-padding">
				<p class="margin-bottom-5"><i class="<?php echo $_icon; ?>"></i> <strong><?php $_task['type'] ?></strong>  <span class="pull-right semi-bold text-muted"><?php echo number_format($_monitor['print']['stats']['percent'] , 2, ',', ''); ?> %</span></p>
				<div class="progress progress-md progress-striped">
					<div class="progress-bar bg-color-teal"  style="width: <?php echo number_format($_monitor['print']['stats']['percent'] , 2, '.', ''); ?>%;"></div>
				</div>
				<em class="note no-margin">last updated on <?php echo date("d/m/Y G:i:s") ?></em>
			</div>
		</span>
	</li>
    
    <?php endforeach; ?>
</ul>


<?
}


//$_response_items['number'] = $_tasks_number;

//$_json = json_encode($_response_items);

//file_put_contents('/var/www/temp/notifications.json', $_json, FILE_USE_INCLUDE_PATH);


//echo $_json;
//echo "<<<<<<<<<<<<<<<<<<<<<<";
    
    
?>