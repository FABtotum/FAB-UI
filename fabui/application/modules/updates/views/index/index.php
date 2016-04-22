<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="text-center">
				<h1 class="tada animated font-xl ">
					<span style="position: relative;">
						<i class="fa fa-play fa-rotate-90 fa-border fa-4x"></i>
						<span>
							<?php if($updated): ?>
								<b style="position:absolute; right: -30px; top:-10" class="badge bg-color-green font-md"><i class="fa fa-check txt-color-black"></i> </b>
							<?php else: ?>
								<b style="position:absolute; right: -30px; top:-10" class="badge bg-color-red font-md"><i class="fa fa-refresh error"></i></b>
							<?php endif; ?>
						</span>
					</span>
				</h1>
				<?php if($updated): ?>
					<h2 class="font-xl"><strong>Great! Your FABtotum Personal Fabricator is up to date</strong></h2>
				<?php else: ?>
					<h2 class="font-xl title"><strong> New important software updates are now available</strong></h2>
					<button id="update" class="btn btn-lg bg-color-red txt-color-white">Update now!</button>
					<p class="lead semi-bold">
						<small class="off-message hidden">Please don't turn off the printer until the operation is completed</small>
					</p>
					<button data-toggle="modal" data-backdrop="static" data-target="#modal" class="btn btn-xs bg-color-blue txt-color-white " style="">&nbsp;See what's new!</button>
					
				<?php endif; ?>
				
			</div>
		</div>
		<?php if(!$updated): ?>
			
			
		<div class="row">
			<div class="col-sm-12">
				<div class="text-center margin-top-10">
					<div class="well mini">
						<p class="text-left">
							<span class="download-info">Downloading update files</span> <span class="pull-right"> <span class="percent"></span> </span>
						</p>
						<div class="progress">
							<div class="progress progress-striped">
								<div class="progress-bar download-progress bg-color-blue" role="progressbar" style="width: 0%"></div>
							</div>
						</div>
					</div>
					<button id="cancel" class="btn btn-lg bg-color-red txt-color-white"> Cancel</button>
				</div>
			</div>
			
		</div>
		<?php endif; ?>
	</div>
</div>
<?php if(!$updated): ?>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">FABUI v.<?php echo $remote_version; ?> Changelog</h4>
			</div>
			<div class="modal-body no-padding">
				<?php echo fabui_changelog($remote_version) ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<?php endif; ?>