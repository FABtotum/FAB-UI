<div class="widget-body-toolbar">
	
	
	<div class="btn-group">
		<button class="btn btn-default" data-toggle="dropdown" id="date-picker">
			<i class="fa fa-calendar"></i> <span><?php echo  date('F j, Y', strtotime('today - 30 days')) .' - '.date('F j, Y', strtotime('today')) ?></span> <span class="caret"></span>
		</button>
	</div>
	<div class="btn-group">
		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span id="ajax-type">Make</span> <span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li>
				<a data-type="type"  data-value="print" href="javascript:void(0);"><i class="icon-fab-print"></i> Print</a>
			</li>
			<li>
				<a  data-type="type" data-value="mill" href="javascript:void(0);"><i class="icon-fab-mill"></i> Mill</a>
			</li>
			<li>
				<a  data-type="type" data-value="laser" href="javascript:void(0);"><i class="icon-fab-mill"></i> Laser</a>
			</li>
			<li>
				<a  data-type="type" data-value="scan" href="javascript:void(0);"><i class="icon-fab-scan"></i> Scan</a>
			</li>
			<li class="divider"></li>
			<li>
				<a  data-type="type" data-value="" href="javascript:void(0);"> Make</a>
			</li>
		</ul>
	</div>
	<div class="btn-group">
	
		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<span id="ajax-status">Status</span> <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li>
				<a  data-type="status" data-value="performed" href="javascript:void(0);">Completed</a>
			</li>
			<li>
				<a  data-type="status" data-value="stopped" href="javascript:void(0);">Aborted</a>
			</li>
			<li>
				<a  data-type="status" data-value="deleted" href="javascript:void(0);">Stopped</a>
			</li>
			<li>
				<a  data-type="status" data-value="error" href="javascript:void(0);">Error</a>
			</li>
			<li class="divider"></li>
			<li>
				<a  data-type="status" data-value="" href="javascript:void(0);"> Status</a>
			</li>
		</ul>
	</div>
		
</div>

<ul id="myTab1" class="nav nav-tabs tabs-pull-right">
	<li class="active">
		<a href="#s1" data-toggle="tab"><i class="fa fa-list"></i> Tasks</a>
	</li>
	<li>
		<a id="stats-click" href="#s2" data-toggle="tab"><i class="fa fa-area-chart"></i> Stats</a>
	</li>
</ul>

<div id="myTabContent1" class="tab-content">
	<div class="tab-pane fade in active" id="s1">
		<table class="table table-bordered table-striped" id="history">
			<thead>
				<tr>
					<th></th>
					<th><i class="fa fa-calendar"></i> <span class="hidden-xs">When</span></th>
					<th><i class="fa fa-play fa-rotate-90 txt-color-blue"></i> <span class="hidden-xs">Make</span></th>
					<th>Status</th>
					<th>Description</th>
					<th><i class="fa fa-clock-o"></i> <span class="hidden-xs">Duration</span></th>
					<th class="hidden"></th>
					<th class="hidden"></th>
					<th class="hidden"></th>
					<th class="hidden"></th>
					<th class="hidden"></th>
					<th class="hidden"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	
	<div class="tab-pane fade in padding-10" id="s2">
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		
	</div>
</div>

				