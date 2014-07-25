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
	}

	public function index($obj_id = ""){

		//carico X class database
		$this->load->database();
		$this->load->model('objects');
		$this->load->model('printsettings');
		$this->load->model('tasks');
		//init function
		$this->load->helper('form');
        $this->load->helper('ft_date_helper');
		$this->load->helper('smart_admin_helper');
		$this->load->helper('create_smart_form_helper');
        
        
        
        /** LOAD REQUEST PARAMETER */
        $_request_obj  = $this->input->get('obj');
        $_request_file = $this->input->get('file');
         
        //$this->layout->set_compress(false);


		/**
		 * verifico prima se c'è una stampa già in esecuzione
		*/
		$_task = $this->tasks->get_running('create', 'print');

		
	
		
		$_running = $_task ? true : false;

		if($_running){
		  
			$this->load->model('files');

			$_attributes = json_decode($_task['attributes'], TRUE);
			
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
           

		}


		/**
		 *  IMPOSTAZIONI STEP1
		 */
        $data_step1['objects']  = $this->objects->get_for_print();
        $_table = $this->load->view('index/step1/table', $data_step1, TRUE);
        $_widget_table = widget('objects'.time(), 'Objects',  '', $_table, false, true);
        
		$data_step1['_running'] = $_running;
        $data_step1['_table']   = $_widget_table;
		////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
		/**
		 * IMPOSTAZIONI STEP2
		 */
		$data_step2[] = '';

		//////////////////////////////////////////////////////////////////////////////////////////////////////

		/**
		 * IMPOSTAZIONI STEP3
		 */
		//generazione tab

		$settings_section = $this->printsettings->get_sections();

		$_tab_items = array();

		foreach($settings_section as $section){

			$content = craeate_section($section->id);

			$_tab_items[] = array('reference' => $section->section_name,    'title' => $section->section_label,    'content'=> $content);

		}

		$_step3_tab = tab('step3_tab', $_tab_items);

		$data_step3['_tab3_widget'] = widget('_tab3_widget', '', '', $_step3_tab, true);
		//$data_step3['_tab3_widget'] = widget('_tab3_widget', '', '', $this->load->view('index/step3/widget', '', TRUE), true);

		//////////////////////////////////////////////////////////////////////////////////////////////////////



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
        $data_widget_step5['_bed_temperature']  = $_running ? $_monitor_encoded->print->stats->bed : 0;
		$data_widget_step5['_pid']              = $_running ? $_attributes['pid'] : 0;
		$data_widget_step5['_velocity']         = $_running ? $_attributes['velocity'] : 100;
		$data_widget_step5['_running']          = $_running;
        $data_widget_step5['ext_temp']          = $ext_temp;
        $data_widget_step5['bed_temp']          = $bed_temp;


		//$data_step5['_tab5_monitor_widget'] = widget('_tab5_monitor_widget', 'Print Monitor', '', $this->load->view('index/step5/widget', $data_widget_step5, TRUE), false);
		$data_step5['_tab5_monitor_widget'] = $this->load->view('index/step5/widget', $data_widget_step5, TRUE);
        $data_step5['_running']             = $_running;


        /**
         * 
         * IMPOSTAZIONI STEP6
         */
    

		//inclusione dei step
		$data['_step_1']  = $this->load->view('index/step1/index', $data_step1, TRUE);
		$data['_step_2']  = $this->load->view('index/step2/index', $data_step2, TRUE);
		$data['_step_3']  = $this->load->view('index/step3/index', $data_step3, TRUE);
		$data['_step_4']  = $this->load->view('index/step4/index', $data_step4, TRUE);
		$data['_step_5']  = $this->load->view('index/step5/index', $data_step5, TRUE);
        $data['_step_6']  = $this->load->view('index/step6/index', '', TRUE);

		$data['_running']     = $_running;
		$data['_object_name'] = $_running ? ' > '.$_object->obj_name : '';
		$data['_file_name']   = $_running ? ' > '.$_file->file_name : '';

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

		$data_js['_estimated_time']   = $_running ? 'new Array("'.implode('","', $_stats['estimated_time']).'")' : 'new Array()';
		$data_js['_progress_steps']   = $_running ? 'new Array("'.implode('","', $_stats['progress_steps']).'")' : 'new Array()';
        $data_js['ext_temp']          = $_running ? $_monitor_encoded->print->stats->extruder : 0;
        $data_js['bed_temp']          = $_running ? $_monitor_encoded->print->stats->bed : 0;
        $data_js['_velocity']         = $_running ? $_attributes['velocity'] : 100;
        
        $data_js['_request_obj']     =  $_request_obj;
        $data_js['_request_file']    =  $_request_file;  
        


        //$this->layout->set_compress(false);

		//echo $_monitor_encoded->print->started;
		//echo time();

		$_time = (time() - intval($_monitor_encoded->print->started));






		/**
		 * IMPOSTAZIONI LAYOUT
		*/


		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/fuelux/wizard/wizard.js', 'comment' => 'javascript for the wizard'));

		$this->layout->add_css_file(array('src'=>'application/modules/create/assets/css/create.css', 'comment'=>'create css'));

		//Gcode viewer
		//$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/gc-viewer/lib/modernizr.custom.93389.js', 'comment' => 'javascript for the wizard'));

		//$this->layout->add_js_file(array('src'=>'application/modules/create/assets/js/GCodeAnalyzer.js', 'comment'=>'GCode Analyzer'));
		$this->layout->add_js_file(array('src'=>'application/modules/create/assets/js/utilities.js', 'comment'=>'create utilities'));
        
        
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/jquery.dataTables-cust.js', 'comment'=>''));
        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/ColReorder.min.js', 'comment'=>''));
        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/FixedColumns.min.js', 'comment'=>''));
        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/ColVis.min.js', 'comment'=>''));
        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/ZeroClipboard.js', 'comment'=>''));
        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/media/js/TableTools.min.js', 'comment'=>''));
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/datatables/DT_bootstrap.js', 'comment'=>''));
        
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.css', 'comment' => 'javascript for the noUISlider'));


        //$this->layout->add_js_file(array('src'=>'application/layout/assets/js/jquery.livequery.js', 'comment' => 'javascript for the Jquery Live'));
        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 





		$js_in_page  = $this->load->view('index/js', $data_js, TRUE);
		//$css_in_page  = $this->load->view('index/css', '', TRUE);

		//$this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => ''));
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'create module'));

		$this->layout->view('index/index', $data);

	}






	public function do_print($id_object, $id_file){


		//se è una chiamata AJAX allora....
		if ($this->input->is_ajax_request()) {

			//carico X class database
			$this->load->database();
			$this->load->model('files');
			$this->load->model('tasks');

            /**
    		 * LOAD HELPERS
    		 */
    		$this->load->helper('file');


			$file = $this->files->get_file_by_id($id_file);
            
            
            
            /**
    		 * ADD TASK
    		 */
    		$_task_data['controller'] = 'create';
			$_task_data['type']       = 'print';
			$_task_data['status']     = 'running';
            $_task_data['attributes'] = json_encode(array('id_object'=>$id_object, 'id_file'=>$id_file));
            
    		$id_task = $this->tasks->add_task($_task_data);
            
            $_time               = time();
            $_destination_folder = '/var/www/tasks/print_'.$id_task.'_'.$_time.'/';
            $_monitor_file       = $_destination_folder.'print_'.$id_task.'_'.$_time.'.monitor';
            $_data_file          = $_destination_folder.'print_'.$id_task.'_'.$_time.'.data';
            $_trace_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'.trace';
            $_debug_file         = $_destination_folder.'print_'.$id_task.'_'.$_time.'.debug';
            
            /*
			$_monitor_file = '/var/www/tasks/print'.time().'/'.$file->file_name.'_monitor.'.time().'.monitor';
			$_data_file    = '/var/www/tasks/print'.time().'/'.$file->file_name.'_data.'.time().'.data';
			$_trace_file   = '/var/www/tasks/print'.time().'/'.$file->file_name.'_trace.'.time().'.trace';
			$_debug_file   = '/var/www/tasks/print'.time().'/'.$file->file_name.'_debug.'.time().'.debug';
            */


            /**
             *  CREATE FILES AND FOLDERS
             */
            mkdir($_destination_folder, 0777);
            
            /** create print monitor file */
            write_file($_monitor_file, '', 'w');
            chmod($_monitor_file, 0777);
            /** create print data file */
            write_file($_data_file, '', 'w');
            chmod($_data_file, 0777);
            /** create print trace file */
            write_file($_trace_file, '', 'w');
            chmod($_trace_file, 0777);
            

            /*
			$ourFileHandle = fopen($_data_file, 'w') or die("can't open file");
			fclose($ourFileHandle);
			chmod($_data_file, 0777);
            */
			$_time_monitor = 2;

			//add task
            /*
			$_task_data['controller'] = 'create';
			$_task_data['type']       = 'print';
			$_task_data['status']     = 'running';
			$_task_data['attributes'] = json_encode(array('id_object'=>$id_object, 'id_file'=>$id_file, 'monitor'=> $_monitor_file, 'data' => $_data_file, 'trace' => $_trace_file, 'debug' => $_debug_file));

			$id_task = $this->tasks->add_task($_task_data);
            */
			
            $_command = 'sudo python /var/www/myfabtotum/python/gpusher.py '.$file->full_path .' '.$_monitor_file .' '.$_data_file.' '.$_time_monitor.' '.$_trace_file.' 2>'.$_debug_file.' > /dev/null & echo $!';
				
			$_output_command = shell_exec ( $_command );
            $_print_pid      = trim(str_replace('\n', '', $_output_command));
            
            $_attributes_items['pid']       =  $_print_pid;
            $_attributes_items['monitor']   =  $_monitor_file;
            $_attributes_items['data']      =  $_data_file;
            $_attributes_items['trace']     =  $_trace_file;
            $_attributes_items['debug']     =  $_debug_file;
            $_attributes_items['id_object'] =  $id_object;
            $_attributes_items['id_file']   =  $id_file;
                        
            $_data_update['attributes']= json_encode($_attributes_items);
            $this->tasks->update($id_task, $_data_update);

			sleep(2);
            
			$status = json_encode(file_get_contents($_monitor_file));
            
            header('Content-Type: application/json');
			echo json_encode(array('status'=>$status, 'id_task' => $id_task, 'monitor_file'=>$_monitor_file, 'data_file'=>$_data_file, 'trace_file' => $_trace_file, 'command' => $_command ));


		}

	}
 

	public function do_action(){

		//se è una chiamata AJAX allora....
		if ($this->input->is_ajax_request()) {


			$_command = '';
			$_action = $this->input->post('action');

			switch($_action){

				case 'stop':
					//$_command = 'M0';
                    $_command = 'M12';
					shell_exec ( 'sudo kill '.$this->input->post('pid') );
					break;
				case 'play':
					$_command = 'play';
					break;
				case 'pause':
					$_command = 'M1';
					break;
				case 'temp1':
					$_command = 'M104 S'.$this->input->post('value');
					break;
				case 'temp2':
					$_command = 'M140 S'.$this->input->post('value');
					break;
				case 'velocity':
					$_command = 'M220 S'.$this->input->post('value');
					break;

			}

            /** WRITE TO FILE DATA */
			file_put_contents($this->input->post('data_file'), $_command.PHP_EOL, FILE_APPEND | LOCK_EX);
				
			//aggiorno attributi del task
				
			//carico X class database
			$this->load->database();
			$this->load->model('tasks');
				
			$task = $this->tasks->get_by_id($this->input->post('id_task'));
				
			$attributes = json_decode($task->attributes, TRUE);
				
			$attributes[$_action] = $this->input->post('value');
				
			$_data_update['attributes'] = json_encode($attributes);
			$this->tasks->update($this->input->post('id_task'), $_data_update);
            
            
            $_response_items['status'] = 200;
            
            
            header('Content-Type: application/json');
            echo json_encode($_response_items); 
				

		}

	}



	public function monitor(){

		//se è una chiamata AJAX allora...
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


	public function updatetask(){

		if ($this->input->is_ajax_request()) {

			$id_task   = $this->input->post('id_task');
			$pid       = $this->input->post('pid');
			$start     = $this->input->post('start');
			$end       = $this->input->post('end');
			$completed = $this->input->post('completed');
			$_stopped  = $this->input->post('stopped');
				
			$_estimated_time = $this->input->post('estimated_time');
			$_progress_steps = $this->input->post('progress_steps');
				
				

			//carico X class database
			$this->load->database();
			$this->load->model('tasks');

			$task = $this->tasks->get_by_id($id_task);

			$attributes = json_decode($task->attributes, TRUE);

			$attributes['pid']       = $pid;
			$attributes['start']     = $start;
			$attributes['end']       = $end;
			$attributes['completed'] = $completed;
				
			$attributes['estimated_time'] = $_estimated_time;
			$attributes['progress_steps'] = $_progress_steps;


			$_data_update['attributes'] = json_encode($attributes);

			if($completed == 1){
					
				$_data_update['status'] = 'performed';
			}

			//if the printing proccess is stopped by the user

			if($_stopped == 1){

				$_data_update['status'] = 'stopped';
				$_data_update['finish_date'] = 'now()';
			}
				
				
			if($completed == 1 || $_stopped == 1){
			 
                /** TODO: ADD END_GCODE */

				sleep(2);
				//delete all temporaly files
				unlink($attributes['monitor']);
				unlink($attributes['data']);
				unlink($attributes['trace']);
				unlink($attributes['debug']);
			}


			$this->tasks->update($id_task, $_data_update);
            
            $_response_items['status'] = 200;
            
            
            header('Content-Type: application/json');
            echo json_encode($_response_items); 
            
            
            
		}

	}





	public function analyze(){
		
		$analyzer = $this->load->library('GcodeAnalyzer');
		
		
		print_r($analyzer);
			
	}


}