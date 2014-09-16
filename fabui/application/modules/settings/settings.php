<?php 

class Settings extends Module {

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
        
        
        
        $this->load->database();
        $this->load->model('configuration');
		
		
		$_units = json_decode(file_get_contents('/var/www/fabui/config/config.json'), TRUE);
        
        $data['_standby_color'] = $_units['color'];
        
        /** LOAD TAB HEADER */
        $_tab_header = $this->tab_header();
        
        $data['_breadcrumb']  = 'General';
        $data['_tab_header']  = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/general/index', $data, TRUE);
        
        /** LAYOUT */
        $js_in_page  = $this->load->view('index/general/js', $data, TRUE);
        $css_in_page = $this->load->view('index/general/css', '', TRUE);
        
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'settings js'));
        $this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => 'settings css'));
        
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.min.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.css', 'comment' => 'javascript for the noUISlider'));

        
        
		$this->layout->view('index/index', $data);
	}
    
    
    
    public function create(){
        
        
        $this->load->database();
        $this->load->model('configuration');
        
        if($this->input->post()){
            foreach($this->input->post() as $key => $value){
                $this->configuration->save_confi_value($key, $value);
            }
            
        }
        
        $data['_start_gcode'] = $this->configuration->get_config_value('start_gcode');
		$data['_end_gcode']   = $this->configuration->get_config_value('end_gcode');
        $data['_slicer_presets'] = json_decode($this->configuration->get_config_value('slicer_presets'), TRUE);
        
        $_tab_header = $this->tab_header('create');
        
        $data['_breadcrumb']  = 'Print';
        $data['_tab_header'] = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/create/index', $data, TRUE);
        
        
        $js_in_page = $this->load->view('index/create/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
        
        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 
        $this->layout->set_compress(false);
		$this->layout->view('index/index', $data);
        
    }
    
    
    
    public function scan(){
        
        
        $_tab_header = $this->tab_header('scan');
        
        /** LOAD DATABASE */
        $this->load->model('scan_model');
        
        /** LOAD SCAN CONFIGURATIONS */
        $quality_list = $this->scan_model->get(array('type' => 'quality'));
        
        
        $data['_breadcrumb']  = 'Scan';
        $data['_tab_header']  = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/scan/index', '', TRUE);
        
		$this->layout->view('index/index', $data);
        
    }
    
    
    
    public function advanced(){
        
        
        $this->config->load('myfab', TRUE);
        
        $data['_breadcrumb']  = 'Advanced';
        $_tab_header = $this->tab_header('advanced');
        $data['_tab_header'] = $_tab_header;
        
        if($this->input->post()){
            
            $file_content = $this->input->post('file_content');
            file_put_contents($this->config->item('script_boot', 'myfab'), $file_content, FILE_USE_INCLUDE_PATH); 
            
            
            
        }
        
        
        $data['_boot_script_file'] = $this->config->item('script_boot', 'myfab');
        $data['_boot_script'] = file_get_contents($this->config->item('script_boot', 'myfab'), FILE_USE_INCLUDE_PATH);
                
        
        $data['_tab_content'] = $this->load->view('index/advanced/index', $data, TRUE);
        
        
        $js_in_page = $this->load->view('index/advanced/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => '')); 
        
        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 
        $this->layout->set_compress(false);
        $this->layout->view('index/index', $data);
        
    }
    
    
    function network(){
        
        /** LOAD HELPERS */
        $this->load->helper("os_helper");
       
        //print_r(scan_wlan());
        //print_r(wlan());
        
        //print_r(lan());
        
        $this->load->database();
		$this->load->model('configuration');
		
		$saved_wifi = $this->configuration->get_config_value('wifi');
        
		$saved_wifi = json_decode($saved_wifi, true);
		
		
        
        $_tab_header = $this->tab_header('network');
		$data['wifi_saved']   = $saved_wifi;
        $data['_breadcrumb']  = 'Network';
        $data['_tab_header']  = $_tab_header;
		$data['lan']         = lan();
		$data['con_wlan']    = wlan();
		$data['wlan']        = scan_wlan(); 
        $data['_tab_content'] = $this->load->view('index/network/index', $data, TRUE);
       
        $js_in_page = $this->load->view('index/network/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		
		
		
		
		$this->layout->set_compress(false);
        $this->layout->view('index/index', $data);
    }
    
    
    
    function jog(){
        
        $this->load->database();
		$this->load->model('configuration');
        
        if($this->input->post()){
            
            foreach($this->input->post() as $key => $value){
				$this->configuration->save_confi_value($key, $value);
			}
            
        }
        
        $_tab_header = $this->tab_header('jog');
        
        $data['_unit']     = $this->configuration->get_config_value('unit');
		$data['_step']     = $this->configuration->get_config_value('step');
		$data['_feedrate'] = $this->configuration->get_config_value('feedrate');
        
        $data['_breadcrumb']  = 'Jog';
        $data['_tab_header']  = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/jog/index', $data, TRUE);

        $this->layout->view('index/index', $data);
        
    }
    
    
    
     function plugin(){
        
       
        $_tab_header = $this->tab_header('plugin');
        
  
        
        $data['_tab_header']  = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/plugin/index', $data, TRUE);

        $this->layout->view('index/index', $data);
        
    }
    
    
    
    function maintenance($mode = 'index'){
        
        
        switch($mode){    
            case 'spool':
                $this->_maintenance_spool();
                break;
            case 'self-test':
                $this->_maintenance_self_test();
                break;
            default:
                $_tab_header = $this->tab_header('maintenance');
                $data['_breadcrumb']  = 'Maintenance';
                $data['_tab_header']  = $_tab_header;
                $data['_tab_content'] = $this->load->view('index/maintenance/'.$mode.'/index.php', $data, TRUE);
                $this->layout->view('index/index', $data);
                break;
        }
        
    }
    
    
    
    private function _maintenance_spool(){
        
        
        $_tab_header = $this->tab_header('maintenance');
        
        $data['_breadcrumb']  = 'Maintenance > Spool';
        $data['_tab_header']  = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/maintenance/spool/index.php', $data, TRUE);
        
        $js_in_page = $this->load->view('index/maintenance/spool/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));


        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 
        $this->layout->view('index/index', $data);
        
        
        
        
        
    }
    
    private function _maintenance_self_test(){
        
        
        $_tab_header = $this->tab_header('maintenance');
        
        $data['_breadcrumb']  = 'Maintenance > Self Test';
        $data['_tab_header']  = $_tab_header;
        $data['_tab_content'] = $this->load->view('index/maintenance/self-test/index', $data, TRUE);
        
        $js_in_page = $this->load->view('index/maintenance/self-test/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));

        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 
        $this->layout->view('index/index', $data);
        
    }
     
    
    
    
    function tab_header($current = 'settings'){
 
        $_tabs[] = array('name' => 'settings',    'label'=>'General',     'url' => site_url('settings'),             'icon' => 'fa fa-lg fa-fw fa fa-cogs');
        //$_tabs[] = array('name' => 'scan',        'label'=>'Scan',        'url' => site_url('settings/scan'),        'icon' => 'fab-lg fab-fw icon-fab-scan');
        //$_tabs[] = array('name' => 'create',      'label'=>'Print',       'url' => site_url('settings/create'),      'icon' => 'fab-lg fab-fw icon-fab-print');
        $_tabs[] = array('name' => 'jog',         'label'=>'Jog',         'url' => site_url('settings/jog'),         'icon' => 'fab-lg fab-fw icon-fab-jog');
        //$_tabs[] = array('name' => 'plugin',    'label'=>'Plugin',   'url' => site_url('settings/plugin'),   'icon' => 'fab-lg fab-fw icon-fab-plugin');
        
		$_tabs[] = array('name' => 'maintenance', 'label'=>'Maintenance', 'url' => site_url('settings/maintenance'), 'icon' => 'fa fa-lg fa-fw fa-wrench');
        $_tabs[] = array('name' => 'network',     'label'=>'Network',     'url' => site_url('settings/network'),     'icon' => 'fa fa-lg fa-fw fa-sitemap');
		$_tabs[] = array('name' => 'advanced',    'label'=>'Advanced',    'url' => site_url('settings/advanced'),    'icon' => 'fa fa-lg fa-fw fa-briefcase');
        
        
        
        
        
        
        $data['_current'] = $current;
        $data['_tabs']    = $_tabs;
        
        return $this->load->view('index/tab_header', $data, TRUE);
        
    }




}

?>