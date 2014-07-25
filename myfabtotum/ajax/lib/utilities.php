<?php
/** FIRST DOWNLOAD FILE */
require_once '/var/www/myfabtotum/ajax/config.php';

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
    return strtolower(trim(shell_exec("sudo python /var/www/myfabtotum/python/check_manufacturing.py ".$file_path)));
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
    
    
    $_command_macro  = 'sudo python /var/www/myfabtotum/python/gmacro.py '.$_macro_name.' '.$_macro_trace.' '.$_macro_response.' '.$_no_wait.' & echo $!';
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
	$_local_version = file_get_contents(MYFAB_LOCAL_VERSION_PATH, FILE_USE_INCLUDE_PATH );
	return $_local_version;
}

/**
 *
 */
function marlin_get_local_version(){
	$_local_version = file_get_contents(MARLIN_LOCAL_VERSION_PATH, FILE_USE_INCLUDE_PATH );
	return $_local_version;

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

?>