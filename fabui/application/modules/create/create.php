<?php
/***
 * Print Module
 * 
 * 
 * 
 * 
 */
class Create extends Module {

	public function __construct()
	{
		parent::__construct();
		error_reporting(0);
		 $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT CHANGE SETTINGS  */
        if(is_printer_busy('print')){
            redirect('dashboard');
        }
        
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);
	}

	
	public function index(){

		/** INIT DB & MODELS */
		$this->load->database();
		$this->load->model('objects');
		$this->load->model('tasks');
		
		/**
		 * LOAD HELPERS
		*/
        $this->load->helper('ft_date_helper');
		$this->load->helper('smart_admin_helper');
		$this->load->helper('os_helper');
        
       
        /** LOAD REQUEST PARAMETER */
        $_request_obj  = $this->input->get('obj');
        $_request_file = $this->input->get('file');
         
        
		
		/**
		 * check if printer is already printing
		*/
		$_task = $this->tasks->get_running('create', 'print');
		
		
		
		$_running = $_task ? true : false;

		if($_running){
		  
		  	/** GET TASK ATTRIBUTES */
		  	$_attributes = json_decode($_task['attributes'], TRUE);
			
			
			/** CHECK IF PID IS STILL ALIVE */
			if(exist_process($_attributes['pid'])){
			
				
				$this->load->model('files');
				
				$_object          = $this->objects->get_obj_by_id($_attributes['id_object']);
				$_file            = $this->files->get_file_by_id($_attributes['id_file']);
				
	            if(isset($_attributes['pid']) && $_attributes['pid'] != ''){
	            
	                if(isset($_attributes['monitor']) && $_attributes['monitor'] != ''){
	                
	                    $_monitor         = file_get_contents($_attributes['monitor']);
	        			$_monitor_encoded = json_decode($_monitor);
	                    $_stats           = json_decode(file_get_contents($_attributes['stats']), TRUE);
	                
	                }
	            
	            }else{
	                $this->tasks->delete($_task['id']);
	                $_running = FALSE;
	            }
           
		   
		   }else{
		   		/** PROCESS IS DEAD */
		   		$_running = false;
				$this->tasks->delete($_task['id']);
				
		   }

		}


		/**
		 *  IMPOSTAZIONI STEP1
		 */
        $data_step1['objects']  = $this->objects->get_for_print();
		
		
		
        $_table = $this->load->view('index/step1/table', $data_step1, TRUE);
        $_widget_table = widget('objects'.time(), 'Objects',  '', $_table, false, true, true);
        
		$data_step1['_running'] = $_running;
        $data_step1['_table']   = $_widget_table;
		////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
		/**
		 * IMPOSTAZIONI STEP2
		 */
		$data_step2[] = '';


		/**
		 * IMPOSTAZIONI STEP4
		*/

		$data_step4 = array();


		/**
		 * IMPOSTAZIONI STEP5
		*/

		
		
		$data_widget_step5['_progress_percent'] = $_running ? number_format($_monitor_encoded->print->stats->percent, 2, ',', ' '): '0';
		$data_widget_step5['_lines']            = $_running ? $_monitor_encoded->print->lines : '-';
		$data_widget_step5['_current_line']     = $_running ? $_monitor_encoded->print->stats->line_number : '-';
		$data_widget_step5['_position']         = $_running ? $_monitor_encoded->print->stats->position : '-';
		$data_widget_step5['_temperature']      = $_running ? $_monitor_encoded->print->stats->extruder : 0;
		$data_widget_step5['_temperature_target']  = $_running ? $_monitor_encoded->print->stats->extruder_target : '-';
        $data_widget_step5['_bed_temperature']  = $_running ? $_monitor_encoded->print->stats->bed : 0;
		$data_widget_step5['_bed_temperature_target']  = $_running ? $_monitor_encoded->print->stats->bed_target : '-';
		$data_widget_step5['_pid']              = $_running ? $_attributes['pid'] : 0;
		$data_widget_step5['_velocity']         = $_running && isset($_attributes['speed']) ? $_attributes['speed'] : 100;
		$data_widget_step5['_rpm']               = $_running && isset($_attributes['rpm']) ? $_attributes['rpm'] : 6000;
		$data_widget_step5['_running']          = $_running;
		$data_widget_step5['_file_type']        = $_running ? $_file->_print_type  : 'additive'; 
		
		
        
        //$data_widget_step5['ext_temp']          = $_running ? $ext_temp : 0;
        //$data_widget_step5['bed_temp']          = $_running ? $bed_temp : 0;


		//$data_step5['_tab5_monitor_widget'] = widget('_tab5_monitor_widget', 'Print Monitor', '', $this->load->view('index/step5/widget', $data_widget_step5, TRUE), false);
		$data_step5['_tab5_monitor_widget'] = $this->load->view('index/step5/widget', $data_widget_step5, TRUE);
        $data_step5['_running']             = $_running;
		$data_step5['mail']                 = $_running && isset($_attributes['mail']) ? $_attributes['mail'] : 0;


        /**
         * 
         * IMPOSTAZIONI STEP6
         */
    

		//inclusione dei step
		$data['_step_1']  = $this->load->view('index/step1/index', $data_step1, TRUE);
		$data['_step_2']  = $this->load->view('index/step2/index', $data_step2, TRUE);
		//$data['_step_3']  = $this->load->view('index/step3/index', $data_step3, TRUE);
		$data['_step_4']  = $this->load->view('index/step4/index', $data_step4, TRUE);
		$data['_step_5']  = $this->load->view('index/step5/index', $data_step5, TRUE);
        $data['_step_6']  = $this->load->view('index/step6/index', '', TRUE);

		$data['_running']     = $_running;
		$data['_object_name'] = $_running ? ' > '.$_object->obj_name : '';
		$data['_file_name']   = $_running ? ' > '.$_file->file_name : '';
		$data['_file_type']   = $_running ? $_file->_print_type  : 'additive'; 

		$data_js['_id_task']          = $_running ? $_task['id'] : 0;
		$data_js['_pid']              = $_running ? $_attributes['pid'] : 0;
		$data_js['_monitor']          = $_running ? $_monitor : '' ;
		$data_js['_monitor_file']     = $_running ? $_attributes['monitor'] : '' ;
		$data_js['_data_file']        = $_running ? $_attributes['data'] : '' ;
		$data_js['_trace_file']       = $_running ? $_attributes['trace'] : '' ;
        $data_js['_stats_file']       = $_running ? $_attributes['stats'] : '' ;
        $data_js['_folder']           = $_running ? $_attributes['folder'] : '' ;
		$data_js['_debug_file']       = $_running ? $_attributes['debug'] : '' ;
        $data_js['_uri_monitor']      = $_running ? $_attributes['uri_monitor'] : '' ;
        $data_js['_uri_trace']        = $_running ? $_attributes['uri_trace'] : '' ;
		$data_js['_seconds']          = $_running ? (time() - intval($_monitor_encoded->print->started)) : 0;

		$data_js['_estimated_time']   = $_running && is_array($_stats) ? 'new Array("'.implode('","', $_stats['estimated_time']).'")' : 'new Array()';
		$data_js['_progress_steps']   = $_running && is_array($_stats) ? 'new Array("'.implode('","', $_stats['progress_steps']).'")' : 'new Array()';
        $data_js['ext_temp']          = $_running ? $_monitor_encoded->print->stats->extruder : 0;
        $data_js['bed_temp']          = $_running ? $_monitor_encoded->print->stats->bed : 0;
		$data_js['ext_target']        = $_running ? $_monitor_encoded->print->stats->extruder_target : 0;
        $data_js['bed_target']        = $_running ? $_monitor_encoded->print->stats->bed_target : 0;
        $data_js['_velocity']         = $_running && isset($_attributes['speed']) ? $_attributes['speed'] : 100;
		$data_js['_rpm']              = $_running && isset($_attributes['rpm']) ? $_attributes['rpm'] : 6000;
        
        $data_js['_request_obj']     =  $_request_obj;
        $data_js['_request_file']    =  $_request_file;  
		
		$_time =  $_running ? (time() - intval($_monitor_encoded->print->started)) : 0;

		/**
		 * IMPOSTAZIONI LAYOUT
		*/

		$this->layout->add_css_file(array('src'=>'application/modules/create/assets/css/create.css', 'comment'=>'create css'));

        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/fuelux/wizard/wizard.min.js', 'comment' => 'javascript for the wizard'));
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/jquery.dataTables.min.js', 'comment'=>''));
       	$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/dataTables.colVis.min.js', 'comment'=>''));
       	$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/dataTables.tableTools.min.js', 'comment'=>''));
       	$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/dataTables.bootstrap.min.js', 'comment'=>''));
        
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.min.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.css', 'comment' => 'javascript for the noUISlider'));


        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/jquery.livequery.js', 'comment' => 'javascript for the Jquery Live'));
        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT'));
		
		$this->layout->add_js_file(array('src'=>'application/modules/create/assets/js/utilities.js', 'comment'=>'create utilities')); 





		$js_in_page  = $this->load->view('index/js', $data_js, TRUE);
		

		
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'create module'));
        
        
        //$this->layout->set_compress(false);
		$this->layout->view('index/index', $data);

	}



	public function monitor(){

		//se � una chiamata AJAX allora...
		if ($this->input->is_ajax_request()) {
			$id_task      = $this->input->post('id_task');
			$file_monitor = $this->input->post('file_monitor');

			header('Content-Type: application/json');
			echo file_get_contents($file_monitor);
		}

	}


	public function trace(){
		if ($this->input->is_ajax_request()) {
			$file_trace = $this->input->post('trace');
			echo file_get_contents($file_trace);
		}
	}




}