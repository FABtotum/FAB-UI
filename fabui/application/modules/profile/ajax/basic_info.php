<?php
/** START SESSION */
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/fabui/ajax/lib/utilities.php';

/** GET DATA FROM POST */
$_first_name  = $_POST['first_name'];
$_last_name   = $_POST['last_name'];
$_email       = $_POST['email'];

$_theme_skin  = $_POST['theme_skin'];
$_lock_screen = $_POST['lock_screen'];
$_avatar_change = $_POST['avatar_change'] == 1 ? true : false;

$_navigation_fixed = $_POST['navigation_fixed'] == 'true' ? true : false;
$_header_fixed     = $_POST['header_fixed'] == 'true' ? true : false;
$_ribbon_fixed     = $_POST['ribbon_fixed'] == 'true' ? true : false;
$_footer_fixed     = $_POST['footer_fixed'] == 'true' ? true : false;

 
$_avatar = $_SESSION['user']['avatar'];

if($_avatar_change){
	
	
	$_avatar = $_POST['avatar'];
	
	if($_avatar != ''){
		
		/** CREATE NEW AVATAR IMAGE */
		$temp          = explode(';', $_avatar);
		$image_type    = explode('/', $temp[0])[1];
		$image_content = explode(',', $temp[1])[1];
		$image_content = base64_decode($image_content);
		
		
		/** ADD PERMISSIONS  */
		shell_exec('sudo chmod 777 '.FABUI_PATH.'application/layout/assets/img/avatar');
		
		$_avatar = '/assets/img/avatar/'.$_SESSION['user']['id'].'_'.time().'.'.$image_type;
		file_put_contents(FABUI_PATH.'application/layout/'.$_avatar, $image_content);
		
		/** REMOVE OLD AVATAR IMAGE */
		shell_exec('sudo rm '.FABUI_PATH.'application/layout/'.$_SESSION['user']['avatar']);
		
	}		
	
}




/** LOAD DB */
$db = new Database();


/** GET USER FROM DB */
$_user = $db->query('select * from sys_user where id='.$_SESSION['user']['id']);
$_user = $_user[0];

$_settings = json_decode($_user['settings'], true);


/** UPDATE BASIC USER INFO */
$_data_update = array();
$_data_update['first_name'] = $_first_name;
$_data_update['last_name']  = $_last_name;
$_data_update['email']      = $_email;


//LAYOUT
$_settings['layout'] = '';

if($_header_fixed){
	$_settings['layout'] .= ' fixed-header ';
}

if($_navigation_fixed){
	$_settings['layout'] .= ' fixed-navigation ';
}

if($_ribbon_fixed){
	$_settings['layout'] .= ' fixed-ribbon ';
}

if($_footer_fixed){
	$_settings['layout'] .= ' fixed-page-footer ';
}


$_settings['avatar']        = $_avatar;
$_settings['theme-skin']    = $_theme_skin;
$_settings['lock-screen']   = $_lock_screen;

$_data_update['settings']   = json_encode($_settings);

$db->update('sys_user', array('column' => 'id', 'value' => $_SESSION['user']['id'], 'sign' => '='), $_data_update);
$db->close();

/** UPDATE SESSION BASIC USER INFO */
$_SESSION['user']['first_name']  = $_first_name;
$_SESSION['user']['last_name']   = $_last_name;
$_SESSION['user']['email']       = $_email;
$_SESSION['user']['avatar']      = $_avatar;
$_SESSION['user']['theme-skin']  = $_theme_skin;
$_SESSION['user']['lock-screen'] = $_lock_screen;
$_SESSION['user']['layout']      = $_settings['layout'];

?>