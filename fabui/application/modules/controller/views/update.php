<?php if($updated): ?>
<div class="alert alert-transparent">
	<h4 class="text-center">No update available</h4>
</div>
<?php else: ?>
	<ul class="notification-body">
		<?php if(!$updated): ?>
			<li>
				<span class="padding-10 unread">
					<em class=" padding-5 no-border-radius  pull-left margin-right-5 ">
						<i class="fa fa-tablet fa-2x "></i>
					</em>
					<span>
						<strong><a class="display-normal" href="#">FABUI <i class="font-xs txt-color-orangeDark">beta</i></a> <?php echo $remote_version ?> is out!</strong>
						<a href="<?php echo site_url("updates"); ?>" class="btn btn-xs btn-primary margin-top-5"><i class="fa fa-refresh"></i> Update now!</a>
					</span>
				</span>
			</li>
		<?php endif; ?>	
	</ul>
<?php endif; ?>
