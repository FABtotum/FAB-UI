<?php 

class Updates extends Module {
	
	
	private $_fabui_local;
	private $_marlin_local;

	public function __construct()
	{
		parent::__construct();
		
        $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT CHANGE SETTINGS  */
        if(is_printer_busy()){
            redirect('dashboard');
        }
		
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);
		
		/** LOAD HELPER */
        $this->load->helper('update_helper');
		
		$this->_fabui_local = myfab_get_local_version();
		$this->_marlin_local = marlin_get_local_version();

	}
    
    
    public function index(){
        
        
        /** LOAD DATABASE */
		$this->load->database();
		$this->load->model('tasks');
		
		
		/*
		$_fabui_local   =  myfab_get_local_version();
		$_marlin_local  =  marlin_get_local_version();
		
		$data['fabui_local']   = $_fabui_local;
		$data['marlin_local']  = $_marlin_local;
		*/
		
		$data['fabui_local']   = $this->_fabui_local;
		$data['marlin_local']  = $this->_marlin_local;
		
		$_SESSION['fabui_version'] = $this->_fabui_local;
		
		
		$_updates =  array();
		$_updates['number'] = 0;
		$_updates['time'] = time();	
		
		
		
		
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
        
		
		 $data['fabui']  = false;
		 $data['marlin'] = false;
        
        if($_is_internet_ok){    
           
            $_fabui_remote = myfab_get_remote_version();
            $_fabui        = $_fabui_remote > $this->_fabui_local;
            
            $data['fabui']        = $_fabui;
            $data['fabui_remote'] = $_fabui_remote;
			
			if($_fabui){
				$data['fabui_changelog'] = fabui_changelog($_fabui_remote);
			}
			
           
            $_marlin_remote =  marlin_get_remote_version();
            $_marlin        =  $_marlin_remote > $this->_marlin_local;
            
            $data['marlin']        = $_marlin;
            $data['marlin_remote'] = $_marlin_remote;
			
			if($_marlin){
				$data['fw_changelog'] = fw_changelog($_marlin_remote);
			}
			
			
			$data['no_update'] = ($this->_fabui_local == $_fabui_remote ) && ($this->_marlin_local == $_marlin_remote);
			
			
			$_updates['number'] += $_fabui ? 1 : 0;
			$_updates['number'] += $_marlin ? 1 : 0;
			$_updates['fabui']   = $_fabui;
			$_updates['fw']      = $_marlin;    
        }


		$_SESSION['updates'] = $_updates;
        
        
        $js_in_page  = $this->load->view('index/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'updates js'));
            
        //$this->layout->set_compress(true);
        
        $data['internet']= $_is_internet_ok; 
		
        $this->layout->view('index/index', $data); 
        
    }



	public function changelog($type, $version){
		
		$this->config->load('myfab', TRUE);
		
		$_remote_url = $this->config->item($type.'_remote_download_url', 'myfab');
		
		$_changelog = $this->config->item($type.'_changelog', 'myfab');
		
		$_url =  $_remote_url.$version.'/'.$_changelog;
		
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		
		echo $response;
		 
	}
	
	
	public function upload(){
			
		
		if($this->input->post()){
			
			
			if(isset($_FILES['install-file'])){
				
				$install_type = $this->input->post('type');
				
				//print_r($_POST);
				//print_r($_FILES['install-file']);
				
				
				$config['upload_path']   = '../temp/';
				$config['allowed_types'] = 'zip';
				
				$this -> load -> library('upload', $config);
				
				if (!$this -> upload -> do_upload('install-file')) {
					$this -> upload -> display_errors();
					
				}else{
					$file_data = array('upload_data' => $this -> upload -> data());
					
					$zip = new ZipArchive;

					$file = $file_data['upload_data']['full_path'];
					
					chmod($file, 0777);
					
					print_r($file_data);
					
					if ($zip -> open($file) === TRUE) {
						
						$zip -> extractTo(TEMPPATH . $file_data['upload_data']['raw_name']);
						$zip -> close();
						
						echo "unzipped";
						
					}else{
						echo "error";
					}
					
				}
				
				
				exit();
				
			}
			
		}
		
		$data['fabui_local']   = $this->_fabui_local;
		$data['marlin_local']  = $this->_marlin_local;
		
		
		$js_in_page  = $this->load->view('upload/js', $data, TRUE);
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'upload js'));
		
		$this->layout->view('upload/index', $data); 
	}
    
    
    
 }
