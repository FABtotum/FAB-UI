<style type="text/css">.bulk-button, .details-button{margin-right:5px !important;} table tbody tr {cursor:pointer;}</style>
<div class="widget-body-toolbar">
	<div class="row">
		<div class="col-sm-12">
			<a rel="tooltip" data-placement="bottom" data-original-title="Delete all selected files" data-action="delete"   href="javascript:void(0);" class="btn btn-danger  bulk-button"><i class="fa fa-trash"></i> Delete</a>
			<a rel="tooltip" data-placement="bottom" data-original-title="Download all selected files" data-action="download" href="javascript:void(0);" class="btn btn-info    bulk-button"><i class="fa fa-download"></i> Download</a>
			<a rel="tooltip" data-placement="bottom" data-original-title="Add new file to this object" href="<?php  echo site_url('objectmanager/file/add/'.$_id_object)?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Files</a>
		</div>
	</div>
</div>
<table class="table table-striped table-bordered table-hover" id="files_table">
    <thead>
        <tr>
        	<th class="hidden"></th>
        	<th class="hidden"></th>
        	<th class="hidden"></th>
        	<th class="hidden"></th>
        	<th class="center" width="20px"></th>
        	<th class="center table-checkbox" width="20px"><label class="checkbox-inline"><input type="checkbox" class="checkbox style-0 select-all"><span></span></label></th>
            <th width="100px">Name</th>
            <th class="hidden-xs hidden-mobile">Type</th>
            <th class="hidden-xs hidden-mobile">Note</th>
            <th class="hidden-xs hidden-mobile">Date</th>
            <th class="hidden-xs hidden-mobile hidden" style="width: 100px;">Size</th>
            <th class="text-center" width="20px"></th>
          
        </tr>
    </thead>
    <tbody>
        <?php foreach($_files as $_file): ?>
        <tr>
        	<td class="hidden"><?php echo $_file->id; ?></td>
        	<td class="hidden"><?php echo $_file->raw_name; ?></td>
        	<td class="hidden"><?php echo $_file->file_ext; ?></td>
        	<td class="hidden"><?php echo $_file->print_type; ?></td>
        	<td class="center" width="20px"><a href="#" > <i class="fa fa-chevron-right fa-lg" data-toggle="row-detail" title="Show Details"></i> </a></td>
        	<td class="center table-checkbox" width="20px">
        		 <label class="checkbox-inline">
		             <input type="checkbox"  class="checkbox style-0" id="check_<?php echo $_file->id ?>" name="checkbox-inline" />
		        	<span></span>
		        </label>
        	</td>
            <td><?php echo $_file -> raw_name; ?></td>
            <td class="hidden-xs hidden-mobile"><?php echo str_replace('.', '', $_file -> file_ext); ?> <?php echo $_file -> print_type != '' ? '(' . $_file -> print_type . ')' : ''; ?></td>
            <td class="hidden-xs hidden-mobile"><?php echo $_file -> note; ?></td>
            <td class="hidden-xs hidden-mobile"><?php echo date('d/m/Y', strtotime($_file ->insert_date)); ?></td> 
            <td class="hidden-xs hidden-mobile hidden"><?php echo roundsize($_file -> file_size); ?></td>
            <td class="text-center" width="20px">
            	<?php if(in_array($_file->file_ext, $_printable_files)): ?>
            	<?php 
            		switch($_file->print_type){
            			case 'additive':
            				$make_label = 'Print';
            				$make_url = 'print';
            				break;
            			case 'subtractive':
            				$make_label = 'Mill';
            				$make_url = 'mill';
            				break;
            			case 'laser':
            				$make_label = 'Laser';
            				$make_url = 'laser';
            				break;
            		}
				?>
            	<a rel="tooltip" data-placement="left" data-original-title="<?php echo $make_label ?> this file" class="btn btn-success btn-xs" href="<?php echo site_url('make/'.$make_url).'?obj='.$_id_object.'&file='.$_file->id ?>"><i class="fa fa-play fa-rotate-90"></i> <span class="hidden-xs hidden-mobile"><?php echo $make_label; ?></span></a>
            	<?php endif; ?>
            </td>
            
        </tr>
        <?php endforeach; ?>
    </tbody>


</table>