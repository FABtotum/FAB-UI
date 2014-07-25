<?php 


function myfab_get_remote_version(){
	//return;
	// Get current CodeIgniter instance
	$CI =& get_instance();
	
	//load configuration file
	$CI->config->load('myfab', TRUE);
    
    
    $context = stream_context_create(array('http'=> array('timeout' => 5)));
	
	$_remote_url = $CI->config->item('myfab_remote_version_url', 'myfab');
	
	$_version = file_get_contents($_remote_url, false, $context);
	
	return $_version;
	
}


function myfab_get_local_version(){
	
	// Get current CodeIgniter instance
	$CI =& get_instance();
	
	//load configuration file
	$CI->config->load('myfab', TRUE);
	
	$_local_path = $CI->config->item('myfab_local_version_path', 'myfab');
	
	$_version = file_get_contents($_local_path, FILE_USE_INCLUDE_PATH );
	
	return $_version;
}




function marlin_get_remote_version(){
    
	// Get current CodeIgniter instance
	$CI =& get_instance();

	//load configuration file
	$CI->config->load('myfab', TRUE);
    
    $context = stream_context_create(array('http'=> array('timeout' => 5)));

	$_remote_url = $CI->config->item('marlin_remote_version_url', 'myfab');
	
	$_version = file_get_contents($_remote_url, false, $context);

    //echo 'remote url: '.$_remote_url;
    
    

	return $_version;

}


function marlin_get_local_version(){

	// Get current CodeIgniter instance
	$CI =& get_instance();

	//load configuration file
	$CI->config->load('myfab', TRUE);

	$_local_path = $CI->config->item('marlin_local_version_path', 'myfab');
	
	$_version = file_get_contents($_local_path, FILE_USE_INCLUDE_PATH );
    

	return $_version;
}





function myfab_update_list(){
	
    //return;
	//check if there's a myfab update
    
    // Get current CodeIgniter instance
	//$CI =& get_instance();
	
	$_update_list = array();
    
    /*$_update_check = $CI->nativesession->get('update_check');*/
    
    $_update_check = isset($_SESSION['update_check']) ? $_SESSION['update_check'] : false ;
    
   
    
    if(!$_update_check){
        
        if(is_internet_avaiable()){
    	
        	$myfab_update  = myfab_get_local_version() < myfab_get_remote_version();
        	
        	$marlin_update = marlin_get_local_version() < marlin_get_remote_version();
        	
        	
        	if($myfab_update){
        		array_push($_update_list, array('name' => 'myfab', 'url' => site_url('updates'), 'description' => 'A new update of myFab is avaiable!' ));
        	}
        	
        	if($marlin_update){
        		array_push($_update_list, array('name' => 'marlin', 'url' => site_url('updates'), 'description' => 'A new update of Marlin is avaiable!' ));
        	}
    	
        }
        
        
        $data = array(
				'update_check' => true,
				'update_list'  => $_update_list
		);


        $_SESSION['update_check'] = true;
        $_SESSION['update_list'] = $_update_list;
        /*
		$CI->nativesession->set('update_check', true);
        $CI->nativesession->set('update_list', $_update_list);
        */
    }else{
        
        //$_update_list = $CI->nativesession->get('update_list');
        $_update_list = $_SESSION['update_list'];
    }
    
	
	return $_update_list;
	
	
	
}



function is_internet_avaiable(){
    return !$sock = @fsockopen('www.google.com', 80, $num, $error, 5) ? false : true;    
}



?>