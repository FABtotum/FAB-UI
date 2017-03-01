<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/lib/config.php';
require_once '/var/www/lib/database.php';
require_once '/var/www/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_folder  = $argv[2];
$_monitor = $argv[3];

$file_size = 0;

$_marlin_local_version   = marlin_get_local_version();
$_marlin_remote_version  = marlin_get_remote_version();



$_marlin_remote_version = 2;

$isZip = url_exist(MARLIN_DOWNLOAD_URL.$_marlin_remote_version.'/'.MARLIN_DOWNLOAD_FILE_ZIP);

$_url = $isZip ? MARLIN_DOWNLOAD_URL.$_marlin_remote_version.'/'.MARLIN_DOWNLOAD_FILE_ZIP : MARLIN_DOWNLOAD_URL.$_marlin_remote_version.'/'.MARLIN_DOWNLOAD_FILE;

$_file_name = $isZip ?  $_folder.MARLIN_DOWNLOAD_FILE_ZIP : $_folder.MARLIN_DOWNLOAD_FILE;


$do_update = $_marlin_local_version < $_marlin_remote_version ;


$_monitor_items = array();


$do_update = true;

if($do_update){
	
	
    $_monitor_items['completed'] = 0;
    $_monitor_items['status'] = 'downloading';
	$_monitor_items['pid'] = getmypid();
	$_monitor_items['folder'] = $_folder;
   
   
    /** CREATE MONITOR FILE */
    echo "Start download...".PHP_EOL;
    
    write_monitor();
    sleep(3);
	
    $_target_file = fopen( $_file_name, 'w+') or die("can't open file");
    $start = time();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
    curl_setopt($ch, CURLOPT_NOPROGRESS, false); // needed to make progress function work
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_FILE, $_target_file );
   
    $html  = curl_exec($ch);
    curl_close($ch);
    
	$download_elapsed_time = time() - $start;
	
    $_monitor_items['download']['percent'] = 100;
    sleep(3);
    $_monitor_items['download']['completed'] = 1;
    

        
    /** INSTALLAZIONE FILES */
    
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = 0;
    $_monitor_items['install']['completed'] = 0;
	
    echo "Package file (".$file_size.") downloaded in ".($download_elapsed_time%86400).PHP_EOL;
    write_monitor();
    sleep(2);
   
	/*this works!
	sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0   -U flash:w:/var/www/temp/Marlin.cpp.hex:i
	*/
	
	
	$_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = rand(5, 30);
    $_monitor_items['install']['completed'] = 0;
    
    echo "Flashing firmware. Please don't turn off the printer until the operation is completed".PHP_EOL;
    write_monitor();
    sleep(2);
	
	if($isZip){
		/** EXTRAC THE ZIP */
		extract_zip($_file_name, $_folder);
		$_file_name = $_folder.'firmware/'.MARLIN_DOWNLOAD_FILE;
		
	}
	
	$_hex_file = $isZip ? $_folder.'firmware/'.MARLIN_DOWNLOAD_FILE : $_folder.MARLIN_DOWNLOAD_FILE;
	
	/** LOG FLASH  */
	$log = TEMP_PATH.'flash_'.time().'.log';
	write_file($log, '', 'w');
	chmod($log, 0777);
	
	
   	$_command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:'.$_hex_file.':i > '.$log;
    shell_exec($_command);
	
	
	
	sleep(1);
	
	
	
	//boot
	include '/var/www/fabui/script/boot.php';
	
	/** install file */
	if(file_exists($_folder.'firmware/install.php')){
		include $_folder.'firmware/install.php';
	}
	
		
	/** UPDATE VERSION  */
	/** LOAD DB */
	$db = new Database();
	/** UPDATE TASK */
	$_data_update = array();
	$_data_update['value'] = $_marlin_remote_version;
	$db->update('sys_configuration', array('column' => 'sys_configuration.key', 'value' => 'fw_version', 'sign' => '='), $_data_update);
	$db->close();
	
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent']   = 50;
    $_monitor_items['install']['completed'] = 0;
    write_monitor();
    sleep(10);
    
    $_command_finalize = 'sudo php /var/www/fabui/script/finalize.php '.$_task_id.' update_fw > /dev/null & echo $! ';
    shell_exec($_command_finalize);   
  

    $_monitor_items['completed'] = 1;
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent']   = 100;
    $_monitor_items['install']['completed'] = 1;
    write_monitor();
    sleep(3);

  
}else{
    echo "don't update".PHP_EOL;
}




function progress($download_size, $downloaded, $upload_size, $uploaded)
{
	
	global $start;
	global $_file_name;
	global $_monitor;
    global $_monitor_items; 
	global $file_size; 
	
	$file_size = $download_size;
	
	$elapsed_time = time() - $start;
		
	if($downloaded <= 0 || $elapsed_time == 0){
		return;
	}
	
	
	$percent      = (($downloaded/$download_size)*100);
    
    $elapsed_time = $elapsed_time%86400;
    $velocita     = $downloaded/$elapsed_time;
	
	$_items_response['download_size'] = $download_size;
	$_items_response['downloaded']    = $downloaded;
	$_items_response['percent']       = $percent;
	//$_items_response['pid']           = getmypid();
	$_items_response['start']         = $start;
	$_items_response['elapsed']       = $elapsed_time%60;
	$_items_response['velocita']      = $velocita;
	$_items_response['file']          = $_file_name;
    $_items_response['completed']     = 0;
    
    $_monitor_items['download']       = $_items_response;
	write_monitor();
}




function write_monitor(){
    
    global $_monitor_items;
    global $_monitor;
    
    
    if(count($_monitor_items) > 0){
        write_file($_monitor, json_encode($_monitor_items), 'w');
    }
    
}





?>