<?php
/*
Widget Name: Shortcut
Widget URI: http://www.fabtotum.com
Version: 1.0
Description: Get latest posts from FABtotum's instagram account
Author: FABteam
Widget Slug: Instagram
*/
class Instagram_widget extends Widget {
    
    public function __construct()
	{
        parent::__construct();
	}
    
     public function content(){
        
        $CI =& get_instance();
     
	 
	 	$config['icon'] = 'fa fa-instagram'; 
		$config['fullscreenbutton'] = 'true';
		
		$config['load'] = widget_url('instagram').'ajax/feed.php';
	 
	 	$this->initialize($config);
	 	
	 
        $_html   = $this->view('index', '', TRUE);
        $_widget = $this->get('instagram', 'Instagram', $_html, false, false);
        //$_js     = $this->view('js', '', TRUE);
        
        //$CI->layout->add_js_in_page(array('data'=> $_js, 'comment' => 'SHORTCUT WIDGET IN PAGE JS'));
        $CI->layout->add_js_file(array('src'=>widget_url('instagram').'assets/js/masonry.pkgd.min.js', 'external'=>true));
		$CI->layout->add_js_file(array('src'=>widget_url('instagram').'assets/js/imagesloaded.pkgd.min.js', 'external'=>true));
		$CI->layout->add_css_in_page(array('data'=> $this->view('css', '', TRUE), 'comment' => 'instagram CSS'));
		$CI->layout->add_js_in_page(array('data'=> $this->view('js', '', TRUE), 'comment' => 'instagram JS'));
        
        return $_widget;
  
     }
}

?>