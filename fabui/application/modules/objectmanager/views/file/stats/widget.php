<div class="widget-body-toolbar bg-color-white">
	<div class="row">
		<div class="col-sm-6">	
		<?php if(count($durations) <= 0): ?>
			<div class="alert alert-info animated fadeIn notification"><i class="fa-fw fa fa-info"></i> No data available </div>
		<?php endif; ?>
		</div>
		<div class="col-sm-6">
			<button class="btn btn-default pull-right" data-toggle="dropdown" id="date-picker">
				<i class="fa fa-calendar"></i> <span><?php echo  date('F j, Y', strtotime('today - 30 days')) .' - '.date('F j, Y', strtotime('today')) ?></span> <span class="caret"></span>
			</button>
		</div>
	</div>
</div>
<div class="row" id="graphs-container" style="<?php echo (count($durations) <= 0) ? 'display:none;' : ''; ?>">
	<div class="col-sm-8">
		<!-- GRAPH -->
		<div id="non-continu-graph" class="chart no-padding"></div>
		<!-- TABLE -->
		<table id="table-list" class="table  table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th>When</th>
					<th>Status</th>
					<th>Duration</th>
					<th class="hidden"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	
	<div class="col-sm-4 ">
		<div class="row">
			<div class="col-sm-12">
				<h5><?php echo $file->print_type == 'additive' ? 'Prints' : 'Milling'; ?> attempts <span class="pull-right total-tasks"><?php echo array_sum($totals) ?></span></h5>
				<h5>Duration <span class="pull-right total-duration"><?php echo $total_durations; ?></span></h5>
				<hr class="simple">
			</div>
		</div>
		<!-- DONUT -->
		<div id="donut-graph" class="chart no-padding"></div>
		<!-- PROGRESS BAR -->
		<div class="col-sm-12 show-stats">
			<div class="row ">
				<?php foreach($status_keys as $status): ?>
					<?php if(isset($totals[$status])): ?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<span class="text"><?php echo $options[$status]['label'] ?> <span class="pull-right"><?php echo $totals[$status] ?> / <?php echo array_sum($totals) ?></span></span>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<span class="text"> <span class="pull-right"><?php echo sumTimes($durations[$status]) ?> / <?php echo $total_durations ?></span></span>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="progress">
							<div class="progress-bar" style="width:<?php echo ($totals[$status]/array_sum($totals))*100 ?>%; background-color: <?php echo $options[$status]['color'] ?> !important;"></div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach;?>
			</div>
		</div>
		
	</div>
</div>