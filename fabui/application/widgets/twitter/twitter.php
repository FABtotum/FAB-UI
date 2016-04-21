<?php
/*
Widget Name: Twitter
Widget URI: http://www.fabtotum.com
Version: 1.0
Description: Get latest FABtotum's tweet
Author: FABteam
Widget Slug: Twitter
*/
class Twitter_widget extends Widget {
    
    public function __construct()
	{
        parent::__construct();
	}
    
     public function content(){
        
        $CI =& get_instance();
     	
		
		$config['icon']     = 'fa fa-twitter';
		$config['sortable'] = 'false';
		$config['fullscreenbutton'] = 'true';
		$config['load']     = widget_url('twitter').'ajax/feed.php';
		
		$this->initialize($config);
	 
	 
        $_html   = $this->view('index', '', TRUE);
        $_widget = $this->get('twitter', 'Latest Tweets', $_html, false, false);
        //$_js     = $this->view('js', '', TRUE);
        
        $CI->layout->add_css_in_page(array('data'=> $this->view('css', '', TRUE), 'comment' => 'Twitter CSS'));
		$CI->layout->add_js_in_page(array('data'=> $this->view('js', '', TRUE), 'comment' => 'Twitter JS'));
        
        return $_widget;
  
     }
}

?>