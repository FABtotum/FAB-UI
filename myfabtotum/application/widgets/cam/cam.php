<?php
/*
Widget Name: Cam
Widget URI: http://www.thingiverse.com/thing:35248
Version: 1.0
Description: Cam screenshots
Author: FABteam
Author URI: http://www.thingiverse.com/hudbrog/designs
Widget Slug: tasks
*/
class Cam_widget extends Widget {
    
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
        
        
        $config['icon']             = 'fa fa-camera';
		$config['togglebutton']     = 'true';
		$config['fullscreenbutton'] = 'true';
        
       	
        $this->initialize($config);
        
        if(!file_exists('/var/www/temp/picture.jpg')){
            
            //$_image   = '/var/www/temp/picture.jpg';
            //$_command = 'sudo raspistill -t 2000 -o '.$_image.' ' ;
            //shell_exec ( $_command );
            
        }
        
        
        $_html = $this->view('index', $data, TRUE);
        
        $_js  = $this->view('js', '', TRUE);
        $_css = $this->view('css', '', TRUE);
        
        
        $CI->layout->add_js_in_page(array('data'=> $_js, 'comment' => 'CAM WIDGET IN PAGE JS'));
        $CI->layout->add_css_in_page(array('data'=> $_css, 'comment' => 'CAM WIDGET IN PAGE CSS'));
        
        
        
        $_widget_tasks = $this->get('tasks', 'RaspiCam',  $_html, false, false);
                
        return $_widget_tasks;

    }
}



?>




