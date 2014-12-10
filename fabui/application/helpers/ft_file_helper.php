<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('roundsize'))
{
	function roundsize($size){

		$i=0;

		$iec = array("B", "Kb", "Mb", "Gb", "Tb");

		while (($size/1024)>1) {
			$size=$size/1024;
			$i++;
		}
		return(round($size,2)." ".$iec[$i]);
	}
	 
}

if ( ! function_exists('print_type'))
{
	function print_type($file_path){       
        return strtolower(trim(shell_exec("sudo python /var/www/fabui/python/check_manufacturing.py ".$file_path)));
	}
	 
}


function get_file_extension($filename)
{
		$x = explode('.', $filename);
		return '.'.end($x);
}


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




function clean_temp($mode = 'day', $max = 1){
    
    
    $CI =& get_instance();
    
    $CI->load->helper('directory');
    $CI->load->helper('file');
    
    
    $directory = '/var/www/temp/';
    
    $files = directory_map($directory);
    
    $now = time();
    
    
    $files_to_take[] = 'picture.jpg';
    $files_to_take[] = 'fab_ui_safety.json';
    $files_to_take[] = 'faq.json';
	$files_to_take[] = 'instagram_feed.json';
	$files_to_take[] = 'instagram_hash.json';
	$files_to_take[] = 'twitter.json';
    
    foreach($files as $file){
        
        
        $file_date = get_file_info($directory.$file, 'date')['date'];
        $diff = $now - $file_date;
        
            
        $diff_min  = $diff / 60 ;
        $diff_hour = $diff_min / 60;
        $diff_days = $diff_hour / 24;
 
        switch($mode){
            case 'min':
                $remove = $diff_min > $max;
                break;
            case 'hour':
                $remove = $diff_hour > $max;
                break;
            case 'day':
                $remove = $diff_days > $max;
                break;
        }
        
        if($remove && !in_array($file, $files_to_take)){
            
            unlink($directory.$file);
        }   
    }
}




function gcode_analyzer($file_id){
	
	
	$command = 'sudo php /var/www/fabui/script/gcode_analyzer.php '.$file_id.' > /dev/null & echo $!';
	shell_exec($command);
	
}
