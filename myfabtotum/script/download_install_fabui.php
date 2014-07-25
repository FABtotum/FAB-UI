<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/myfabtotum/ajax/config.php';
require_once '/var/www/myfabtotum/ajax/lib/utilities.php';

/** GET ARGS FROM COMMAND LINE */
$_task_id = $argv[1];
$_folder  = $argv[2];
$_monitor = $argv[3];


$_myfab_local_version   = myfab_get_local_version();
$_myfab_remote_version  = myfab_get_remote_version();


$_file_name = $_folder.'myfab'.'.zip';
$_url       = MYFAB_DOWNLOAD_URL.MYFAB_DOWNLOAD_FILE;
//$_monitor   = MYFAB_UPDATE_MONITOR_FILE;


$do_update = $_myfab_local_version < $_myfab_remote_version ;


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
    extract_zip($_file_name, $_folder);
    
    
    $_monitor_items['extract']['percent'] = '100';
    $_monitor_items['extract']['completed'] = 1;
    write_monitor();
    
    
    
    
    /** INSTALLAZIONE FILES */
    
    $_monitor_items['status'] = 'installing';
    $_monitor_items['install']['percent'] = 0;
    $_monitor_items['install']['completed'] = 0;
    write_monitor();
    sleep(3);
   
    $_command_rename = 'sudo mv /var/www/recovery/update/temp/myfabtotum/ /var/www/myfabtotum_new/';
	//shell_exec($_command_rename);
    $_monitor_items['install']['percent'] = 25;
    write_monitor();
    sleep(3);
	
	$_command_rename_old_myfab = 'sudo mv /var/www/myfabtotum/ /var/www/myfabtotum_old/';
	//shell_exec($_command_rename_old_myfab);
    $_monitor_items['install']['percent'] = 50;
    write_monitor();
    sleep(3);
	
	$_command_rename_new_myfab = 'sudo mv /var/www/myfabtotum_new/ /var/www/myfabtotum/';
	//shell_exec($_command_rename_new_myfab);
    $_monitor_items['install']['percent'] = 75;
    write_monitor();
    sleep(3);
    
    $_command_symbolic_link = 'ln -s /var/www/upload/ /var/www/myfabtotum/upload';
    //shell_exec($_command_symbolic_link);
    $_monitor_items['install']['percent'] = 100;
    write_monitor();
    sleep(3);
    
    $_monitor_items['completed'] = 1;
    $_monitor_items['status'] = 'complete';
    write_monitor();
    
    
    
    
    $_command_finalize = 'sudo php /var/www/myfabtotum/script/finalize.php '.$_task_id.' updates > /dev/null & echo $! ';
    shell_exec($_command_finalize);
    
   
  

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