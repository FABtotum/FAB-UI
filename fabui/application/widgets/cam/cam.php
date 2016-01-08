<?php
/*
Widget Name: Cam
Widget URI: http://www.thingiverse.com/thing:35248
Version: 1.0
Description: Cam screenshots
Author: FABteam
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
		$CI->load->helper('form');
		
		
		
		
		$_running = $CI->tasks->get_running();
		$_lasts   = $CI->tasks->get_lasts();
		
		$data['running'] = $_running;
		$data['lasts']   = $_lasts;
        
        
		// ================================== init widget ============
		
        $config['icon']             = 'fa fa-camera';
		$config['togglebutton']     = 'true';
		$config['fullscreenbutton'] = 'true';
        $this->initialize($config);
		
		
		// =============================== load image settings ===========
		$settings_file = str_replace(basename(__FILE__),  'data/settings.json', __FILE__);
		shell_exec('sudo chmod 777 '.$settings_file);
		$settings = json_decode(file_get_contents($settings_file), TRUE);
		
		$data['settings'] = $settings;
		
		if(!isset($data['settings']['flip'])){
			$data['settings']['flip'] = 'hf';
		}

			
				
		// ============================== load parameters options list
		include str_replace(basename(__FILE__),  'data/config.php', __FILE__);
		$data['params'] = $params;
		
        $_html = $this->view('index', $data, TRUE);
        
        $_js  = $this->view('js', '', TRUE);
        $_css = $this->view('css', '', TRUE);
        
        
        $CI->layout->add_js_in_page(array('data'=> $_js, 'comment' => 'CAM WIDGET IN PAGE JS'));
        $CI->layout->add_css_in_page(array('data'=> $_css, 'comment' => 'CAM WIDGET IN PAGE CSS'));
        
        
		//$tabs[] = array('name'=>'Photo', 'icon'=>'fa-camera', 'href'=>'photo-tab');
		//$tabs[] = array('name'=>'Settings', 'icon'=>'fa-cogs', 'href'=>'settings-tab');
		
        
        $_widget_tasks = $this->get('tasks', 'RaspiCam',  $_html, true, false, null);
                
        return $_widget_tasks;

    }
}



?>




