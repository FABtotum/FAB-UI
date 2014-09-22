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
        
        
        $_print = $CI->tasks->get_running('create',  'print');
        $_scan  = $CI->tasks->get_running('scan',    'scan');
		
		
		
		
		
		if($except == 'scan'){
			return $_print;
		}
		
		
		if($except == 'print'){
			return $_scan;
		}	

       
       return $_print != false || $_scan != false ;
       
       		
	}
	 
}