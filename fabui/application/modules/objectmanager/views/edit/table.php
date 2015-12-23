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
            <th class="hidden-xs">Type</th>
            <th class="hidden-xs">Note</th>
            <th class="hidden-xs">Date</th>
            <th class="hidden-xs" style="width: 100px;">Size</th>
          
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
            <td class="hidden-xs"><?php echo str_replace('.', '', $_file -> file_ext); ?> <?php echo $_file -> print_type != '' ? '(' . $_file -> print_type . ')' : ''; ?></td>
            <td class="hidden-xs"><?php echo $_file -> note; ?></td>
            <td class="hidden-xs"><?php echo mysql_to_human($_file ->insert_date); ?></td>
            <td class="hidden-xs"><?php echo roundsize($_file -> file_size); ?></td>
            
        </tr>
        <?php endforeach; ?>
    </tbody>


</table>