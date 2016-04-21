<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Controller {

	private static $instance;
		
	public $_type = 'module'; 
	
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		self::$instance =& $this;
		       
		$this->layout =& load_class('Layout', 'core', 'FT_');
		
		//check if is logged in
        if(strtolower(get_class($this)) != 'login'){
            is_logged_in();
        }
		
	}
	
	public static function &get_instance()
	{
		return self::$instance;
	}
    
}
