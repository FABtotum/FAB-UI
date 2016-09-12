<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_folder  = $argv[2];
$_monitor = $argv[3];

$_file_size = 0;

$_myfab_local_version   = myfab_get_local_version();
$_myfab_remote_version  = myfab_get_remote_version();


$_file_name = $_folder.'fabui'.'.zip';
$_url       = MYFAB_DOWNLOAD_URL.$_myfab_remote_version.'/'.MYFAB_DOWNLOAD_FILE;
//$_monitor   = MYFAB_UPDATE_MONITOR_FILE;


//$do_update = $_myfab_local_version <= $_myfab_remote_version ;


$do_update = true; 

$_monitor_items = array(); 

if($do_update){
	
	
	echo "Downloading update package...".PHP_EOL;
    
    $_monitor_items['completed'] = 0;
    $_monitor_items['status'] = 'downloading'; 
	$_monitor_items['pid'] = getmypid();
	$_monitor_items['folder'] = $_folder;
    /** CREATE MONITOR FILE */
    write_monitor();
    
    $_target_file = fopen( $_file_name, 'w+') or die("can't open file");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
    curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_FILE, $_target_file );
    $start = time();
    $html  = curl_exec($ch);
    curl_close($ch);
    
    echo 'Download package ('.roundsize($_file_size).') completed in '.(humanTiming($start)).PHP_EOL;
    
    $_monitor_items['download']['completed'] = 1;
    
    
    /** ESTRAZIONE FILES  */
    $_monitor_items['status'] = 'extracting';
    $_monitor_items['extract']['completed'] = 0;
    $_monitor_items['extract']['percent'] = 0;
	
	echo 'Unpacking update package...';
	
    write_monitor();
    sleep(1);
    
    $_monitor_items['extract']['percent'] = '20';
    write_monitor();
    sleep(1);
    
    $_monitor_items['extract']['percent'] = '60';
    write_monitor();
	
	/** EXTRAC THE ZIP */
    extract_zip($_file_name, $_folder.'temp/');
	
    $_monitor_items['extract']['percent'] = '100';
    $_monitor_items['extract']['completed'] = 1;
   
    write_monitor();
	echo ' Complete!'.PHP_EOL;
    
    
    /** INSTALLAZIONE FILES */
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = 0;
    $_monitor_items['install']['completed'] = 0;
    write_monitor();
	echo 'Installing files...'.PHP_EOL;
    sleep(1);
	
	
	/** CHECK IF EXIST SCRIPT FILE TO EXEC */
	if(file_exists($_folder.'temp/install.php')){
		/** EXEC FILE */
		$_command_exec = 'sudo php '.$_folder.'temp/install.php'; 
		shell_exec($_command_exec);		
	}
	
	
	$_monitor_items['install']['percent'] = 25;
    write_monitor();
    sleep(1);
	
	
	/** CHECK IF EXIST FOLDER FABUI FOR THE UPDATE */
	if(file_exists($_folder.'temp/fabui')){
		
		$_command_copy = 'sudo cp -rvf '.$_folder.'temp/fabui /var/www/';
		shell_exec($_command_copy);
		
	}

	
	$_monitor_items['install']['percent'] = 50;
    write_monitor();
    sleep(1);
	
	$_monitor_items['install']['percent'] = 100;
    write_monitor();
    sleep(1);
	
	
	
	/** DELETE OLD VERSIONS */
    shell_exec('sudo rm -rf /var/www/recovery_old/');
    shell_exec('sudo rm -rf /var/www/fabui_old/');

	/** FILE PERMISSIONS */
	//shell_exec('sudo chmod 777 '.FABUI_PATH.'config/config.json');
	
	//shell_exec('sudo chmod 777 '.FABUI_PATH.'application/layout/assets/img/avatar');
	
	/** simbolic link */
	if(!file_exists('/var/www/fabui/upload') || !is_link('/var/www/fabui/upload')){
		
		shell_exec('sudo ln -s /var/www/upload /var/www/fabui/upload');
			
	}
	
	
	/** UPDATE VERSION  */
	/** LOAD DB */
	$db = new Database();
	/** UPDATE TASK */
	$_data_update = array();
	$_data_update['value'] = $_myfab_remote_version;
	$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'fabui_version', 'sign' => '='), $_data_update);
	$db->close();
	
    $_command_finalize = 'sudo php /var/www/fabui/script/finalize.php '.$_task_id.' update_fabui > /dev/null & echo $! ';
    shell_exec($_command_finalize);
	
	
	$_monitor_items['completed'] = 1;
    $_monitor_items['status'] = 'complete';
    write_monitor();
    
   
  

}else{
    echo "can't update this way".PHP_EOL;
}




function progress($download_size, $downloaded, $upload_size, $uploaded)
{
	
	global $start;
	global $_file_name;
	global $_monitor;
    global $_monitor_items;  
	global $_file_size;
	
	$_file_size = $download_size;
	
	$now = time();
	
	
	$elapsed_time = $now - $start;
	
	
	if($downloaded <= 0 || $elapsed_time == 0){
		return;
	}
	
    $percent      = (($downloaded/$download_size)*100);
    //$elapsed_time = $elapsed_time%86400;
    $velocita     = $downloaded/$elapsed_time;
	
	$_items_response['download_size'] = $download_size;
	$_items_response['downloaded']    = $downloaded;
	$_items_response['percent']       = $percent;
	//$_items_response['pid']           = getmypid();
	$_items_response['start']         = $start;
	$_items_response['elapsed']       = $elapsed_time;
	$_items_response['velocita']      = $velocita;
	$_items_response['file']          = $_file_name;
    $_items_response['completed']     = 0;
    
    
    $_monitor_items['download']       = $_items_response;
	
	if($elapsed_time%1 == 0){
		write_monitor();
	}
	
}

 

function write_monitor(){
    
    global $_monitor_items;
    global $_monitor;
    
    if(count($_monitor_items) > 0){
        write_file($_monitor, json_encode($_monitor_items), 'w');
    }    
}




?>