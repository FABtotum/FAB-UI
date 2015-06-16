<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */



class Module extends CI_Controller {

	private static $instance;
	
	public $_type = 'module'; 
	
	

	/**
	 * Constructor
	 */
	public function __construct()
	{
		
		parent::__construct();
		
		self::$instance =& $this;
		
		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		/*
        foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		//load Loader class
		$this->load =& load_class('Loader', 'core');
		
		$this->load->initialize();
		*/
		//load Layout Class
        
        $CI =& get_instance();
        
        /*
        if(isset($_SESSION['language']) && $_SESSION['language'] != ''){
            
            return $_SESSION['language'];
            
        }else{
            
            $CI->load->database();
            $CI->load->model('configuration');
            $languages = json_decode($CI->configuration->get_config_value('languages'),TRUE);
            $language = $CI->configuration->get_config_value('language');     
            $_SESSION['language'] = $languages[$language];
        }
	
    */
		$this->layout =& load_class('Layout', 'core', 'FT_');
		
		//check if is logged in
        
        if(strtolower(get_class($CI)) != 'login'){
            is_logged_in();
        }
		
		
		$this->layout->set_layout_title(get_class($CI));
		$this->layout->set_setup_wizard(need_setup_wizard());
        

	}

	public static function &get_instance()
	{
		return self::$instance;
	}
    
   
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */