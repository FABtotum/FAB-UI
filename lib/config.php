<?php
//===================================================================================================================================================
$www_path   = '/var/www/';
$fabui_path = $www_path.'fabui/';

defined('WWW_PATH')      ? null : define("WWW_PATH",      $www_path);
defined('FABUI_PATH')    ? null : define("FABUI_PATH",    $fabui_path);
defined('PYTHON_PATH')   ? null : define("PYTHON_PATH",   FABUI_PATH.'python/');
defined('SCRIPT_PATH')   ? null : define("SCRIPT_PATH",   FABUI_PATH.'script/');
defined('TASKS_PATH')    ? null : define("TASKS_PATH",    WWW_PATH.'tasks/');
defined('RECOVERY_PATH') ? null : define("RECOVERY_PATH", WWW_PATH.'recovery/');
defined('TEMP_PATH')     ? null : define("TEMP_PATH",     WWW_PATH.'temp/');
defined('UPLOAD_PATH')   ? null : define("UPLOAD_PATH",   WWW_PATH.'upload/');
defined('LIB_PATH')      ? null : define("LIB_PATH",      WWW_PATH.'lib/');
defined('LOCK_FILE')     ? null : define("LOCK_FILE",     WWW_PATH.'temp/LOCK');
defined('INI_FILE')      ? null : define("INI_FILE",      LIB_PATH.'config.ini');
defined('SERIAL_INI')    ? null : define("SERIAL_INI",    LIB_PATH.'serial.ini');

//===================================================================================================================================================
/** SERIAL PORT CONSTANTS */
defined("PORT_NAME")  ? null : define("PORT_NAME", '/dev/ttyAMA0');
defined("BOUD_RATE")  ? null : define("BOUD_RATE", '115200'); 

//===================================================================================================================================================
/** DATABASE CONNECTION */
defined("DB_HOSTNAME")  ? null : define("DB_HOSTNAME", 'localhost');
defined("DB_USERNAME")  ? null : define("DB_USERNAME", 'root');
defined("DB_PASSWORD")  ? null : define("DB_PASSWORD", 'fabtotum');
defined("DB_DATABASE")  ? null : define("DB_DATABASE", 'fabtotum');

defined("SQL_INSTALL_DB")  ? null : define("SQL_INSTALL_DB", RECOVERY_PATH.'install/sql/fabtotum.sql');

//===================================================================================================================================================
defined("CONFIG_UNITS")         ? null : define("CONFIG_UNITS", FABUI_PATH.'config/config.json');
defined("CUSTOM_CONFIG_UNITS")  ? null : define("CUSTOM_CONFIG_UNITS", FABUI_PATH.'config/custom_config.json');

//===================================================================================================================================================
defined("MYFAB_REMOTE_VERSION_URL")  ? null : define("MYFAB_REMOTE_VERSION_URL", 'http://update.fabtotum.com/FAB-UI/version.txt');
defined("MARLIN_REMOTE_VERSION_URL") ? null : define("MARLIN_REMOTE_VERSION_URL", 'http://update.fabtotum.com/MARLIN/version.txt');


//===================================================================================================================================================
defined("MYFAB_DOWNLOAD_URL")            ? null : define("MYFAB_DOWNLOAD_URL",            'http://update.fabtotum.com/FAB-UI/download/');
defined("MYFAB_DOWNLOAD_FILE")           ? null : define("MYFAB_DOWNLOAD_FILE",           'fabui.zip');
defined("MYFAB_UPDATE_MONITOR_FILE")     ? null : define("MYFAB_UPDATE_MONITOR_FILE",     TEMP_PATH.'myfab_update.json');
defined("MYFAB_DOWNLOAD_TARGET_FILE")    ? null : define("MYFAB_DOWNLOAD_TARGET_FILE",    TEMP_PATH);
defined("MYFAB_DOWNLOAD_EXTRACT_FOLDER") ? null : define("MYFAB_DOWNLOAD_EXTRACT_FOLDER", TEMP_PATH);

//===================================================================================================================================================
defined("MARLIN_DOWNLOAD_URL")            ? null : define("MARLIN_DOWNLOAD_URL", 'http://update.fabtotum.com/MARLIN/download/');
defined("MARLIN_DOWNLOAD_FILE_ZIP")       ? null : define("MARLIN_DOWNLOAD_FILE_ZIP", 'firmware.zip');
defined("MARLIN_DOWNLOAD_FILE")           ? null : define("MARLIN_DOWNLOAD_FILE", 'Marlin.cpp.hex');
defined("MARLIN_DOWNLOAD_MONITOR_FILE")   ? null : define("MARLIN_DOWNLOAD_MONITOR_FILE",   '/var/www/recovery/update/temp/marlin_progress.json');
defined("MARLIN_DOWNLOAD_TARGET_FILE")    ? null : define("MARLIN_DOWNLOAD_TARGET_FILE",    '/var/www/recovery/update/temp/');
defined("MARLIN_DOWNLOAD_EXTRACT_FOLDER") ? null : define("MARLIN_DOWNLOAD_EXTRACT_FOLDER", '/var/www/recovery/update/temp/');


//================================= SOCKET ===============
defined("SOCKET_HOST")  ? null : define("SOCKET_HOST", '0.0.0.0');
defined("SOCKET_PORT")  ? null : define("SOCKET_PORT", 9001);


//====================== NETWORK INTERFACES ===============
defined("NETWORK_INTERFACES")  ? null : define("NETWORK_INTERFACES", '/etc/network/interfaces');



//==================================
defined("TASK_TRACE")  ? null : define("TASK_TRACE", '/var/www/temp/task_trace');
defined("MACRO_TRACE") ? null : define("MACRO_TRACE", '/var/www/temp/macro_trace');
defined("TASK_NOTIFICATIONS") ? null : define("TASK_NOTIFICATIONS", '/var/www/temp/task_notifications.json');


define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


if(isset($_SERVER['SERVER_NAME'])){
	defined("SITE_URL") ? null : define("SITE_URL", 'http://'.$_SERVER['SERVER_NAME'].'/fabui/');
}



defined('INSTAGRAM_FEED_URL')  ? null : define("INSTAGRAM_FEED_URL",      'http://www.fabtotum.com/instagram_feed.json');
defined('INSTAGRAM_HASH_URL')  ? null : define("INSTAGRAM_HASH_URL",      'http://www.fabtotum.com/instagram_hash.json');
defined('INSTAGRAM_FEED_JSON') ? null : define("INSTAGRAM_FEED_JSON",      TEMP_PATH.'instagram_feed.json');
defined('INSTAGRAM_HASH_JSON') ? null : define("INSTAGRAM_HASH_JSON",      TEMP_PATH.'instagram_hash.json');

defined('TWITTER_FEED_URL')  ? null : define("TWITTER_FEED_URL",      'http://www.fabtotum.com/twitter_feed.json');
defined('TWITTER_FEED_JSON') ? null : define("TWITTER_FEED_JSON",      TEMP_PATH.'twitter.json');

defined('BLOG_FEED_URL')  ? null : define("BLOG_FEED_URL",      'http://blog.fabtotum.com/feed/');
defined('BLOG_FEED_XML')  ? null : define("BLOG_FEED_XML",      TEMP_PATH.'blog.xml');

defined('FAQ_URL')  ? null : define("FAQ_URL", 'http://www.fabtotum.com/faq_new.json');
defined('FAQ_JSON') ? null : define("FAQ_JSON", TEMP_PATH.'faq.json');


defined('WIZARD_FILE') ? null : define("WIZARD_FILE", WWW_PATH.'WIZARD');

defined('USB_SYSTEM_FILE') ? null : define("USB_SYSTEM_FILE", '/dev/sda1');
defined('USB_FOLDER') ? null : define("USB_FOLDER", '/media');


defined('INTERFACES_FILE') ? null : define("INTERFACES_FILE", '/etc/network/interfaces');

?>