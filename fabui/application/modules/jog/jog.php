<?php 

class Jog extends Module {

	public function __construct()
	{
		parent::__construct();
        //FLUSH SERIAL PORT BUFFER INPUT/OUTPUT
        $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT JOG  */
        if(is_printer_busy()){
            redirect('dashboard');
        }
        
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);
        
	}

	public function index(){
		
		
		$this -> config -> load('fabtotum', TRUE);
		$units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);
		
		if(isset($units['settings_type']) && $units['settings_type'] == 'custom'){
			$units = json_decode(file_get_contents($this -> config -> item('fabtotum_custom_config_units', 'fabtotum')), TRUE);
		}
        
        $data['max_temp'] = isset($units['hardware']['head']['max_temp']) ? $units['hardware']['head']['max_temp'] : 230;
		
		$css_in_page = $this->load->view('index/css', '', TRUE);
		$js_in_page  = $this->load->view('index/js', $data, TRUE);

		$this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => 'JOG CSS'));
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'JOG JS'));
		
		
        $this->layout->add_js_file(array('src'=>'/assets/js/plugin/knob/jquery.knob.min.js', 'comment'=>'KNOB'));
		
		$this->layout->set_compress(false);
	
		$this->layout->view('index/index', $data); 
	}
    
    
    
    
    public function setup(){
        
        $this->load->database();
		$this->load->model('configuration');

        $data['_unit']     = $this->configuration->get_config_value('unit');
		$data['_step']     = $this->configuration->get_config_value('step');
		$data['_feedrate'] = $this->configuration->get_config_value('feedrate');
        
        $js_in_page  = $this->load->view('setup/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'JOG JS'));
        
        $this->layout->view('setup/index', $data);
    }
	
	
	public function manual(){
		
		//carico X class database
		$this->load->database();
		$this->load->model('codes');
		
		$g_codes = $this->codes->get_all('G');
		$m_codes = $this->codes->get_all('M');
		
		$data['gcodes'] = $g_codes;
		$data['mcodes'] = $m_codes;
		
		$this->load->view('manual/index', $data);
	}





}

?>