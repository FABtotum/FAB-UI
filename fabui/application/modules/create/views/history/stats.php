<?php foreach($stats as $key_stat => $value_stat): ?>
	
	<?php if($key_stat != ''): ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-12 "><h6 class="text-primary"><i class="<?php echo $icons[$key_stat] ?>"></i> <?php echo $type_options[$key_stat] ?></h6></div>
			
			
			<?php foreach($value_stat as $key => $value): ?>
				<div class="col-sm-12 ">
					<span class="text"><?php echo $stats_label[$key]?> <span class="pull-right"><?php echo $value ?></span></span>
				</div>
			<?php endforeach; ?>
			
		</div>
	</div>
	<div class="col-sm-12">
					<hr class="simple">
				</div>
	<?php endif; ?>
	
	
	
<?php endforeach; ?>
	
	 
