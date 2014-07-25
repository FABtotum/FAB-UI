<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/myfabtotum/ajax/lib/utilities.php';

/** LOAD DATABASE */
$db = new Database();

/** LOAD OBJECE FROM DB */
$_objects = $db->query('SELECT `sys_objects`.`id`, `sys_objects`.`obj_name`, `sys_objects`.`obj_description`, `sys_objects`.`date_insert`, `sys_objects`.`date_updated`, count(id_file) as num_files FROM (`sys_objects`) LEFT JOIN `sys_obj_files` ON `sys_obj_files`.`id_obj` = `sys_objects`.`id` GROUP BY `sys_objects`.`id` ORDER BY `date_insert` DESC');
$db->close();

$_rows = array();



foreach($_objects as $obj){
    
    
    $_edit_button   = '<a href="'.site_url('objectmanager/edit/'.$obj['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
    $_delete_button = '<a href="javascript:ask_delete('.$obj['id'].', \''.$obj['obj_name'].'\');" file-id="'.$obj['id'].'" file-name="'.$obj['obj_name'].'" class="btn btn-default file-delete txt-color-red"><i class="fa fa-times"></i></a>';
    
    $icon_file = $obj['num_files'] > 1 ? 'fa-files-o' : ' fa-file-o';
    $_files    = $obj['num_files'].' <i class="fa '.$icon_file.'"></i>';
    
    $_link_edit = '<a href="'.site_url('objectmanager/edit/'.$obj['id']).'">'.$obj['obj_name'].'</a>';
    
    
    $_rows[] = array($_link_edit, $obj['obj_description'], mysql_to_human($obj['date_insert']), $_files,  '<div class="btn-group">'.$_edit_button.' '.$_delete_button.'</div>');          


}

header('Content-Type: application/json');
echo minify(json_encode(array('aaData' => $_rows)));



?>