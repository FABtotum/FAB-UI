<?php if(count($stats) <= 0): ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="alert alert-info animated fadeIn">
				<i class="fa-fw fa fa-info"></i>
				<strong>Info!</strong> No data available
			</div>
		</div>
	</div>
<?php else: 

$total_time = 0;
$temp_times = array();
$div_totals = 0;

//print_r($stats);
foreach($stats as $key_stat => $value_stat){
	
	foreach($value_stat as $status => $tot){
		
		if($status == 'total_time' && ($tot != '0')){
			array_push($temp_times, $tot);
		}
	}
	
	if(array_key_exists($key_stat,$type_options) && (array_sum($value_stat) > 0)) $div_totals +=1;
	
}

$total_time = sumTimes($temp_times);

$total_time_seconds = time_to_seconds($total_time);


?><!-- CONTENITORE -->	
<div class="row">		
<?php foreach($stats as $key_stat => $value_stat):
	  
	
	$total  = 0;
	$temp   = array();
	$colors = array();
		
	foreach($value_stat as $status => $tot){
		
		if(array_key_exists($status,$status_options)){			
			$total += $tot;
		}
	}
	
	foreach($value_stat as $status => $tot){
		if(array_key_exists($status,$status_options)){	
			$temp[] = array('value' =>@number_format((($tot/$total)*100),  1, '.', ' '), 'label' => $status_options[$status]);
			array_push($colors, $status_colors[$status]);
		}
	}
	
	
	if(array_key_exists($key_stat,$type_options) && (array_sum($value_stat) > 0)): 

?>
	
	<div class="col-sm-<?php echo (12/$div_totals).' '.$div_totals; ?> margin-bottom-10" >
		
		<div class="row">
			<div class="col-sm-12">
				<h4><i class="<?php echo $icons[$key_stat] ?>"></i> <?php echo $type_options[$key_stat]; ?> <span class="pull-right"><?php echo $total; ?> times</span></h4>
				<hr class="simple">
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div id="donut-<?php echo $key_stat; ?>" class="chart no-padding"></div>
			</div>
			<div class="col-sm-6 show-stats">
					<?php foreach($value_stat as $status => $tot): ?>
						<div class="row">
						<?php if(array_key_exists($status,$status_options)): ?>
						<div class="col-sm-12">
							<span class="text"><?php echo $stats_label[$status] ?> 
								<span class="pull-right"><?php echo $tot ?>/<?php echo $total; ?> </span>
							</span>
							<div class="progress">
								<div class="progress-bar" style="width: <?php echo @(($tot/$total)*100) ?>%; background-color:<?php echo $status_colors[$status] ?> !important;"></div>
							</div>
						</div>
						<?php elseif($status == 'total_time'):?>
							<div class="col-sm-12">
								<span class="text">Total Time 
									<span class="pull-right"><?php echo $tot ?></span>
								</span>
								<div class="progress">
									<div class="progress-bar" data-attr="<?php echo time_to_seconds($tot) ?>" style="width: <?php echo @((time_to_seconds($tot)/$total_time_seconds)*100) ?>%;"></div>
								</div>
							</div>
						<?php endif; ?>
						</div>
					<?php endforeach; ?>
				
			</div>
		</div>
		
		<script type="text/javascript">
			var data_<?php echo $key_stat; ?> = <?php echo json_encode($temp, JSON_NUMERIC_CHECK) ?>;
			Morris.Donut({
				element : 'donut-<?php echo $key_stat; ?>',
				data : data_<?php echo $key_stat; ?>,
				colors: <?php echo json_encode($colors); ?>,
				formatter : function(x) {
					return x + "%"
				},
				resize: true
			});

		</script>
	</div>
	
	<?php endif; ?>
	
<?php endforeach; ?>
</div>

<?php endif; ?>	
	 
