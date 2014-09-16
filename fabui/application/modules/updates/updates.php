<?php 

class Updates extends Module {

	public function __construct()
	{
		parent::__construct();
        
        $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT CHANGE SETTINGS  */
        if(is_printer_busy()){
            redirect('dashboard');
        }
        
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);

	}
    
    
    public function index(){
        
        
        /** LOAD DATABASE */
		$this->load->database();
		$this->load->model('tasks');
		
		/** LOAD HELPER */
        $this->load->helper('update_helper');
		
		$_fabui_local   =  myfab_get_local_version();
		$_marlin_local  =  marlin_get_local_version();
		
		$data['fabui_local']   = $_fabui_local;
		$data['marlin_local']  = $_marlin_local;
		
		
		$_SESSION['fabui_version'] = $_fabui_local;
		
		
        /** GET IF IS RUNNING */
        $_task = $this->tasks->get_running('updates');
        
        
        $_is_running = $_task == false ? false : true;
        
        $data['running'] = $_is_running;
        
        /** GET TASK INFO IF IS RUNINNG */
        if($_is_running){
            
            $_attributes = json_decode($_task['attributes'], TRUE);
            $data['update_type'] = $_task['type'];
            $data['json_uri']    = $_attributes['uri_monitor'];
            $data['id_task']     = $_task['id'];
            
        }
		
        $_is_internet_ok = is_internet_avaiable();
        
        
        if($_is_internet_ok){    
           
            $_fabui_remote = myfab_get_remote_version();
            $_fabui        = $_fabui_remote > $_fabui_local;
            
            $data['fabui']        = $_fabui;
            
            $data['fabui_remote'] = $_fabui_remote;
			
           
            $_marlin_remote =  marlin_get_remote_version();
            $_marlin        =  $_marlin_remote > $_marlin_local;
            
            $data['marlin']        = $_marlin;
            
            $data['marlin_remote'] = $_marlin_remote;
			
			
			$data['no_update'] = ($_fabui_local == $_fabui_remote ) && ($_marlin_local == $_marlin_remote);
            
            
        }
        
        
        $js_in_page  = $this->load->view('index/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'updates js'));
            
        //$this->layout->set_compress(true);
            
        
       
        $data['internet']= $_is_internet_ok; 
		
        $this->layout->view('index/index', $data); 
        
    }
    
    
    
 }
