<form class="smart-form" action="<?php echo site_url('objectmanager/add') ?>" method="POST" id="object-form">
	<fieldset>
		<input name="authenticity_token" type="hidden">
		<div class="row">
			<section class="col col-8">
				<label class="label">Name</label>
				<label class="input">
					<input type="text" id="name" name="name" class="form-control" />
				</label>
			</section>
			<section class="col col-4">
				<label rel="tooltip" data-placement="top" data-original-title="If is checked everyone can use this object" class="label">Public</label>
				<div class="inline-group">
					<label class="radio">
						<input type="radio" name="private" value="1" checked="checked">
						<i></i>Yes </label>
					<label class="radio">
						<input type="radio"  name="private" value="0">
						<i></i>No </label>
				</div>
			</section>
		</div>
		<section>
			<label class="label">Description</label>
			<label class="textarea"> 				<textarea rows="5" class="custom-scroll" id="description" name="description"></textarea> </label>
			<div class="note">
				<strong>Note:</strong> If you set this object as public everyone can use it
			</div>
		</section>
		<input type="hidden" id="files" name="files">
		<input type="hidden" id="usb_files" name="usb_files">
	</fieldset>



</form>