<div class="step-pane <?php echo  $_task && $_task_attributes->step == 1 ? 'active': '' ?> <?php echo  !$_task  ? 'active': '' ?>"  id="step1">
	<div class="row">
		<div class="col-sm-6">
			<h2 class="text-primary">Select scan mode</h2>
		</div>

	</div>


	<div class="row">
	
	<?php foreach($mode_list as $mode): ?>
	
		<?php $configuration = json_decode($mode->values) ?>
		<div class="col-sm-4">
		
			<div class="scan-mode  well well-sm text-center " data-id="<?php echo $mode->id; ?>" data-type="<?php echo $mode->name ?>" data-title="<?php echo $configuration->info->name ?>">
			
				<h4 class="page-title txt-color-blueDark"><?php echo $configuration->info->name ?></h4>
				<div class="row">
					<div class="text-align-center mode-image">
						<img class="img-responsive" style="display: inline; max-width: 50%;" src="<?php echo base_url() .'/application/modules/scan/assets/img/'.strtolower($configuration->info->name).'.png' ?>">
					</div>
                    <div class="mode-description" style="display:none;">
					   <p><?php echo $configuration->info->description ?></p>
                    </div>
				</div>
			
			</div>
		</div>
	
	
	<?php endforeach; ?>

	</div>

</div> 
