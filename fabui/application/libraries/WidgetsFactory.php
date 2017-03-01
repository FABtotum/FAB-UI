<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class WidgetsFactory {
    
    
    private $_path = 'widgets/';
    
    
    public function __construct()
	{
        $CI =& get_instance();
	    $CI->load->library('Widget');
	   
    }
    
    public function load($class){
               
        $_file_class = APPPATH.$this->_path.$class.'/'.$class.'.php';  
		
        if(!file_exists($_file_class)){
            show_error(' Unable to load the requested file: '.$_file_class);
        }
    
       //load widget file class
       include_once ($_file_class);

       /**
        *  NAME CLASS MUST BE Name_widget es: Foo_widget
        * */
       $class_name = ucfirst($class).'_widget';
       
       	if ( ! class_exists($class_name))
		{
            show_error("Class ".$class_name." doesn't exist");
		}
      
        return new $class_name;
    }
}
?>