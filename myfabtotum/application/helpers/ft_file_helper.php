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
        return strtolower(trim(shell_exec("sudo python /var/www/myfabtotum/python/check_manufacturing.py ".$file_path)));
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

