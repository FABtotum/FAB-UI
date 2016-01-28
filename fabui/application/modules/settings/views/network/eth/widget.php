<div class="row">
	<div class="col-sm-12 col-lg-12 col-sx-12">
		<table class="table ">
			<tbody>
				<tr>
					<td style="border:0px;" width="200px;">IP Address</td>
					<td style="border:0px;"><a href="http://<?php echo $info['inet_address']; ?>" target="_blank"><?php echo $info['inet_address']; ?></a></td>
				</tr>
				<tr>
					<td>MAC Address</td>
					<td><strong><?php echo $info['mac_address']; ?></strong></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="row margin-top-10 margin-bottom-10">
	<div class="col-sm-12">
		<button class="btn btn-primary" id="new-ip-button"><i class="fa fa-angle-double-down"></i> Set new IP address</button>
		<div class="nets-table margin-top-10"></div> 
	</div>
</div>


<div class="row margin-top-10" id="new-ip-form-container" style="display:none;">
	
	<?php if($info['inet_address'] == $_SERVER['HTTP_HOST']): ?>
	
	<div class="col-sm-12">
		<div class="alert alert-info fade in animated fadeIn">
			<i class="fa-fw fa fa-info"></i> it is recommended to change the Ethernet IP address when you're connected to the FABtotum via WiFi
		</div>
	</div>
	
	<?php endif; ?>
	
	<div class="col-sm-12 col-sx-12">
		<form id="ip-form">
			<fieldset>
				<div class="input-group">
					<span class="input-group-addon">169.254.1</span>
					<input class="form-control" id="ip-num" name="ip-num" type="number" min="1" max="255" value="<?php echo explode('.', $info['inet_address'])[3] ?>">
				</div>
				<div class="row">
					<div class="col-sm-12"></div>
					
				</div>
			</fieldset>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary" type="button" id="save"><i class="fa fa-save"></i> Save new address</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>