<div class="widget-body-toolbar"></div>
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
            <td><?php echo $_file->file_name; ?></td>
            <td><?php echo str_replace('.', '', $_file->file_ext); ?> <?php echo $_file->print_type != '' ? '('.$_file->print_type.')' : ''; ?></td>
            <td class="hidden-xs"><?php echo $_file->note; ?></td>
            <td class="hidden-xs"><?php echo roundsize($_file->file_size); ?></td>
            <td class="text-right">
                <div class="btn-group">
                <?php if(in_array($_file->file_ext, $_printable_files)): ?>
                    <a  href="<?php echo site_url("objectmanager/file/view/".$_id_object."/".$_file->id) ?>" class="btn btn-default"><i class="fa fa-pencil-square-o"></i></a>
                    <a  title="Print" href="<?php echo site_url("create") ?>?obj=<?php echo $_id_object ?>&file=<?php echo $_file->id ?>" class="btn btn-default "><i class="icon-fab-print txt-color-green"></i></a>
                <?php endif; ?>
                <?php if($_file->file_ext == '.stl'): ?>
                    <a  href="<?php echo site_url("objectmanager/prepare/stl/".$_id_object."/".$_file->id); ?>" class="btn btn-default "><i class="fa fa-wrench"></i></a>
                <?php endif; ?>
                    <a  title="Download" href="<?php echo site_url("objectmanager/download/".$_file->id) ?>" class="btn btn-default "><i class="fa fa-download"></i></a>
                    <a href="javascript: ask_delete(<?php echo $_file->id; ?>, '<?php echo $_file->file_name; ?>')" class="btn btn-default  delete-file txt-color-red" data-file-id="<?php echo $_file->id; ?>"><i class="fa fa-times"></i></a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>


</table>