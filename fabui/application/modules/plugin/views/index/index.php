<div class="row">
	<!-- col -->
	<!--<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<h1 class="page-title txt-color-blueDark">
			<i class="fab-fw icon-fab-plugin"></i> Plugins </span>
		</h1>
	</div>-->
    <div class="col-xs-12 col-sm-6 col-md-12 col-lg-12 text-align-right">
        <div class="page-title">
            <a href="<?php echo site_url('plugin/upload'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add new</a>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-sm-12">
	
		<div class="well">
			
			<?php if(count($installed_plugins) > 0): ?>
		
			<table class="table table-striped table-forum">
				<thead>
					<tr>
						<th>Plugin</th>
						<th class="text-center hidden-xs">Version</th>
						<th class="text-center hidden-xs">Author</th>
					</tr>
				</thead>
				
				<tbody>
				
				<?php foreach($installed_plugins as $plugin): ?>
				
				
				<?php $plugin_info = plugin_info($plugin['plugin']); ?>
				
					<tr>
						<td>
							<h4>
								<a href="javascript:void(0)"><?php echo $plugin_info['title'] ?></a>
								<small><?php echo $plugin_info['description'] ?> | <a  target="_blank" href="<?php echo $plugin_info['plugin_uri'] ?>"> visit plugin site</a></small>
							</h4>
							<p class="margin-top-10">
								<?php if(is_plugin_active($plugin['plugin'])):  ?>
								<a class="btn btn-xs btn-warning" href="<?php echo site_url('plugin/deactivate/'.$plugin['plugin']) ?>" title="Deactivate">Deactivate</a>	
							<?php else: ?>
								<a class="btn btn-xs btn-success" href="<?php echo site_url('plugin/activate/'.$plugin['plugin']) ?>" title="Activate">Activate</a>&nbsp;
								<a class="btn btn-xs btn-danger remove" data-title="<?php echo $plugin_info['title'] ?>"  data-href="<?php echo site_url('plugin/remove/'.$plugin['plugin']) ?>" title="Remove">Remove</a>		
							<?php endif; ?>
							</p>
						</td>
						<td class="text-center hidden-xs"><?php echo $plugin_info['version']; ?></td>
						<td class="text-center hidden-xs">
							<a target="_blank" href="<?php echo $plugin_info['author_uri'] ?>"><?php echo $plugin_info['author'] ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
						
				</tbody>
			
			</table>
			
			<?php else: ?>
				
				<h2 class="text-center"><i class="fa fa-plug"></i> No plugin installed</h2>
				<h6 class="text-center">Click "Add new" button to upload a new plugin</h6>
				
			<?php endif; ?>
		
		</div>
	</div>
</div>
