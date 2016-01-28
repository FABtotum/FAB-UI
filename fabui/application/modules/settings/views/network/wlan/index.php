<?php if(isset($message)): ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="alert <?php echo $message['type'] ?> alert-block animated  bounce">
				<?php echo $message['text'] ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if($info['ip_address'] == $_SERVER['HTTP_HOST']): ?>

<div class="row">
	<div class="col-sm-12">
		<div class="alert alert-info fade in animated fadeIn">
			<i class="fa-fw fa fa-info"></i> it is recommended to connect to a WiFi network when you're connected to the FABtotum via ethernet cable
		</div>
	</div>
</div>
<?php endif; ?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $widget; ?>
        </article>
    </div>
</section>
<div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i> Password for <span class="password-modal-title"></span></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password</label>
							<input id="modal-password-input" type="password" class="form-control password-input" />
						</div>
						<div class="form-group">
							<label class="checkbox-inline">
								  <input type="checkbox" class="checkbox password" id="show-password">
								  <span>Show password</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" id="confirm-password" class="btn btn-primary"><i class="fa fa-check"></i> Confirm password</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- -->
<div class="modal fade" id="hidden-wifi-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-user-secret"></i> Connect to a hidden WiFi <i class="fa fa-wifi"></i></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>WiFi Name</label>
							<input id="hidden-ssid-input" type="text" class="form-control" />
							<p class="note">*Required</p>
						</div>
						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Password</label>
							<input id="hidden-password-input" type="password" class="form-control password-input" />
						</div>
						<div class="form-group">
							<label class="checkbox-inline">
								  <input type="checkbox" class="checkbox password" id="show-password">
								  <span>Show password</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" id="hidden-connect" class="btn btn-primary"><i class="fa fa-check"></i> Connect</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->