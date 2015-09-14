<?php 

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/utilities.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/lib/database.php';



$start = $_REQUEST['start'];
$end   = $_REQUEST['end'];
$mode  = $_REQUEST['mode'];

$db    = new Database();

$lasts = $db->query('SELECT * FROM (`sys_tasks`) WHERE `status` != "running" AND `user` = '.$_SESSION['user']['id'].' ORDER BY `start_date` desc LIMIT '.$start.','.$end);


$num_rows = $db->get_num_rows();


if($num_rows == 0){
	exit('');
}

if(!isset($lasts[0])){
	$temp = array();
	$temp[0] = $lasts;
	$lasts = $temp;
}


$totalLasts = count($lasts);

$countLasts = 0;

?>

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
				<a href="javascript:void(0);"><?php echo getTaskTitle($task['controller'], $task['controller']); ?></a>
			</h6>
			
			<p></p>
			
			<ul class="list-inline">
				<li>
					<i class="fa fa-calendar"></i>
					<a href="javascript:void(0);"> <?php echo date('F j, Y, g:i a', strtotime($task['finish_date'])); ?> </a>
				</li>
			</ul>
		</div>
		
		
		<div class="col-sx-12 col-sm-12"><hr></div>
		
		
	<?php endforeach; ?>
	



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
			$icon = 'icon-fab-print fab-lg fab-fw  ';
			$title = 'Create';
			break;
		case 'scan':
			$icon = 'icon-fab-scan fab-lg fab-fw  ';
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

