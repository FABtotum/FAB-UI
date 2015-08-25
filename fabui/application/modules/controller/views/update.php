<?php if(!$fabui_update && !$fw_update): ?>

<div class="alert alert-transparent">
	<h4 class="text-center">No update available</h4>
</div>


<?php else: ?>


	<ul class="notification-body">
		
		<?php if($fabui_update): ?>
			<li>
				<span class="padding-10 unread">
					<em class=" padding-5 no-border-radius  pull-left margin-right-5 ">
						<i class="fa fa-tablet fa-2x "></i>
					</em>
					<span>
						<strong><a class="display-normal" href="#">FAB UI <i class="font-xs txt-color-orangeDark">beta</i></a> <?php echo $fabui_remote ?> is out!</strong>
						<a href="<?php echo site_url("updates"); ?>" class="btn btn-xs btn-primary margin-top-5">update</a>
					</span>
				</span>
			</li>
			
		<?php endif; ?>
		
		
		
		<?php if($fw_update): ?>
			
			
			<li>
				<span class="padding-10 unread">
					<em class=" padding-5 no-border-radius  pull-left margin-right-5 ">
						<i class="fa fa-shield txt-color-purple  fa-2x text-muted"></i>
					</em>
					<span>
						<strong><a class="display-normal" href="#">FABlin Firmware </a> <?php echo $fw_remote ?> is out!</strong>
						<a href="<?php echo site_url("updates"); ?>" class="btn btn-xs btn-primary margin-top-5">update</a>
					</span>
				</span>
			</li>
			
			
		<?php endif; ?>
		
	</ul>

<?php endif; ?>
