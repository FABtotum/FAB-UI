<form class="smart-form" action="<?php echo site_url('objectmanager/edit/'.$_object->id) ?>" method="POST" >
	<fieldset>

		<div class="row">
			<section class="col col-8">
				<label class="label">Name</label>
				<label class="input">
					<input type="text" id="obj_name" name="obj_name" class="form-control" value="<?php echo $_object -> obj_name; ?>" />
				</label>
			</section>
			<section class="col col-4">
				<label rel="tooltip" data-placement="top" data-original-title="If is checked everyone can use this object" class="label">Public</label>
				<div class="inline-group">
					<label class="radio">
						<input type="radio" name="private" value="1" <?php echo $_object->private == 1 ? 'checked="checked"' : '' ?>
						> <i></i>Yes</label>
					<label class="radio">
						<input type="radio"  name="private" value="0" <?php echo $_object->private == 0 ? 'checked="checked"' : '' ?>
						> <i></i>No</label>
				</div>
			</section>
		</div>
		<section>
			<label class="label">Description</label>
			<label class="textarea"> 				<textarea rows="5" class="custom-scroll" id="obj_description" name="obj_description"><?php echo $_object -> obj_description; ?></textarea> </label>
			<div class="note">
				<strong>Note:</strong> If you set this object as public everyone can use it
			</div>
		</section>

	</fieldset>

	<footer>
		<button class="btn btn-primary" type="button" id="save-object"><i class="fa fa-save"></i> Save</button>
	</footer>
</form>