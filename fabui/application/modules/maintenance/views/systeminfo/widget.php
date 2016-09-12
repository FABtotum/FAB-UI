<div class="row">
	<div class="col-sm-6 margin-bottom-10">
		<h1 class="txt-color-blueDark"><i class="fa fa-play fa-rotate-90 fa-border"></i> FABtotum Personal Fabricator</h1>
	</div>
	<div class="col-sm-6 margin-bottom-10">
		<div class="well no-padding well-light">
			<table class="table table-striped table-condensed">
				<caption></caption>
				<tbody>
					<tr>
						<td>OS</td>
						<td><span class="pull-right"><?php echo $os_info; ?></span></td>
					</tr>
					<tr>
						<td>Firmware</td>
						<td><span class="pull-right">v. <?php echo $fabtotum_info['fw']; ?></span></td>
					</tr>
					<tr>
						<td>FabUI</td>
						<td><span class="pull-right">v. <?php echo $_SESSION['fabui_version'] ?></span></td>
					</tr>
					<tr>
						<td>Hardware</td>
						<td><span class="pull-right">v. <?php echo $fabtotum_info['hw']; ?></span></td>
					</tr>
					<tr>
						<td>Installed Head</td>
						<td><span class="pull-right"><?php echo $unit_configs['hardware']['head']['description']; ?></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<hr class="simple">
<div class="row">
	<div class="col-sm-6 margin-bottom-10">
		<h1 class="txt-color-blueDark">Raspberry Pi <small>Board details</small></h1>
	</div>
	<div class="col-sm-6 margin-bottom-10">
		<div class="well no-padding well-light">
			<table class="table table-striped table-condensed">
				<caption>Memory</caption>
				<tbody>
					<tr>
						<td>Free</td>
						<td><span class="pull-right"><?php echo floor($mem_free / 1024); ?> MB</span></td>
					</tr>
					<tr>
						<td>Total</td>
						<td><span class="pull-right"><?php echo floor($mem_total / 1000); ?> MB</span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6 margin-bottom-10">
		<div class="well no-padding well-light">
			<table class="table table-striped">
				<caption>Hardware</caption>
				<tbody>
					<tr>
						<td>Time Alive</td>
						<td><span class="pull-right"><?php echo $time_alive?></span></td>
					</tr>
					<tr>
						<td>Board Temperature</td>
						<td><span class="pull-right"><?php echo $temp . '&deg;'; ?></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-6 margin-bottom-10">
		<div class="well no-padding well-light">
			<table class="table table-striped">
				<caption>Network <span class="pull-right" style="margin-right: 5px;">(eth-wlan)</span></caption>
				<tbody>
					<tr>
						<td>Down</td>
						<td><span class="pull-right"><?php echo pretty_baud($eth_rates[0])?> - <?php echo pretty_baud($wlan_rates[0])?></span></td>
					</tr>
					<tr>
						<td>Up</td>
						<td><span class="pull-right"><?php echo pretty_baud($eth_rates[1])?> - <?php echo pretty_baud($wlan_rates[1])?></span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 margin-bottom-10">
		<div class="well no-padding well-light">
			<table class="table table-striped">
				<caption>Storage</caption>
				<thead>
					<tr>
					<?php $col_count = 0; ?>
					<?php foreach($table_header as $header): ?>
						<?php if($header != ''): ?>
						<?php

						switch($col_count) {
							case 4 :
								$class = 'text-center';
								break;
							case 5 :
								$class = 'text-right';
								break;
							default :
								$class = '';
						}
						?>
						<th class="<?php echo $class; ?> th-border-top"><?php echo $header; ?></th>
						<?php $col_count++; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach($table_rows as $row): ?>
						<tr>
							<?php $items = explode(' ', $row); ?>
							<?php $col_count = 0; ?>
							<?php foreach($items as $item): ?>
								<?php if($item!==""): ?>
									
									<?php
									switch($col_count) {
										case 4 :
											$class = 'text-center';
											$content = '<div class="progress"><div class="progress-bar bg-color-blue" data-transitiongoal="' . intval($item) . '" style=""></div></div>';
											break;
										case 5 :
											$class = 'text-right';
											$content = $item;
											break;
										default :
											$class = '';
											$content = $item;
									}
									?>
																
									<td class="<?php echo $class; ?>"><?php echo $content; ?></td>
									<?php $col_count++; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>



