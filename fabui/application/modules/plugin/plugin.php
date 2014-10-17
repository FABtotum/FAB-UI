<?php 

class Plugin extends Module {

	public function __construct()
	{
		parent::__construct();
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);

	}

	public function index(){


		
		
		$this->load->helper('ft_plugin_helper');
		
		
		$_installed_plugins = installed_plugins();
		
		
		$_data['installed_plugins'] = $_installed_plugins;
		
		
		$this->layout->view('index/index', $_data);
	}
	
    
    
    public function add(){
        
        
        $this->load->helper('update_helper');
        
        
        $data['_internet'] = is_internet_avaiable();
        
        if($data['_internet'] == true){
            
            
            $css_in_page = $this->load->view('add/css', '', TRUE);
            $this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => 'add css'));
            
            $this->load->database();
            $this->load->model('configuration');
            
            /** LOAD SAVED REPOSITORY */
            $_repository = json_decode($this->configuration->get_config_value('plugin_respository'), TRUE);
            $_plugins    = array();
            
            /** LOAD PLUGIN LIST */
            foreach($_repository as $_repo){    
                $_temp = $_repo;
                $_temp['plugins'] = json_decode(file_get_contents($_repo['url']), TRUE);
                $_plugins[] = $_temp;    
               
                
            }
            
            $data['_plugins'] = $_plugins;
            
        }
        
        
        
        $this->layout->view('add/index', $data);
        
    }
	
	
	
	public function active($plugin){
		
		$this->load->database();
		$this->load->model('plugins');
		
		$this->plugins->active($plugin);
		
		
		$_SESSION['plugins'] = $this->plugins->get_activeted_plugins();
		
		redirect('plugin');
		
	}
	
	
	
	
	public function deactive($plugin){
		
		$this->load->database();
		$this->load->model('plugins');
		
		$this->plugins->deactive($plugin);
		
		$_SESSION['plugins'] = $this->plugins->get_activeted_plugins();
		
		redirect('plugin');
		
	}
	

}

?>