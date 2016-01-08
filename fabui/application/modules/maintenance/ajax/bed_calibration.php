<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';


/** CREATE LOG FILES */
$_time                 = $_POST['time'];
$_num_probes           = isset($_POST['num_probes']) ? $_POST['num_probes'] : 4;
$_skip_homing          = isset($_POST['skip_homing']) ? $_POST['skip_homing'] : 0;
$_destination_trace    = TEMP_PATH.'macro_trace';
$_destination_response = TEMP_PATH.'macro_response';

write_file($_destination_trace, '', 'w');
//chmod($_destination_trace, 0777);

write_file($_destination_response, '', 'w');
//chmod($_destination_response, 0777);

/** WAIT JUST 1 SECOND */
sleep(1);

/** EXEC COMMAND */
$h_over = 38;
//$num_probes=2; #num probes to execute for each point
//$skip_homing=1; #set 1 to skip homing the second time

$_command = 'sudo python ' . PYTHON_PATH . 'manual_bed_lev.py ' . $_destination_response . ' ' . $_destination_trace . ' ' . $h_over . ' ' . $_num_probes . ' ' . $_skip_homing;

$_output_command = shell_exec($_command);

/** WAIT JUST 1 SECOND */
sleep(1);

$_response = json_decode(file_get_contents($_destination_response), TRUE);

$screws = array();

$greens = 0;

$screws[0] = array('t' => $_response['bed_calibration']['t1'], 's' => $_response['bed_calibration']['s1']);
$screws[1] = array('t' => $_response['bed_calibration']['t2'], 's' => $_response['bed_calibration']['s2']);
$screws[2] = array('t' => $_response['bed_calibration']['t3'], 's' => $_response['bed_calibration']['s3']);
$screws[3] = array('t' => $_response['bed_calibration']['t4'], 's' => $_response['bed_calibration']['s4']);
?>


<table class="table table-hover screws-rows">
	
	<thead>
		<tr>
			<th class="text-center">Screw</th>
			<th class="text-center">Instructions</th>
		</tr>
	</thead>
		
	<tbody>
	<?php for($i=0; $i<4; $i++): ?>
		
		<tr class="<?php echo  get_row_color($screws[$i]['s'])?>">
			<td class="text-center"><span class="badge  badge <?php echo get_color($screws[$i]['s']); ?>"><?php echo($i + 1); ?></span></td>
			<td><strong><?php echo get_rotation_number($screws[$i]['t']); ?> <?php echo get_direction($screws[$i]['s']) ?></strong></td>
		</tr>
	<?php endfor; ?>	
	</tbody> 
</table>


<?php if($greens == 4): ?>
	
	
	<div class="alert alert-success alert-block">
		
		<h4 class="alert-heading"><i class="fa fa-check"></i> Success!</h4>
		The bed is well calibrated to print
	</div>

	
	
<?php endif; ?>

<?

function get_row_color($value) {

	$value = abs(floatval($value));

	if ($value > 0.2) {
		return 'danger';
	}

	if (($value <= 0.2) && ($value > 0.1)) {
		return 'warning';
	}

	if ($value <= 0.1) {
		return 'success';
	}

}

function get_color($value) {

	global $greens;

	$value = abs(floatval($value));

	if ($value > 0.2) {
		return 'bg-color-red';
	}

	if (($value <= 0.2) && ($value > 0.1)) {
		return 'bg-color-orange';
	}

	if ($value <= 0.1) {
		$greens++;
		return 'bg-color-green';
	}
}

// - senso orario
// + senso antioario

function get_direction($value) {


	$class = '';
	
	if ($value > 0) {
		$class = 'fa-rotate-right';
	} else {
		$class = 'fa-rotate-left';
	}
	
	return '- Direction:  <i class="fa '.$class.'"></i>';

}


function get_rotation_number($value){
			
	$value = abs(floatval($value));
	
	$temp = explode('.', $value);
	
	$turns_number = $temp[0];
	
	$degrees = round((floatval(floatval('0.'.$temp[1]) * 360)));
	
	
	if($turns_number >= 1){
		
		$label_time = $turns_number > 1 ? 'times' : 'time';
		
		$message = "Turn ".$turns_number." ".$label_time;
		
		if($degrees > 0){
			
			$message .= ' and '.$degrees.' degrees';
			
		}
		
				
	}else{
		
		
		$message = 'Turn for ' . $degrees . ' degrees';
		
		if($degrees == 0){
		
			$message = '';
				
		}
		
	}
	
	return $message;
	
}
?>