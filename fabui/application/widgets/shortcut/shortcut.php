<?php
/*
Widget Name: Shortcut
Widget URI: http://www.thingiverse.com/thing:35248
Version: 1.0
Description: gCodeViewer is a visual GCode visualizer, viewer and analyzer in your own browser! It works on any OS in almost any modern browser (chrome, ff, safari 6, opera, ie10 should work too). All you need to do - is drag your *.gcode file to the designated zone.
Author: FABteam
Author URI: http://www.thingiverse.com/hudbrog/designs
Widget Slug: shortcut
*/
class Shortcut_widget extends Widget {
    
    public function __construct()
	{
        parent::__construct();
	}
    
     public function content(){
        
        $CI =& get_instance();
     
	 
	 
	 	$shortcut[] = array('controller'=>'');
	 
	 
        $_html   = $this->view('index', '', TRUE);
        $_widget = $this->get('shortcut', 'Shortcut', $_html, true, false);
        $_js     = $this->view('js', '', TRUE);
        
        $CI->layout->add_js_in_page(array('data'=> $_js, 'comment' => 'SHORTCUT WIDGET IN PAGE JS'));
        
        return $_widget;
  
     }
}

?>