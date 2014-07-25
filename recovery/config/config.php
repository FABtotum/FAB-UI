<?php
defined("INSTALL_FILE")  ? null : define("INSTALL_FILE", '/var/www/install.txt');


//================================== DATABASE ============
defined("DB_HOSTNAME")  ? null : define("DB_HOSTNAME", 'localhost');
defined("DB_USERNAME")  ? null : define("DB_USERNAME", 'root');
defined("DB_PASSWORD")  ? null : define("DB_PASSWORD", 'fabtotum');
defined("DB_DATABASE")  ? null : define("DB_DATABASE", 'fabtotum');


//================================= INSTALL =============
defined("SQL_INSTALL_DB")  ? null : define("SQL_INSTALL_DB", '/var/www/recovery/install/sql/fabtotum.sql');



//=========== INSTALL ==============================//
defined("MYFAB_REMOTE_VERSION_URL")  ? null : define("MYFAB_REMOTE_VERSION_URL", 'http://update.fabtotum.com/FAB-UI/version.txt');
defined("MARLIN_REMOTE_VERSION_URL") ? null : define("MARLIN_REMOTE_VERSION_URL", 'http://update.fabtotum.com/MARLIN/version.txt');

//=========== LOCAL ==============================//
defined("MYFAB_LOCAL_VERSION_PATH")  ? null : define("MYFAB_LOCAL_VERSION_PATH", '/var/www/myfabtotum/version.txt');
defined("MARLIN_LOCAL_VERSION_PATH") ? null : define("MARLIN_LOCAL_VERSION_PATH", '/var/www/marlin/version.txt');

//================================================================================================================
//defined("MYFAB_DOWNLOAD_URL")          ? null : define("MYFAB_DOWNLOAD_URL", 'http://download.thinkbroadband.com/');
defined("MYFAB_DOWNLOAD_URL")            ? null : define("MYFAB_DOWNLOAD_URL",            'http://update.fabtotum.com/FAB-UI/download/');
defined("MYFAB_DOWNLOAD_FILE")           ? null : define("MYFAB_DOWNLOAD_FILE",           'myfabtotum.zip');
defined("MYFAB_DOWNLOAD_MONITOR_FILE")   ? null : define("MYFAB_DOWNLOAD_MONITOR_FILE",   '/var/www/recovery/update/temp/myfab_progress.json');
defined("MYFAB_DOWNLOAD_TARGET_FILE")    ? null : define("MYFAB_DOWNLOAD_TARGET_FILE",    '/var/www/recovery/update/temp/');
defined("MYFAB_DOWNLOAD_EXTRACT_FOLDER") ? null : define("MYFAB_DOWNLOAD_EXTRACT_FOLDER", '/var/www/recovery/update/temp/');



defined("MARLIN_DOWNLOAD_URL")            ? null : define("MARLIN_DOWNLOAD_URL", 'http://update.fabtotum.com/MARLIN/download/');
defined("MARLIN_DOWNLOAD_FILE")           ? null : define("MARLIN_DOWNLOAD_FILE", 'marlin.hex');
defined("MARLIN_DOWNLOAD_MONITOR_FILE")   ? null : define("MARLIN_DOWNLOAD_MONITOR_FILE",   '/var/www/recovery/update/temp/marlin_progress.json');
defined("MARLIN_DOWNLOAD_TARGET_FILE")    ? null : define("MARLIN_DOWNLOAD_TARGET_FILE",    '/var/www/recovery/update/temp/');
defined("MARLIN_DOWNLOAD_EXTRACT_FOLDER") ? null : define("MARLIN_DOWNLOAD_EXTRACT_FOLDER", '/var/www/recovery/update/temp/');







?>