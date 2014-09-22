<?php
/** START SESSION */
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/fabui/ajax/lib/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/fabui/ajax/lib/utilities.php';

/** GET DATA FROM POST */
$_first_name = $_POST['first_name'];
$_last_name  = $_POST['last_name'];
$_email      = $_POST['email'];
$_avatar     = $_POST['avatar'];
$_theme_skin = $_POST['theme_skin'];

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
shell_exec('sudo rm /var/www/fabui/application/layout/'.$_SESSION['user']['avatar']);

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

$_settings['avatar']        = $_avatar;
$_settings['theme-skin']    = $_theme_skin;
$_data_update['settings']   = json_encode($_settings);

$db->update('sys_user', array('column' => 'id', 'value' => $_SESSION['user']['id'], 'sign' => '='), $_data_update);
$db->close();

/** UPDATE SESSION BASIC USER INFO */
$_SESSION['user']['first_name'] = $_first_name;
$_SESSION['user']['last_name']  = $_last_name;
$_SESSION['user']['email']      = $_email;
$_SESSION['user']['avatar']     = $_avatar;
$_SESSION['user']['theme-skin'] = $_theme_skin;


?>