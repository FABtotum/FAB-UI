<div class="row">
	<div class="col-sm-4">
		<div class="well layout" id="layout-1">
			<div class="row ">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td colspan="2" class="text-center">
									1
								</td>
							</tr>
							<tr>
								<td class="text-center">
									2
								</td>
								<td class="text-center">
									3
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="well layout" id="layout-2">
			<div class="row">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td colspan="3" class="text-center">
								1
							</td>
						</tr>
						<tr>
							<td class="text-center">
								2
							</td>
							<td class="text-center">
								3
							</td>
							<td class="text-center">
								4
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="well layout" id="layout-3">
			<div class="row">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td class="text-center">
								1
							</td>
						</tr>
						<tr>
							<td class="text-center">
								2
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<h6>
			Widgets
		</h6>
		<div class="well">
			<ol class="wid" id="nestable1">
				<?php $count=1; ?>
					<?php foreach($_widgets as $key=>
						$value): ?>
						<?php $widget_info=widget_info($key); ?>
							<li class="item" data-name="<?php echo $key ?>" data-id="<?php echo $key ?>">
								<?php echo $widget_info[ 'name'] ?>
							</li>
							<?php $count++; ?>
								<?php endforeach; ?>
			</ol>
		</div>
	</div>
	<div class="col-sm-8">
		<h6>
			Layout
		</h6>
		<div class="col-row">
			<div class="col-sm-12" id="group-1">
				<div class="well">
					<ol class="wid" id="nestable2">
					</ol>
				</div>
			</div>
		</div>
		<div class="col-row">
			<div class="col-sm-6" id="group-2">
				<div class="well">
					<ol class="wid" id="nestable3">
					</ol>
				</div>
			</div>
			<div class="col-sm-6" id="group-3">
				<div class="well">
					<ol class="wid" id="nestable4">
					</ol>
				</div>
			</div>
			<div class="col-sm-6" id="group-4" style="display: none;">
				<div class="well">
					<ol class="wid" id="nestable5">
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>