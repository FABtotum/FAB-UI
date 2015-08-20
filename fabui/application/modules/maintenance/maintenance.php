<?php 
class Maintenance extends Module {

	public function __construct()
	{
		parent::__construct();
        
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);
        
        
	}
	
	
	
	
	public function index(){
		
	}
	
	
	
	public function spool(){
		
		
		
		$this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT'));
		
		$js_in_page = $this->load->view('spool/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		$this->layout->view('spool/index', '');
		
	}
	
	public function feeder(){
		
		
		$js_in_page = $this->load->view('feeder/js', '', TRUE);
       	$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		$this->layout->view('feeder/index', '');
		
	}
	
	
	public function fourthaxis(){
		
		
		$js_in_page = $this->load->view('fourthaxis/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		
		
		
		$this->layout->view('fourthaxis/index', '');
	}
	
	
	public function selftest(){
		
		
		/** INIT DB & MODELS */
		$this->load->database();
		$this->load->model('tasks');
		
		/**
		 * LOAD HELPERS
		*/
		$this->load->helper('os_helper');
		
		$_task = $this->tasks->get_running('maintenance', 'self_test');
		
		$_running = $_task ? true : false;
		
		
		if($_running){
			
			/** GET TASK ATTRIBUTES */
		  	$_attributes = json_decode($_task['attributes'], TRUE);
			
			if(exist_process($_attributes['pid'])){
				
				
				$data['monitor_file'] = $_attributes['uri_monitor'];
				$data['trace_file']   = $_attributes['uri_trace'];
				$data['trace_content'] = file_get_contents($_attributes['trace']);
				$this->layout->set_compress(false);
			
			}else{
				
				$_running = false;
				$this->tasks->delete($_task['id']);
				
			}
		}
		
		
		
		$data['running'] = $_running;
		
		$js_in_page = $this->load->view('selftest/js', $data, TRUE); 
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));

        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 
		
		
		$this->layout->view('selftest/index', $data);
	}
	
	
	public function bedcalibration(){
		
		
		
		$js_in_page = $this->load->view('bedcalibration/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		$this->layout->view('bedcalibration/index', '');
	}
	
	public function probecalibration(){
		
		
		$js_in_page = $this->load->view('probecalibration/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		$this->layout->view('probecalibration/index', '');
	}
	
	
	public function firstsetup(){
		
		
		$this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/fuelux/wizard/wizard.min.js', 'comment' => ''));
		
		
		$data['show_feeder'] = $this->layout->getFeeder();
		
		
		$data['step1'] = $this->load->view('firstsetup/step1/index', '', TRUE);
		$data['step2'] = $this->load->view('firstsetup/step2/index', '', TRUE);
		$data['step3'] = $this->load->view('firstsetup/step3/index', '', TRUE);
		$data['step4'] = $this->load->view('firstsetup/step4/index', '', TRUE);
		$data['step5'] = $this->load->view('firstsetup/step5/index', '', TRUE);
		
		$js_in_page = $this->load->view('firstsetup/js', $data, TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		
		$this->layout->set_setup_wizard(FALSE);
		
		
		$this->layout->view('firstsetup/index', '');
	}


}

?>