<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/utilities.php';

/** LOAD DATABASE */
$db = new Database();

/** LOAD OBJECE FROM DB */
$_objects = $db->query('SELECT `sys_objects`.`id`, `sys_objects`.`obj_name`, `sys_objects`.`obj_description`, `sys_objects`.`date_insert`, `sys_objects`.`date_updated`, count(id_file) as num_files FROM (`sys_objects`) LEFT JOIN `sys_obj_files` ON `sys_obj_files`.`id_obj` = `sys_objects`.`id` where `sys_objects`.`user` = '.$_SESSION['user']['id'].' GROUP BY `sys_objects`.`id` ORDER BY `date_insert` DESC');
$db->close();


$_rows = array();



foreach($_objects as $obj){
    
    
    $_edit_button    = '<a href="'.site_url('objectmanager/edit/'.$obj['id']).'" ><i class="fa fa-file fa-lg fa-fw txt-color-blue"></i> <u>E</u>dit</a>';
    $_delete_button  = '<a href="javascript:ask_delete('.$obj['id'].', \''.$obj['obj_name'].'\');" file-id="'.$obj['id'].'" file-name="'.$obj['obj_name'].'" class="file-delete"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>';
    $icon_file       = $obj['num_files'] > 1 ? 'fa-files-o' : ' fa-file-o';
    $_files          = $obj['num_files'].' <i class="fa '.$icon_file.'"></i>';
    $_link_edit      = '<a href="'.site_url('objectmanager/edit/'.$obj['id']).'">'.$obj['obj_name'].'</a>';
    //$_action_buttons = '<div class="btn-group">'.$_edit_button.' '.$_delete_button.'</div>';
    
	
	$_action_buttons  = '<div class="btn-group display-inline pull-right text-align-left ">';
	
	$_action_buttons .= '<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></button>';
	
	$_action_buttons .= '<ul class="dropdown-menu dropdown-menu-xs pull-right">';
	
	$_action_buttons .= '<li>'.$_edit_button.'</li>';
	$_action_buttons .= '<li>'.$_delete_button.'</li>';
	$_action_buttons .= '<li class="divider"></li><li class="text-align-center"><a href="javascript:void(0);">Cancel</a></li>';
	$_action_buttons .= '</ul>';
	
	$_action_buttons .= '</div>';
	
	
	
    $_rows[] = array($_link_edit, $obj['obj_description'], mysql_to_human($obj['date_insert']), $_files, $_action_buttons);          


}

header('Content-Type: application/json');
echo minify(json_encode(array('aaData' => $_rows)));



?>