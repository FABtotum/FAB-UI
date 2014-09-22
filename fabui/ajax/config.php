<?php
//============================= CONFIG ===============================
defined("SITE_URL")      ? null : define("SITE_URL", 'http://'.$_SERVER['HTTP_HOST'].'/fabui/');
defined('FABUI_PATH')    ? null : define("FABUI_PATH", '/var/www/fabui/');
defined('PYTHON_PATH')   ? null : define("PYTHON_PATH", FABUI_PATH.'python/');
defined('TASKS_PATH')    ? null : define("TASKS_PATH", '/var/www/tasks/');
defined('RECOVERY_PATH') ? null : define("RECOVERY_PATH", '/var/www/recovery/');
defined('TEMP_PATH')     ? null : define("TEMP_PATH", '/var/www/temp/');
defined('UPLOAD_PATH')   ? null : define("UPLOAD_PATH", '/var/www/upload/');

//============================= SERIAL PORT =========================
defined("PORT_NAME")  ? null : define("PORT_NAME", '/dev/ttyAMA0');
defined("BOUD_RATE")  ? null : define("BOUD_RATE", '115200'); 

//================================== DATABASE ============
defined("DB_HOSTNAME")  ? null : define("DB_HOSTNAME", 'localhost');
defined("DB_USERNAME")  ? null : define("DB_USERNAME", 'root');
defined("DB_PASSWORD")  ? null : define("DB_PASSWORD", 'fabtotum');
defined("DB_DATABASE")  ? null : define("DB_DATABASE", 'fabtotum');

defined("SQL_INSTALL_DB")  ? null : define("SQL_INSTALL_DB", RECOVERY_PATH.'install/sql/fabtotum.sql');

//==========================================================
defined("CONFIG_UNITS")  ? null : define("CONFIG_UNITS", FABUI_PATH.'config/config.json');


//=========== REMOTE ==============================//
defined("MYFAB_REMOTE_VERSION_URL")  ? null : define("MYFAB_REMOTE_VERSION_URL", 'http://update.fabtotum.com/FAB-UI/version.txt');
defined("MARLIN_REMOTE_VERSION_URL") ? null : define("MARLIN_REMOTE_VERSION_URL", 'http://update.fabtotum.com/MARLIN/version.txt');

//=========== LOCAL ==============================//
//defined("MYFAB_LOCAL_VERSION_PATH")  ? null : define("MYFAB_LOCAL_VERSION_PATH", '/var/www/fabui/version.txt');
//defined("MARLIN_LOCAL_VERSION_PATH") ? null : define("MARLIN_LOCAL_VERSION_PATH", '/var/www/marlin/version.txt');


//=================================================================================
defined("MYFAB_DOWNLOAD_URL")            ? null : define("MYFAB_DOWNLOAD_URL",            'http://update.fabtotum.com/FAB-UI/download/');
defined("MYFAB_DOWNLOAD_FILE")           ? null : define("MYFAB_DOWNLOAD_FILE",           'fabui.zip');
defined("MYFAB_UPDATE_MONITOR_FILE")     ? null : define("MYFAB_UPDATE_MONITOR_FILE",     TEMP_PATH.'myfab_update.json');
defined("MYFAB_DOWNLOAD_TARGET_FILE")    ? null : define("MYFAB_DOWNLOAD_TARGET_FILE",    TEMP_PATH);
defined("MYFAB_DOWNLOAD_EXTRACT_FOLDER") ? null : define("MYFAB_DOWNLOAD_EXTRACT_FOLDER", TEMP_PATH);



defined("MARLIN_DOWNLOAD_URL")            ? null : define("MARLIN_DOWNLOAD_URL", 'http://update.fabtotum.com/MARLIN/download/');
defined("MARLIN_DOWNLOAD_FILE")           ? null : define("MARLIN_DOWNLOAD_FILE", 'Marlin.cpp.hex');
defined("MARLIN_DOWNLOAD_MONITOR_FILE")   ? null : define("MARLIN_DOWNLOAD_MONITOR_FILE",   '/var/www/recovery/update/temp/marlin_progress.json');
defined("MARLIN_DOWNLOAD_TARGET_FILE")    ? null : define("MARLIN_DOWNLOAD_TARGET_FILE",    '/var/www/recovery/update/temp/');
defined("MARLIN_DOWNLOAD_EXTRACT_FOLDER") ? null : define("MARLIN_DOWNLOAD_EXTRACT_FOLDER", '/var/www/recovery/update/temp/');


?>