<?php 

class Controller extends Module {

	public function __construct()
	{
		parent::__construct();

	}

	public function index(){

		
	}
	
	
	public function updates(){
		
		$this->load->helper('update_helper');
		$_update_list = myfab_update_list();

		
		if(count($_update_list) > 0){
			$data['update_list'] = $_update_list;
			
			echo $this->load->view('update', $data, TRUE);
			
		}
		
	}
	
	
	public function tasks(){
		
		echo $this->load->view('tasks', '', TRUE);
		
	}

	
	
	public function notifications(){
		
		echo $this->load->view('notifications', '', TRUE);
		
	}
    
    
    
    
    public function language(){
        
        if($this->input->post()){
            
            
            $language = $this->input->post('lang');
            $back_url = $this->input->post('back_url');
            
            $this->load->database();
            $this->load->model('configuration');
            
            
            $languages = json_decode($this->configuration->get_config_value('languages'),TRUE);
            
            $this->configuration->save_confi_value('language', $language);

            $_SESSION['language'] = $languages[$language];
            
            redirect($back_url);
            
            
            
            
            
        }
        
        
    }



}

?>