<ul class="notification-body">
	<?php foreach($update_list as $update): ?>
	<li>
		<span class="padding-10 unread">
			<em class="badge padding-5 no-border-radius bg-color-blueLight pull-left margin-right-5">
				<i class="fa fa-refresh fa-fw fa-2x"></i>
			</em>
			<span>
				 <?php echo $update['description'] ?> - <a href="<?php echo $update['url'] ?>" class="display-normal">detail</a> 
			</span>
		</span>
	</li>
	<?php endforeach; ?>
</ul>