<?php
/** START SESSION */
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/utilities.php';

/** GET DATA FROM POST */
$_old_password         = $_POST['old_password'];
$_new_password         = $_POST['new_password'];
$_confirm_new_password = $_POST['confirm_new_password'];


$_response_items = array();

/** LOAD DB */
$db = new Database();

/** GET USER FROM DB */
$_user = $db->query('select * from sys_user where id='.$_SESSION['user']['id'].' and password="'.md5($_old_password).'"');


/** IF USER EXIST */
if($db->get_num_rows() > 0){
	
	
	/** IF THE NEW PASSWORD IS CONFIRMED */
	if($_new_password == $_confirm_new_password){
		
		
		$data_update['password'] = md5($_new_password);
		
		$db->update('sys_user', array('column' => 'id', 'value' => $_user['id'], 'sign' => '='), $data_update);
		$db->close();
		
		$result  = 'ok';
		$message = '<i class="fa fa-check"></i> New password saved';
		$icon    = 'fa fa-thumbs-up bounce animated ';
		$color   = '#659265';
		$title   = 'Success';
	}else{
		$result  = 'ko';
		$message = 'New password is not confirmed';
		$icon    = 'fa fa-warning';
		$color   = '#C46A69';
		$title   = 'Warning';
	}
	
	

}else{
	
	
	$result  = 'ko';
	$message = 'Old password incorrect';
	$icon    = 'fa fa-warning';
	$color   = '#C46A69';
	$title   = 'Warning';
	
	
	
}


$_response_items['result']  = $result;
$_response_items['message'] = $message;
$_response_items['icon']    = $icon;
$_response_items['color']   = $color;
$_response_items['title']   = $title;


echo json_encode($_response_items);



?>