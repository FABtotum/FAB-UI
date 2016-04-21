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
        
        
        $_print = $CI->tasks->get_running('make',  'print');
        $_scan  = $CI->tasks->get_running('make',    'scan');
		
		if($except == 'scan'){
			return $_print;
		}
		
		
		if($except == 'print'){
			return $_scan;
		}	

       
       return $_print != false || $_scan != false ;
       
       		
	}
	 
}



if ( ! function_exists('create_default_config'))
{
	/**
	 * 
	 * @return if there are tasks 
	 */
	function create_default_config($except = ''){
		
		$CI =& get_instance();
		
		$CI->load->helper('file');
		
		$dafault_config = array(
			'color'         => array('r'=>255, 'g'=>255, 'b'=>255),
			'safety'        => array('door'=>0, 'collision-warning'=>1),
			'switch'        => 0,
			'feeder'        => array('disengage-offset'=> 2, 'show' => true),
			'milling'       => array('layer-offset' => 12),
			'e'             => 3048.1593,
			'a'             => 177.777778,
			'bothy'         => 'None',
			'bothz'         => 'None',
			'api'           => array('keys' => array()),
			'zprobe'        => array('disbale'=>0, 'zmax'=>206),
			'settings_type' => 'default',
			'hardware'      => array('head' => array('type' => 'hybrid', 'description'=>'Hybrid Head', 'max_temp'=>230))
		);
		
		write_file(CONFIG_FOLDER . 'config.json', json_encode($dafault_config));
        
     }
	 
}


if ( ! function_exists('get_head'))
{
	/**
	 * 
	 * @return setted head
	 */
	function get_head(){
		
		$configs = json_decode(file_get_contents(CONFIG_FOLDER . 'config.json'), true);
		
		return isset($configs['hardware']['head']['type']) ? $configs['hardware']['head']['type'] : 'hybrid';
		
    
     }
	 
}