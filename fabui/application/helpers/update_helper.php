<?php 


function myfab_get_remote_version(){
	//return;
	// Get current CodeIgniter instance
	$CI =& get_instance();
	
	//load configuration file
	$CI->config->load('myfab', TRUE);
    
	$_remote_url = $CI->config->item('fabui_remote_version_url', 'myfab');
	
	$ch = curl_init($_remote_url);
	
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$_version = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	return $_version;
	
}


function myfab_get_local_version(){
	
	// Get current CodeIgniter instance
	$CI =& get_instance();
	
	$CI->load->database();
	$CI->load->model('configuration');
	
	return  $CI->configuration->get_config_value('fabui_version');
}




function marlin_get_remote_version(){
    
	// Get current CodeIgniter instance
	$CI =& get_instance();

	//load configuration file
	$CI->config->load('myfab', TRUE);

	$_remote_url = $CI->config->item('fw_remote_version_url', 'myfab');
    
    $ch = curl_init($_remote_url);
	
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$_version = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
    
    

	return $_version;

}


function marlin_get_local_version(){

	// Get current CodeIgniter instance
	$CI =& get_instance();
	
	$CI->load->database();
	$CI->load->model('configuration');
	
	return  $CI->configuration->get_config_value('fw_version');
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
        		array_push($_update_list, array('name' => 'fabui', 'url' => site_url('updates'), 'description' => 'A new update for FAB UI is avaiable!' ));
        	}
        	
        	if($marlin_update){
        		array_push($_update_list, array('name' => 'fw', 'url' => site_url('updates'), 'description' => 'A new update for Marlin Firmware is avaiable!' ));
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


function fabui_changelog($version){
	
	$CI =& get_instance();
	
	//load configuration file
	$CI->config->load('myfab', TRUE);
	
	
	if(is_internet_avaiable()){
		
		
		$_remote_url = $CI->config->item('fabui_remote_download_url', 'myfab');
		$_fabui_changelog = $CI->config->item('fabui_changelog', 'myfab');
		
		$ch = curl_init($_remote_url.$version.'/'.$_fabui_changelog);
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$changelog = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		return $changelog;	
	}
	
	return "";
	
	
}



function fw_changelog($version){
	
	$CI =& get_instance();
	
	$CI->config->load('myfab', TRUE);
	
	
	if(is_internet_avaiable()){
		
		$_remote_url = $CI->config->item('fw_remote_download_url', 'myfab');
		$_fw_changelog = $CI->config->item('fw_changelog', 'myfab');
		
		$ch = curl_init($_remote_url.$version.'/'.$_fw_changelog);
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$changelog = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		return $changelog;
		
	}
	
	return "";
	
	
}



?>