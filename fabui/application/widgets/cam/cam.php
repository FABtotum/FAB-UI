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
		$CI->load->helper('form');
		
        
		// ================================== init widget ============
		
        $config['icon']             = 'fa fa-camera';
		$config['togglebutton']     = 'true';
		$config['fullscreenbutton'] = 'true';
        $this->initialize($config);
		
		
		// =============================== load image settings ===========
		$settings_file = str_replace(basename(__FILE__),  'data/settings.json', __FILE__);
		
		if(!file_exists($settings_file)){

			shell_exec('sudo chmod 777 '.str_replace(basename(__FILE__),  'data', __FILE__));
						
			$CI->load->helper('file');
			write_file($settings_file, json_encode($this->get_default_settings()));
			
			
		}
		
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
		
        
        $_widget_tasks = $this->get('tasks', 'Raspi Cam',  $_html, false, false, null);
                
        return $_widget_tasks;

    }

	function get_default_settings(){
		
		return array(
			'encoding' => 'jpg',
			'width' => '640',
			'height' => '480',
			'iso' => '800',
			'quality' => '100',
			'brightness' => '50',
			'imxfx' => 'none',
			'contrast' => '0',
			'sharpness' => '0',
			'saturation' => '0',
			'awb' => 'auto',
			'ev' => '5',
			'exposure' => 'auto',
			'rotation' => '90',
			'metering' => 'average',
			 	
		);	
	}
}



?>




