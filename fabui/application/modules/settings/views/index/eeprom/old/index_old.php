<div class="tab-pane animate fadeIn fade in active" id="tab2">
	<div class="row padding-10">
		<div class="smart-form">
			<header>EEPROM Overrides</header>
			<fieldset id="fieldset">
				<?php $count = 0; ?>
				<?php foreach($eeprom_lines as $line): ?>
					<section class="eeprom_comamnd">
						<label class="label comment_<?php echo $count; ?>"><?php echo $line['comment']; ?></label>
						<label class="input">
							<input type="text" name="command_<?php echo $count; ?>" id="command_<?php echo $count; ?>" value="<?php echo $line['command']; ?>">
						</label>
					</section>
				<?php $count++ ?>
				<?php endforeach; ?>
			</fieldset>
			<footer>
				<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
				<button type="button" id="default" class="btn btn-primary"> Restore</button>
			</footer>
		</div>
	</div>
</div>