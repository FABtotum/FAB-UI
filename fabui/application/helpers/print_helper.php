<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_printer_busy'))
{
	/**
	 * 
	 * @return if there are tasks 
	 */
	function is_printer_busy($except = ''){
		
		$CI =& get_instance();
        
        
        $CI->load->database();
		$CI->load->model('tasks');
        
        
        $_print = $CI->tasks->get_running('make', 'print');
        $_scan  = $CI->tasks->get_running('make', 'scan');
		
		
		
		
		
		if($except == 'scan'){
			return $_print;
		}
		
		
		if($except == 'print'){
			return $_scan;
		}	

       
       return $_print != false || $_scan != false ;
       
       		
	}
	 
}