<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-12">
			<a href="<?php  echo site_url('objectmanager/file/add/'.$_id_object)?>" class="btn btn-default pull-right"> Add Files</a>
		</div>
	</div>
</div>
<table class="table table-striped table-hover" id="files_table">
    <thead>
        <tr>
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