<?php
/*
Widget Name: Tasks
Widget URI: http://www.thingiverse.com/thing:35248
Version: 1.0
Description: gCodeViewer is a visual GCode visualizer, viewer and analyzer in your own browser! It works on any OS in almost any modern browser (chrome, ff, safari 6, opera, ie10 should work too). All you need to do - is drag your *.gcode file to the designated zone.
Author: FABteam
Author URI: http://www.thingiverse.com/hudbrog/designs
Widget Slug: tasks
*/
class Tasks_widget extends Widget {
    
   	public function __construct()
	{
	   
        parent::__construct();
	}
       
    
    public function content(){
        
        
        $CI =& get_instance();
        
        /**
		 * LOAD DATABASE
		 */
		$CI->load->model('tasks');
        
		$CI->load->helper('ft_date_helper');
		
		
		$_running = $CI->tasks->get_running();
		$_lasts   = $CI->tasks->get_lasts();
		
		$data['running'] = $_running;
		$data['lasts']   = $_lasts;
        
        
        $config['icon']             = 'fa fa-tasks';
		$config['togglebutton']     = 'true';
		$config['fullscreenbutton'] = 'true';
        
       	
        $this->initialize($config);
        
        $_html = $this->view('index', $data, TRUE);
        
        $_widget_tasks = $this->get('tasks', 'Tasks',  $_html, false, false);
                
        return $_widget_tasks;

    }
}



?>




