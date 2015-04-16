<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_folder  = $argv[2];
$_monitor = $argv[3];



$_myfab_local_version   = myfab_get_local_version();
$_myfab_remote_version  = myfab_get_remote_version();


$_file_name = $_folder.'fabui'.'.zip';
$_url       = MYFAB_DOWNLOAD_URL.$_myfab_remote_version.'/'.MYFAB_DOWNLOAD_FILE;
//$_monitor   = MYFAB_UPDATE_MONITOR_FILE;


//$do_update = $_myfab_local_version <= $_myfab_remote_version ;


$do_update = true; 

$_monitor_items = array(); 

if($do_update){
    
    $_monitor_items['completed'] = 0;
    $_monitor_items['status'] = 'downloading'; 
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
    
    
    $_monitor_items['download']['completed'] = 1;
    
    
    /** ESTRAZIONE FILES  */
    $_monitor_items['status'] = 'extracting';
    $_monitor_items['extract']['completed'] = 0;
    $_monitor_items['extract']['percent'] = 0;
    write_monitor();
    sleep(3);
    
    $_monitor_items['extract']['percent'] = '20';
    write_monitor();
    sleep(2);
    
    $_monitor_items['extract']['percent'] = '60';
    write_monitor();
	
	
	
	/** EXTRAC THE ZIP */
    extract_zip($_file_name, $_folder.'temp/');
    
    
    $_monitor_items['extract']['percent'] = '100';
    $_monitor_items['extract']['completed'] = 1;
    write_monitor();
    
    
   
    
    /** INSTALLAZIONE FILES */
    
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = 0;
    $_monitor_items['install']['completed'] = 0;
    write_monitor();
    sleep(2);
	
	
	/** CHECK IF EXIST SCRIPT FILE TO EXEC */
	if(file_exists($_folder.'temp/install.php')){
		/** EXEC FILE */
		$_command_exec = 'sudo php '.$_folder.'temp/install.php'; 
		shell_exec($_command_exec);		
	}
	
	
	$_monitor_items['install']['percent'] = 25;
    write_monitor();
    sleep(2);
	
	
	
	
	/** CHECK IF EXIST FOLDER FABUI FOR THE UPDATE */
	if(file_exists($_folder.'temp/fabui')){
		
		$_command_copy = 'cp -rvf '.$_folder.'temp/fabui /var/www/';
		shell_exec($_command_copy);
		
	}

	
	$_monitor_items['install']['percent'] = 50;
    write_monitor();
    sleep(3);
	
	
	
	
	/** CHECK IF EXIST FOLDER RECOVERY FOR THE UPDATE */
	if(file_exists($_folder.'temp/recovery')){

		$_command_copy = 'cp -rvf '.$_folder.'temp/recovery /var/www/';
		shell_exec($_command_copy);
		
	}
	
	
	/** CHECK IF EXIST FOLDER SQL */
	if(file_exists($_folder.'temp/sql')){
		
		
		foreach(glob($_folder.'temp/sql/*') as $file_sql) 
		{
				/** EXEC SQL FILES */
				if(file_exists($file_sql)){
					$_exec_sql = 'sudo mysql -u '.DB_USERNAME.' -p'.DB_PASSWORD.' -h '.DB_HOSTNAME.'  < '.$file_sql;
					shell_exec($_exec_sql);		
	
				} 
		}
		
	} 
	 
	
	
	
	
	$_monitor_items['install']['percent'] = 100;
    write_monitor();
    sleep(3);
	
	
	
	/** DELETE OLD VERSIONS */
    shell_exec('sudo rm -rf /var/www/recovery_old/');
    shell_exec('sudo rm -rf /var/www/fabui_old/');

	/** FILE PERMISSIONS */
	shell_exec('sudo chmod 666 '.FABUI_PATH.'config/config.json');
	
	shell_exec('sudo chmod 777 '.FABUI_PATH.'application/layout/assets/img/avatar');
	
	/** simbolic link */
	shell_exec('sudo ln -s /var/www/upload /var/www/fabui/upload');
	
	
    $_monitor_items['completed'] = 1;
    $_monitor_items['status'] = 'complete';
    write_monitor();
    
	
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
    
   
  

}else{
    echo "can't update this way".PHP_EOL;
}




function progress($download_size, $downloaded, $upload_size, $uploaded)
{
	
	global $start;
	global $_file_name;
	global $_monitor;
    global $_monitor_items;  
	
	
	$now = time();
	
	$percent      = (($downloaded/$download_size)*100);
	$elapsed_time = $now - $start;
    
    //$elapsed_time = $elapsed_time%86400;
    $velocita     = $downloaded/$elapsed_time;
	
	$_items_response['download_size'] = $download_size;
	$_items_response['downloaded']    = $downloaded;
	$_items_response['percent']       = $percent;
	$_items_response['pid']           = getmypid();
	$_items_response['start']         = $start;
	$_items_response['elapsed']       = $elapsed_time;
	$_items_response['velocita']      = $velocita;
	$_items_response['file']          = $_file_name;
    $_items_response['completed']     = 0;
    
    
    $_monitor_items['download']       = $_items_response;
	
	if($elapsed_time%3 == 0){
		write_monitor();
	}
	
}

 

function extract_zip($source, $destination){
	

	$zip = new ZipArchive;
	
	$res = $zip->open($source);
	
	if ($res === TRUE) {
	
		$zip->extractTo($destination);
		$zip->close();
		return true;
	} else {
		return false;
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