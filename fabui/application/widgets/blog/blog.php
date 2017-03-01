<?php
/*
Widget Name: Blog
Widget URI: http://www.fabtotum.com
Version: 1.0
Description: Fabtotum Development's Blog Feed
Author: FABteam
Widget Slug: blog
*/
class Blog_widget extends Widget {
    
    public function __construct()
	{
        parent::__construct();
	}
    
     public function content(){
        
        $CI =& get_instance();
     
	 
	 	$config['icon'] = 'fa fa-comments';
	 	$config['load'] = widget_url('blog').'ajax/feed.php';
		$config['fullscreenbutton'] = 'true';	
	 	$this->initialize($config);
	 	 
        $_html   = $this->view('index', '', TRUE);
        $_widget = $this->get('blog', 'Development Blog', $_html, false, false);

        
        return $_widget;
  
     }
}



?>