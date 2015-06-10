<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-12">
			
			<div class="form-inline">
				<div class="form-group">
					<select class="form-control bulk-select">
						<option value="">Bulk Actions</option>
						<option value="delete">Delete</option>
					</select>
				</div>
				<button class="btn btn-primary bulk-button" type="button">Apply</button>
				<a href="<?php  echo site_url('objectmanager/file/add/'.$_id_object)?>" class="btn btn-primary pull-right"> Add Files</a>
				
			</div>
			
		</div>
		
	</div>
</div>
<table class="table table-striped table-hover smart-form has-tickbox" id="files_table">
    <thead>
        <tr>
        	<th><label class="checkbox"><input class="select-all" type="checkbox" name="checkbox-inline" /><i></i> </label></th>
            <th>Name</th>
            <th>Type</th>
            <th class="hidden-xs">Note</th>
            <th class="hidden-xs" style="width: 100px;">Size</th>
            <th></th>
        </tr>
    </thead>
    
    <tbody>
        <?php foreach($_files as $_file): ?>
        <tr>
        	<td><label class="checkbox"><input id="check_<?php echo $_file->id ?>" type="checkbox" name="checkbox-inline" /><i></i> </label></td>
            <td><a href="<?php echo site_url('objectmanager/manage/'.$_id_object.'/'.$_file->id) ?>"><?php echo $_file -> raw_name; ?></a></td>
            <td><?php echo str_replace('.', '', $_file -> file_ext); ?> <?php echo $_file -> print_type != '' ? '(' . $_file -> print_type . ')' : ''; ?></td>
            <td class="hidden-xs"><?php echo $_file -> note; ?></td>
            <td class="hidden-xs"><?php echo roundsize($_file -> file_size); ?></td>
            <td class="text-right">
            
                <div class="btn-group display-inline pull-right text-align-left ">
					<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-cog fa-lg"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-xs pull-right">
                        <?php if(!in_array($_file->file_ext, $_printable_files)): ?>
                        <li>
							<a href="<?php echo site_url('objectmanager/manage/'.$_id_object.'/'.$_file->id) ?>"><i class="fa fa-th-large fa-lg fa-fw txt-color-orange"></i> <u>M</u>anage</a>
						</li>
                        <?php endif; ?>
						<li>
							<a href="<?php echo site_url("objectmanager/file/view/".$_id_object."/".$_file->id) ?>"><i class="fa fa-file fa-lg fa-fw txt-color-blue"></i> <u>E</u>dit</a>
						</li>
                        
                        <?php if(in_array($_file->file_ext, $_printable_files)): ?>
                        <li>
                            <a  href="<?php echo site_url("create?obj=".$_id_object."&file=".$_file->id) ?>"><i class="icon-fab-print fa-lg fa-fw txt-color-orange"></i> <u>P</u>rint</a>
                        </li>  
                        <?php endif; ?>
                        
                        
                        <?php if(strtolower($_file->file_ext) == '.stl' || strtolower($_file->file_ext) == '.gc' || strtolower($_file->file_ext) == '.gcode'): ?>
                        	
                        	<li>
                        		<a href="<?php echo site_url("objectmanager/file/preview/".$_id_object."/".$_file->id) ?>"><i class="fa fa-eye fa-lg fa-fw txt-color-pink "></i>  Pre<u>v</u>iew</a>
                        	</li>	
                        	
                        <?php endif; ?>
                        
                        
                        <li>
                            <a  href="<?php echo site_url("objectmanager/download/".$_file->id) ?>"><i class="fa fa-download fa-lg fa-fw txt-color-greenLight"></i> <u>D</u>ownload</a>
                        </li>
						<li>
							
                            <a href="javascript: ask_delete(<?php echo $_file -> id; ?>, '<?php echo $_file -> file_name; ?>')" class="delete-file" data-file-id="<?php echo $_file -> id; ?>"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
						</li>
						<li class="divider"></li>
						<li class="text-align-center">
							<a href="javascript:void(0);">Cancel</a>
						</li>
					</ul>
				</div>

          
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>


</table>