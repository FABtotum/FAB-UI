<div class="row">
	<!-- col -->
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<h1 class="page-title txt-color-blueDark">
			<i class="fab-fw icon-fab-plugin"></i> Plugins </span>
		</h1>
	</div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-align-right">
        <div class="page-title">
            <a href="<?php echo site_url('plugin/add'); ?>" class="btn btn-default">Add more</a>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-sm-12">
	
		<div class="well">
		
			<table class="table table-striped table-forum">
				<thead>
					<tr>
						<th>Plugin</th>
						<th></th>
						<th class="text-center">Version</th>
						<th class="text-center">Author</th>
					</tr>
				</thead>
				
				<tbody>
				
				<?php foreach($installed_plugins as $plugin): ?>
				
				
				<?php $plugin_info = plugin_info($plugin['plugin']); ?>
				
					<tr>
						<td>
							<h4>
								<a href="javascript:void(0)"><?php echo $plugin_info['title'] ?></a>
								<small><?php echo $plugin_info['description'] ?> | <a  target="_blank" href="<?php echo $plugin_info['plugin_uri'] ?>"> visiti plugin site</a></small>
							</h4>
						</td>
						<td>
							<?php 
								if(is_plugin_active($plugin['plugin'])){
							 		echo anchor('plugin/deactive/'.$plugin['plugin'], 'Deactive', 'title="Active"');
							 	}else{
									echo anchor('plugin/active/'.$plugin['plugin'], 'Active', 'title="Active"');
								}
							
							 ?>
						</td>
						<td class="text-center"><?php echo $plugin_info['version']; ?></td>
						<td class="text-center">
							<a target="_blank" href="<?php echo $plugin_info['author_uri'] ?>"><?php echo $plugin_info['author'] ?></a>
						</td>
					
					
					</tr>
				
				
				<?php endforeach; ?>
						
				</tbody>
			
			</table>
		
		</div>
	</div>
</div>
