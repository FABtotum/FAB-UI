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
			'safety'        => array('door'=>1, 'collision-warning'=>1),
			'switch'        => 0,
			'feeder'        => array('disengage-offset'=> 2, 'show' => false),
			'print'         => array('pre-heating' => array('extruder' => 130, 'bed' => 40), 'calibration' => 'homing'),
			'milling'       => array('layer-offset' => 12),
			//'e'             => 3048.1593,
			'a'             => 177.777778,
			'bothy'         => 'None',
			'bothz'         => 'None',
			'api'           => array(
				'keys' => array(
						$_SESSION['user']['id'] => ''
				)
			),
			'zprobe'        => array('disable'=>1, 'zmax'=>230),
			'settings_type' => 'default',
			'hardware'      => array('head' => array('type' => 'print_v2', 'description'=>'Printing Head V2', 'max_temp'=>250)),
			'custom'        => array(
				'overrides' => CONFIG_FOLDER . 'custom_overrides.txt',
				'invert_x_endstop_logic' => false,
                'camera_available' => false
			)
		);
		write_file(CONFIG_FOLDER . 'config.json', json_encode($dafault_config));
		//write_file(CONFIG_FOLDER . 'custom_config.json', json_encode($dafault_config));
		shell_exec('sudo chmod 0777 '.CONFIG_FOLDER . 'config.json');
		//shell_exec('sudo chmod 0777 '.CONFIG_FOLDER . 'custom_config.json');
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
		return isset($configs['hardware']['head']['type']) ? $configs['hardware']['head']['type'] : 'print_v2';
		
    
     }
	 
}
