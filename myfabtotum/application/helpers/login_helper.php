<?php
/**
 *
 * @return boolean
 */
function is_logged_in() {

	// Get current CodeIgniter instance
	//$CI =& get_instance();

	// We need to use $CI->session instead of $this->session
	//$email     = $CI->nativesession->get('email');
	//$logged_in = $CI->nativesession->get('logged_in');
    
  
    
    //print_r($_SESSION);
    
    //print_r($CI->nativesession); exit();

      
      if(!isset($_SESSION['email']) || $_SESSION['email'] == '' || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == ''){  
	//if (!isset($email) && !isset($logged_in) || $logged_in == FALSE) {

		    
	       /*
			$CI->nativesession->delete('email');
			$CI->nativesession->delete('logged_in');
			$CI->nativesession->delete('first_name');
			$CI->nativesession->delete('');
            */
            /*
            unset( $_SESSION['email'] );
            unset( $_SESSION['logged_in'] );
            unset( $_SESSION['first_name'] );
            unset( $_SESSION['email'] );
            */
            
	

		//if(strtolower(get_class($CI)) != 'login')
		  	redirect('login/out');


	} else {

		return true;
	}
}

