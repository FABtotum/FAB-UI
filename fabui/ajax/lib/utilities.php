<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';

function site_url($url){
    return SITE_URL.$url;
}
function base_url(){
    
}

function host_name(){
    return 'http://'.$_SERVER[HTTP_HOST].'/';
}

/** */
function mysql_to_human($date, $format = 'YYYY/mm/dd')
{
	
	if($date == '')
		return '';
	
	$temp = explode(' ', $date);
	
	$date = $temp[0];
	
	$minute_hours = isset($temp[1]) ? $temp[1] : '';
	
	$temp_date = explode('-', $date);
	
	
	$date = $temp_date[2].'/'.$temp_date[1].'/'.$temp_date[0];
	
	
	if(isset($temp[1]) && $temp[1] != ''){
		$date .= ' '.$temp[1];
	}
	
	return $date;
	
}


/** */
function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE){
	if ( ! $fp = @fopen($path, $mode))
	{
		return FALSE;
	}

	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);

	return TRUE;
}


/** */
function print_type($file_path){       
    return strtolower(trim(shell_exec("sudo python /var/www/fabui/python/check_manufacturing.py ".$file_path)));
}



/** */
function macro($_macro_name, $_wait = TRUE){
    
    $_macro_response = '/var/www/temp/'.$_macro_name.'_'.time().'.log';
    $_macro_trace    = '/var/www/temp/'.$_macro_name.'_'.time().'.trace';
    
    
    write_file($_macro_trace, '', 'w');
    chmod($_macro_trace, 0777);
   
    write_file($_macro_response, '', 'w');
    chmod($_macro_response, 0777);
    
    $_no_wait = '';
    
    if(!$_wait){
        $_no_wait = ' > /dev/null';
    }
    
    
    $_command_macro  = 'sudo python /var/www/fabui/python/gmacro.py '.$_macro_name.' '.$_macro_trace.' '.$_macro_response.' '.$_no_wait.' & echo $!';
	$_output_macro   = shell_exec ( $_command_macro );
	$_pid_macro      = trim(str_replace('\n', '', $_output_macro));
    
    
    if(!$_wait){
        return true;
    }
    
    
    /** WAIT MACRO TO FINISH */
	while(str_replace(PHP_EOL, '', file_get_contents($_macro_response)) == ''){   
		sleep(0.5);
	}
    
    
    $_return_array['trace']    = file_get_contents($_macro_trace, FILE_USE_INCLUDE_PATH);
    $_return_array['response'] = file_get_contents($_macro_response, FILE_USE_INCLUDE_PATH);
    
    
    unlink($_macro_trace);
    unlink($_macro_response);
    
    return $_return_array;
    
}



/** */
function minify($string){
    
    $buffer = $string;
		
    $search = array(
				'/\n/',			// replace end of line by a space
				'/\>[^\S ]+/s',		// strip whitespaces after tags, except space
				'/[^\S ]+\</s',		// strip whitespaces before tags, except space
				'/(\s)+/s'		// shorten multiple whitespace sequences
    );
		
    $replace = array(
				' ',
				'>',
				'<',
				'\\1'
    );
		
    return preg_replace($search, $replace, $buffer);
}



/** */
function roundsize($size){

		$i=0;

		$iec = array("B", "Kb", "Mb", "Gb", "Tb");

		while (($size/1024)>1) {
			$size=$size/1024;
			$i++;
		}
		return(round($size,2)." ".$iec[$i]);
}

/** */
function get_file_extension($filename)
{
		$x = explode('.', $filename);
		return '.'.end($x);
}


/** */
function get_name($full_path){
    
    
    $x = explode('/', $full_path);
    return end($x);
    
}


/** */
function set_filename($path, $filename)
{

		if ( ! file_exists($path.$filename))
		{
			return $filename;
		}

        $ext = get_file_extension($filename);

		$filename = str_replace($ext, '', $filename);
        
        
        

		$new_filename = '';
		for ($i = 1; $i < 100; $i++)
		{
			if ( ! file_exists($path.$filename.$i.$ext))
			{
				$new_filename = $filename.$i.$ext;
				break;
			}
		}

		if ($new_filename == '')
		{
			
			return FALSE;
		}
		else
		{
			return $new_filename;
		}
}


/**
 * 
 */
function myfab_get_remote_version(){
	$_remote_version =  file_get_contents(MYFAB_REMOTE_VERSION_URL);
	return $_remote_version;	
}

/**
 * 
 * @return unknown
 */
function myfab_get_local_version(){
	
	
	/** LOAD DB */
	$db = new Database();
	
	/** GET TASK FROM DB */
	$_version = $db->query('select sys_configuration.value from sys_configuration where sys_configuration.key="fabui_version"');
	return $_version['value'];
}

/**
 *
 */
function marlin_get_local_version(){

	/** LOAD DB */
	$db = new Database();
	/** GET TASK FROM DB */
	$_version = $db->query('select sys_configuration.value from sys_configuration where sys_configuration.key="fw_version"');
	$db->close();
	return $_version['value'];
	
	

}


/**
 * 
 */
function marlin_get_remote_version(){
	$_remote_version =  file_get_contents(MARLIN_REMOTE_VERSION_URL);
	return $_remote_version;
}

/**
 * 
 */
function is_internet_avaiable(){
    return !$sock = @fsockopen('www.google.com', 80, $num, $error, 5) ? false : true;    
}







function wlan(){
    
    
    
    $_ethernet_result = shell_exec("sudo ifconfig wlan0");
	
	$interfaces = array();
	
	foreach (preg_split("/\n\n/", $_ethernet_result) as $int) {
	
		preg_match("/^([A-z]*\d)\s+Link\s+encap:([A-z]*)\s+HWaddr\s+([A-z0-9:]*).*" .
				"inet addr:([0-9.]+).*Bcast:([0-9.]+).*Mask:([0-9.]+).*" .
				"MTU:([0-9.]+).*Metric:([0-9.]+).*" .
				"RX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*frame:([0-9.]+).*" .
				"TX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*carrier:([0-9.]+).*" .
				"RX bytes:([0-9.]+).*\((.*)\).*TX bytes:([0-9.]+).*\((.*)\)" .
				"/ims", $int, $regex);
	
		if (!empty($regex)) {
	
			$interface = array();
			
			$interface['name']      = trim($regex[1]);
			$interface['type']      = trim($regex[2]);
			$interface['mac']       = trim($regex[3]);
			$interface['ip']        = trim($regex[4]);
			$interface['broadcast'] = trim($regex[5]);
			$interface['netmask']   = trim($regex[6]);
			$interface['mtu']       = trim($regex[7]);
			$interface['metric']    = trim($regex[8]);
	
			$interface['rx']['packets']  = (int) $regex[9];
			$interface['rx']['errors']   = (int) $regex[10];
			$interface['rx']['dropped']  = (int) $regex[11];
			$interface['rx']['overruns'] = (int) $regex[12];
			$interface['rx']['frame']    = (int) $regex[13];
			$interface['rx']['bytes']    = (int) $regex[19];
			$interface['rx']['hbytes']   = (int) $regex[20];
	
			$interface['tx']['packets']  = (int) $regex[14];
			$interface['tx']['errors']   = (int) $regex[15];
			$interface['tx']['dropped']  = (int) $regex[16];
			$interface['tx']['overruns'] = (int) $regex[17];
			$interface['tx']['carrier']  = (int) $regex[18];
			$interface['tx']['bytes']    = (int) $regex[21];
			$interface['tx']['hbytes']   = (int) $regex[22];
	
			$interfaces[] = $interface;
		}
	}
	
	
	return count($interfaces) == 1 ? $interfaces[0]: $interfaces;
    

}



?>