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
write_file($_destination_response, '', 'w');

/** WAIT JUST 1 SECOND */
sleep(1);

/** EXEC COMMAND */
$h_over = 38;

$_command = 'sudo python ' . PYTHON_PATH . 'manual_bed_lev.py ' . $_destination_response . ' ' . $_destination_trace . ' ' . $h_over . ' ' . $_num_probes . ' ' . $_skip_homing;

$_output_command = shell_exec($_command);

/** WAIT JUST 1 SECOND */
sleep(1);
$_response = json_decode(file_get_contents($_destination_response), TRUE);

if(is_array($_response)){ // if there is a valid response

	$screws = array();	
	$greens = 0;
	
	$screws[0] = array('t' => $_response['bed_calibration']['t1'], 's' => $_response['bed_calibration']['s1']);
	$screws[1] = array('t' => $_response['bed_calibration']['t2'], 's' => $_response['bed_calibration']['s2']);
	$screws[2] = array('t' => $_response['bed_calibration']['t3'], 's' => $_response['bed_calibration']['s3']);
	$screws[3] = array('t' => $_response['bed_calibration']['t4'], 's' => $_response['bed_calibration']['s4']);
	
	$elaboreted_screws = array();

	foreach($screws as $screw){
		array_push($elaboreted_screws, elaborate_screw($screw));
		if(isset($screw['color']))
			if($screw['color'] == 'green'){
				$greens++;
			}	
	}
	
?>
	<h4 class="text-center">
	Screw or unscrew following the indication given for each point.<br>
	Green points are optimally leveled.<br>
	Always follow the order without skipping any point (even green ones) and repeat until all the points are green. Arrows show the direction (CW or CCW) as seen from above.
	</h4>
	<hr class="simple">
	<div class="margin-top-10">
		<table class="table table-hover screws-rows">
			<thead>
				<tr>
					<th class="text-center">Screw</th>
					<th class="text-center">Instructions</th>
				</tr>
			</thead>
			<tbody><?php echo get_results($elaboreted_screws); ?></tbody> 
		</table>
	</div>
	<hr class="simple">
	<?php if($greens == 4): ?>
		<div class="alert alert-success alert-block">
			
			<h4 class="alert-heading"><i class="fa fa-check"></i> Well done!</h4>
			The bed is well calibrated to print
		</div>
	<?php endif; ?>
	
<?php	
}else{ //else there is something wrong need to do it again

 echo '<h4 class="text-center">
		Well this is embarassing, during measurements something went wrong. Try again
	</h4>';
}

function elaborate_screw($data){
	
	$t_data = $data['t'];
	$s_data = $data['s'];
	
	$t_value = abs(floatval($t_data));
	$t_exploded = explode('.', $t_value);
	
	$units    = $t_exploded[0];
	$decimals = $t_exploded[1];
	
	$turns_number = $units;
	$degrees      = roundUpToAny(round((floatval(floatval('0.'.$decimals) * 360))));
	
	$direction   = $s_data  > 0 ? 'right' : 'left';
	return array('t_value' =>$t_value, 'turns'=>array('times'=>$turns_number, 'degrees'=>$degrees), 'direction'=>$direction, 'color'=>get_color($s_data));
	
}

function get_color($value){
	
	global $greens;
	
	$value = abs(floatval($value));

	if ($value > 0.2) {
		return 'red';
	}

	if (($value <= 0.2) && ($value > 0.1)) {
		return 'orange';
	}

	if ($value <= 0.1) {
		$greens++;
		return 'green';
	}
}


function get_results($data){
	
	$trs = '';
	$counter = 1;
	
	foreach($data as $screw){

		$trs .= '<tr class="'.get_row_color($screw['color']).'">';
		
		$trs .= '<td class="text-center"><span class="badge bg-color-'.$screw['color'].'">'.$counter.'</span></td>';
		$trs .= '<td><strong>'.get_instructions($screw).'</strong></td>';
		
		$trs .= '</tr>';
		
		$counter ++;
	}
	return $trs;
}

function get_row_color($value){
	switch($value){
		case 'red':
			return 'danger';
			break;
		case 'orange':
			return 'warning';
			break;
		case 'green':
			return 'success';
			break;
	}
}

function get_instructions($screw){
	
	if($screw['t_value'] < 0.1){
		return '<i class="fa fa-check"></i> Well done';
	}
	
	
	$rotation_section = 'following this rotation sense <i class="fa fa-rotate-'.$screw['direction'].'"></i>';
	
	if($screw['turns']['times'] < 1 ){
		return 'Turn for '.$screw['turns']['degrees'].' degrees, '.$rotation_section;
	}
	
	if($screw['turns']['times'] > 0){
		$times_label = $screw['turns']['times'] == 1 ? 'time' : 'times' ;
		$degrees_section = '';
		if($screw['turns']['degrees'] > 0){
			$degrees_section =  ' and '.$screw['turns']['degrees'].' degrees';
		}	
		return 'Turn for '.$screw['turns']['times'].' '.$times_label.$degrees_section.' '.$rotation_section;
	}
}

function roundUpToAny($n,$x=5) {
    return round(($n+$x/2)/$x)*$x;
}
?>