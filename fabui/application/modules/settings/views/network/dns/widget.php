<div class="row">
	<div class="col-sm-12">
		<form class="smart-form" id="hostname-form">
			
			<fieldset>
				<div class="row">
					<section class="col col-6">
						<label class="label">Name</label>
						<div class="input-group">
							<span class="input-group-addon">http://</span>
							<input style="padding-left:5px" value="<?php echo $current_hostname; ?>" placeholder="<?php echo $current_hostname; ?>" class="form-control" id="hostname" type="text">
							<span class="input-group-addon">.local</span>
						</div>
						<div class="note"></div>
					</section>
				</div>
				<div class="row">
					<section class="col col-6">
						<label class="label">Description</label>
						<label class="input">
							<input type="text" value="<?php echo $current_name; ?>" id="name"> 
						</label>
					</section>
				</div>
				<div class="row">
					<section class="col col-6">
						<div class="note">
						To easily access to the FABtotum Personal Fabricator by using the name you inserted you need to install on the device you are using to access it a multicast domain name system service discovery such as <abbr title="Bonjour">Bonjour</abbr> or similar
					</div>
					</section>
				</div>
			</fieldset>
			<footer>
				<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
			</footer>
		</form>
	</div>
</div>
<form action="<?php site_url('settings/network/hostname') ?>" id="response-form" method="post">
	<input type="hidden" id="response" name="response">
	<input type="hidden" id="new_hostname" name="new_hostname">
</form>