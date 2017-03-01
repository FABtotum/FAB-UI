<div class="row">
	<!-- col -->
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<h1 class="page-title txt-color-blueDark">
			<i class="fab-fw icon-fab-plugin"></i> Plugins <span> > Add new</span>
		</h1>
	</div>
	
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<div class="page-title pull-right">
			<a href="<?php  echo site_url('plugin/upload')?>"
				class="btn btn-primary"><i class="fa fa-upload"></i> Upload</a>
		</div>
	</div>
    
</div>

<div class="row">

<?php if(!$_internet): ?>

    <div class="col-sm-12">
        
        <div class="well text-center">
            <h1><i class="fa fa-warning"></i> No internet connection</h1>
            <h5>Please check your connection</h5>
            <h1></h1>
        </div>
    
    </div>


<?php else: ?>

    <div class="col-sm-12">
        <div class="well">
        <?php $_count_repo = 1;  ?>
        <?php foreach($_plugins as $_list): ?>
                <table class="table table-striped table-forum">
                    
                    <thead>
                        <tr>
                            <th><?php echo $_list['name'] ?> <small><?php echo $_list['description'] ?></small></th>
                            <?php if(isset($_list['plugins']) && count($_list['plugins']) > 0): ?> 
                            <th class="text-center" style="width: 100px;">Install</th>
                            <th class="text-center hidden-xs" style="width: 100px;">Version</th>
                            <th class="text-center hidden-xs" style="width: 200px;">Author</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                   
                    <tbody>
                    
                    <?php if(isset($_list['plugins']) && count($_list['plugins']) > 0): ?>    
                    <?php foreach($_list['plugins'] as $_plugin): ?>
                        
                        <tr>
                            <td>
                                <h4>
                                    <a href="javascript:void(0);"><?php echo $_plugin['name'] ?></a>
                                    <small><?php echo $_plugin['description'] ?> | <a href="<?php echo $_plugin['website']  ?>"> visit plugin site</a></small>
                                </h4>
                            </td>
                            <td class="text-center"><a href="javascript:void(0)"><i class="fa fa-download fa-2x"></i></a></td>
                            <td class="text-center hidden-xs"><?php echo $_plugin['version'] ?></td>
                            <td class="text-center hidden-xs"><a target="_blank" href="<?php echo $_plugin['website'] ?>"><?php echo $_plugin['author'] ?></a></td>
                        </tr>
                    
                    <?php endforeach; ?>
                    <?php else: ?>
                    	<tr>
                    		<td>
                    			No plugins available
                    		</td>
                    	</tr>
                    <?php endif; ?>
                    
                    </tbody>
                </table>
                
        <?php endforeach; ?>
        </div>
    </div>
  

<?php endif; ?>

</div>