<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_folder  = $argv[2];
$_monitor = $argv[3];


$_marlin_local_version   = marlin_get_local_version();
$_marlin_remote_version  = marlin_get_remote_version();


$_file_name = $_folder.MARLIN_DOWNLOAD_FILE;
$_url       = MARLIN_DOWNLOAD_URL.$_marlin_remote_version.'/'.MARLIN_DOWNLOAD_FILE;

$do_update = $_marlin_local_version < $_marlin_remote_version ;


$_monitor_items = array();


$do_update = true;

if($do_update){
    
    $_monitor_items['completed'] = 0;
    $_monitor_items['status'] = 'downloading';
    /** CREATE MONITOR FILE */
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
    
    $_monitor_items['download']['percent'] = 100;
    sleep(3);
    
    $_monitor_items['download']['completed'] = 1;
    

        
    /** INSTALLAZIONE FILES */
    
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = 0;
    $_monitor_items['install']['completed'] = 0;
    write_monitor();
    sleep(2);
   
	/*this works!
	sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0   -U flash:w:/var/www/temp/Marlin.cpp.hex:i
	*/
	
	
	$_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = rand(5, 30);
    $_monitor_items['install']['completed'] = 0;
    write_monitor();
    sleep(2);
	
	
	/** LOG FLASH  */
	$log = TEMP_PATH.'flash_'.time().'.log';
	write_file($_data_file, '', 'w');
	chmod($_data_file, 0777);
	
   	$_command = 'sudo /usr/bin/avrdude -D -q -V -p atmega1280 -C /etc/avrdude.conf -c arduino -b 57600 -P  /dev/ttyAMA0 -U flash:w:'.$_file_name.':i > '.$log;
    shell_exec($_command);
		
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
	
	
	$percent      = (($downloaded/$download_size)*100);
	$elapsed_time = time() - $start;
    
    $elapsed_time = $elapsed_time%86400;
    $velocita     = $downloaded/$elapsed_time;
	
	$_items_response['download_size'] = $download_size;
	$_items_response['downloaded']    = $downloaded;
	$_items_response['percent']       = $percent;
	$_items_response['pid']           = getmypid();
	$_items_response['start']         = $start;
	$_items_response['elapsed']       = $elapsed_time%60;
	$_items_response['velocita']      = $velocita;
	$_items_response['file']          = $_file_name;
    $_items_response['completed']     = 0;
    
    $_monitor_items['download']       = $_items_response;
	write_monitor();
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