<?php 
/***
 * Scan Module
*
*/
class Scan extends Module {

	public function __construct()
	{
		parent::__construct();

	}


	/**
	 *
	 */
	public function index(){


		/**
		 * LOAD DATABASE MODEL
		 */
		$this->load->database();
		$this->load->model('scan_model');
		$this->load->model('tasks');


		/**
		 * LOAD HELPERS
		*/
		$this->load->helper('os_helper');



		/**
		 * CHECK IF IS TASK RUNNING
		*/
		$_task             = $this->tasks->get_running('scan', 'scan');
		$_scan_monitor     = '';
		$_pprocess_monitor = '';

		/**
		 * IF TASK IS RUNNING CHECK IF PROCESS IS STILL ALIVE
		 */
		if($_task){

			/** Load scan monitor file */
            
            if(file_exists (json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->scan_monitor)){
               $_scan_monitor     =  json_decode(file_get_contents(json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->scan_monitor)); 
            }
            /** Load pprocess monitor file */
            if(file_exists(json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->pprocess_monitor)){
                $_pprocess_monitor =  json_decode(file_get_contents(json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->pprocess_monitor));
            }	
			

			/** if process not exist, unset the task */
            
    			if(!exist_process(json_decode($_task['attributes'])->scan_pid) || !isset(json_decode($_task['attributes'])->scan_pid)){
    				
                    /** IF PROCESS DOESNT EXIST DELETE TASK RECORD FROM DB */
                   
                    $this->tasks->delete($_task['id']);
                    
                    $_task = FALSE;
                    
    			}
            
           
				
		}


		/**
		 * LOAD SCAN CONFIGURATIONS
		 */
		$mode_list    = $this->scan_model->get(array('type' => 'mode'));
		$quality_list = $this->scan_model->get(array('type' => 'quality'));




		/**
		 * IMPOSTAZIONI LAYOUT
		*/
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/fuelux/wizard/wizard.js', 'comment' => 'javascript for the wizard'));
        
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.css', 'comment' => 'javascript for the noUISlider'));
        
        /** JCROP */
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/jcrop/jquery.Jcrop.js', 'comment' => 'javascript for JCROP'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/jcrop/jquery.Jcrop.css', 'comment' => 'css for JCROP'));



		$data_for_steps['mode_list']           = $mode_list;
		$data_for_steps['_task']               = $_task;
		$data_for_steps['_task_attributes']    = $_task ? json_decode($_task['attributes']) : '';
        $data_for_steps['_monitor_attributes'] = $_task ? json_decode(file_get_contents($data_for_steps['_task_attributes']->folder.$data_for_steps['_task_attributes']->scan_monitor) ): '';
        $data_for_steps['_scan_monitor']       = $_task ? $_scan_monitor : '';
        $data_for_steps['_scan_stats']         = $_task ? json_decode(file_get_contents($data_for_steps['_task_attributes']->scan_stats_file) ): '';
        $data_for_steps['_pprocess_stats']     = $_task ? json_decode(file_get_contents($data_for_steps['_task_attributes']->pprocess_stats_file) ): '';
		$data_for_steps['_pprocess_monitor']   = $_task ? $_pprocess_monitor : '';
        
        //print_r($data_for_steps['_task_attributes']);
        //print_r($_pprocess_monitor);

		/**
		 *  LOAD STEPS
		 */
		$data['_step_1'] = $this->load->view('index/step1/index', $data_for_steps, TRUE);
		$data['_step_2'] = $this->load->view('index/step2/index', $data_for_steps, TRUE);
		$data['_step_3'] = $this->load->view('index/step3/index', $data_for_steps, TRUE);
		$data['_step_4'] = $this->load->view('index/step4/index', $data_for_steps, TRUE);
		$data['_step_5'] = $this->load->view('index/step5/index', $data_for_steps, TRUE);
		$data['_step_6'] = $this->load->view('index/step6/index', $data_for_steps, TRUE);
		$data['_task']   = $_task;
		$data['_task_attributes'] = $_task ?  json_decode($_task['attributes']) : '';


		/**
		 *  LOAD IN PAGE JS
		 */
		$js_data['mode_list']           = $mode_list;
		$js_data['quality_list']        = $quality_list;
		$js_data['_task']               = $_task;
		$js_data['_task_attributes']    = $_task ? json_decode($_task['attributes']) : '';
		$js_data['_scan_monitor']       = $_task ? $_scan_monitor : '';
		$js_data['_pprocess_monitor']   = $_task ? $_pprocess_monitor : '';
        $js_data['_monitor_attributes'] = $_task ? json_decode(file_get_contents($js_data['_task_attributes']->folder.$js_data['_task_attributes']->scan_monitor)) : '';
        $js_data['_scan_stats']         = $_task ? json_decode(file_get_contents($js_data['_task_attributes']->scan_stats_file) ): '';
        $js_data['_pprocess_stats']     = $_task ? json_decode(file_get_contents($js_data['_task_attributes']->pprocess_stats_file) ): '';

		$this->layout->add_js_in_page(array('data'=> $this->load->view('index/js', $js_data, TRUE), 'comment' => 'SCAN IN PAGE JS'));

		/**
		 * LOAD CSS IN PAGE
		*/
		$this->layout->add_css_in_page(array('data'=> $this->load->view('index/css', '', TRUE), 'comment' => 'SCAN IN PAGE CSS'));

        $this->layout->set_compress(false);
		$this->layout->view('index/index', $data);
	}





	/**
	 *  AJAX CALL TO START SCAN
	 */
	public function start(){

        if ($this->input->is_ajax_request()) {
    		/** INIT */
    		$mode = $this->input->post('mode');
    		/**
    		 * LOAD DATABASE MODEL
    		*/
    		$this->load->database();
    		$this->load->model('scan_model');
    		
    		$mode = $this->scan_model->get(array('id'=>$mode,    'type'=> 'mode'));
            
            /** SAVE POST HEADER PARAMATERS AS DATA*/
            $data = $this->input->post();
            
            switch($mode->id){
                
                case 6: /** ROTATING MODE */
                    $this->scan_rotating($data);
                    break;
                case 7: /** SWEEP MODE */
                    $this->scan_sweep($data);
                    break; 
                case 8; /** PROBING MODE */
                    $this->scan_probe($data);
                    break;
            }
        
        }

	}



	function monitor(){
		
		//se è una chiamata AJAX allora...
		if ($this->input->is_ajax_request()) {
				
			$id_task              = $this->input->post('task_id');
			$scan_file_monitor    = $this->input->post('scan_monitor_file');
			$process_file_monitor = $this->input->post('pprocess_monitor_file');
            $isprobing            = $this->input->post('isprobing');
            $isprobing            = $isprobing == 'true' ? true : false;
			
			/** load scan monitor file */
			$_status_scan     = json_decode(file_get_contents($scan_file_monitor), TRUE);
		
			$_response_items['scan']     = $_status_scan;
            
            if(!$isprobing){
                
               	/** load pprocess monitor file */
                $_status_pprocess = json_decode(file_get_contents($process_file_monitor), TRUE);
                $_response_items['pprocess'] = $_status_pprocess;
                
            }
			

			/** monitor response */
			header('Content-Type: application/json');
			echo json_encode($_response_items); 
		}
	}



	function updatetask(){

		if ($this->input->is_ajax_request()) {
				
			
			/** GET DATA FROM POST */
			$id_task            	   = $this->input->post('task_id');
			$scan_completed            = $this->input->post('scan_completed');
			$pprocess_completed        = $this->input->post('pprocess_completed');
//			$_scan_estimated_time      = $this->input->post('scan_estimated_time');
//			$_scan_progress_steps      = $this->input->post('scan_progress_steps');
			$_step                     = $this->input->post('step');
//			$_pprocess_estimated_times = $this->input->post('pprocess_estimated_time');
//			$_pprocess_progess_steps   = $this->input->post('pprocess_progress_steps');
//			$_scan_image               = $this->input->post('scan_image');
			$mesh_completed            = $this->input->post('mesh_completed');
			$mesh_completed            = $mesh_completed == true ? 1 : 0;



			//carico X class database
			$this->load->database();
			$this->load->model('tasks');

			$task = $this->tasks->get_by_id($id_task);

			$attributes = json_decode($task->attributes, TRUE);

			$attributes['scan_completed']          = $scan_completed;
			$attributes['pprocess_completed']      = $pprocess_completed;
			$attributes['scan_estimated_time']     = $_scan_estimated_time;
			$attributes['scan_progress_steps']     = $_scan_progress_steps;
			$attributes['pprocess_estimated_time'] = $_pprocess_estimated_times;
			$attributes['pprocess_progress_steps'] = $_pprocess_progess_steps;
			$attributes['step']                    = $_step;
//			$attributes['scan_image']              = $_scan_image;
			$attributes['mesh_completed']          = $mesh_completed;

			$_data_update['attributes']            = json_encode($attributes);

			if($scan_completed == 1 && $pprocess_completed == 1 && $mesh_completed == 1){
					
				$_data_update['status'] = 'performed';
			}

			//if the printing proccess is stopped by the user

			/*
			if($_stopped == 1){

				$_data_update['status'] = 'stopped';
				$_data_update['finish_date'] = 'now()';
			}
			*/



			$this->tasks->update($id_task, $_data_update);
				
			//echo $this->db->last_query();
		}

	}

	
	
	function mesh(){
		
		
		$task_id = $this->input->post("task_id");
		
		//carico X class database
		$this->load->database();
		$this->load->model('tasks');
		
		$task = $this->tasks->get_by_id($task_id);
		
		$_attributes = json_decode($task->attributes, TRUE);
		
		
		$_time          = $_attributes['time'];
		$_folder        = $_attributes['folder'];
		$_input_file    = $_folder.$_attributes['pprocess_file'];
		$_output_file   = 'mesh_'.$task_id.$_time.'.stl';
		$_filter_script = '/root/meshlab_script.mlx';
		$_mesh_monitor  = 'mesh_'.$task_id.$_time.'.monitor';
		$_mesh_debug    = 'mesh_'.$task_id.$_time.'.debug';
		
		$_xvfb_log_file = $_folder.'xvfb_'.$task_id.$_time.'.log';
		
		
		$_command_mesh = 'sudo xvfb-run -a -e '.$_xvfb_log_file.' meshlabserver -i '.$_input_file.' -s '.$_filter_script.' -o '.$_folder.$_output_file.' 1>'.$_folder.$_mesh_monitor. ' 2>'.$_folder.$_mesh_debug.' &  echo $!' ;
		
		$_output_command = shell_exec ( $_command_mesh );
		
		$_mesh_pid = trim(str_replace('\n', '', $_output_command));
		
		
		
		/** UPDATE TASK ATTRIBUTES */
		$_attributes['mesh_pid']     = $_mesh_pid;
		$_attributes['mesh_monitor'] = $_mesh_monitor;
		$_attributes['mesh_debug']   = $_mesh_debug;
		$_attributes['mesh_file']    = $_output_file;
		$_attributes['step']         = 5;
		
		$this->tasks->update($task_id, array('attributes' => json_encode($_attributes)));
		
		/** OUTPUT MESH INFO */
		$_response_items['mesh_pid']          = $_mesh_pid;
		$_response_items['command']           = $_command_mesh;
		$_response_items['mesh_monitor_file'] = $_mesh_monitor;
		
		/** mesh response */
		header('Content-Type: application/json');
		echo json_encode($_response_items);
		 
		
		
	}
	
	
	/**
	 * 
	 */
	function pid_check(){
		
		$pid = $this->input->post("pid");
		
		$this->load->helper('os_helper');
		
		$_response_items['exist'] = exist_process($pid) ? 1 : 0;
		
		/** mesh response */
		header('Content-Type: application/json');
		echo json_encode($_response_items);
		
	}
	
	
	/**
	 * CREATE AND SAVE OBJECT
	 */
	function object(){
		
		
		$task_id = $this->input->post("task_id");
		
		//carico X class database
		$this->load->database();
		$this->load->model('tasks');
		
		$task = $this->tasks->get_by_id($task_id);
		
		$_attributes = json_decode($task->attributes, TRUE);
		
		
		/**
		 * CREATE OBJECT
		 */
		$this->load->model('objects');
		
		$_obj_data['obj_name']        = 'scan_'.$task_id.'_'.$_attributes['time'];
		$_obj_data['obj_description'] = 'Scanned using raspberry Cam on '.date('l jS \of F Y h:i:s A');
		
		//inserisco il nuogo oggetto
		$_obj_id = $this->objects->insert_obj($_obj_data);
		
		
		/**
		 * INSERT FILE ROW
		 */
		$this->load->model('files');
		$this->load->helper('file');
		
		$info = get_file_info($_attributes['folder'].$_attributes['mesh_file']);
		
		
		$_data_file['file_name']   = $_attributes['mesh_file'];
		$_data_file['file_type']   = 'application/octet-stream';
		$_data_file['file_path']   = '/var/www/upload/stl/';
		$_data_file['full_path']   = '/var/www/upload/stl/'.$_attributes['mesh_file'];
		$_data_file['raw_name']    = str_replace('.stl', '', $_attributes['mesh_file']);
		$_data_file['orig_name']   = $_attributes['mesh_file'];
		$_data_file['client_name'] = $_attributes['mesh_file'];
		$_data_file['file_ext']    = '.stl';
		$_data_file['file_size']   = $info['size'];
		
		$id_file = $this->files->insert_file($_data_file);
		
		/** MOVE STL FILE TO UPLOAD/STL */
		rename($_attributes['folder'].$_attributes['mesh_file'], $_data_file['full_path']);
		
		/** DELETE SCAN FOLDER */
		
        delete_files($_attributes['folder'], TRUE);
		rmdir($_attributes['folder']);
		
		
		/** ASSOCIATE FILE TO OBJECT */
		$this->objects->insert_files($_obj_id, array($id_file));

		$_response_items['obj_id']         = $_obj_id;
		$_response_items['obj_name']       = $_obj_data['obj_name'];
		$_response_items['file_id']        = $id_file;
		$_response_items['file_name']      = $_data_file['file_name'];
		$_response_items['file_full_path'] = $_data_file['full_path'];
		
		/** mesh response */
		header('Content-Type: application/json');
		echo json_encode($_response_items);
		
		
	}
    
    
    /**
     *  STOP SCAN PROCESS
     */
    function stop(){
        
        if ($this->input->is_ajax_request()) {
            
            /** INIT */
            $_task_id = $this->input->post('task_id');
            
            /** LOAD MODEL */
            $this->load->model('tasks');
            $_task = $this->tasks->get_by_id($_task_id);
            
            $this->tasks->update($_task_id, array('status' => 'stopped', 'finish_date' =>'now()'));
            
            /** REMOVE FOLDERS AND FILES */
            $this->load->helper("file"); // load the helper
            delete_files(json_decode($_task->attributes)->folder, true);
            rmdir(json_decode($_task->attributes)->folder);
            
            $_response_items['status'] = 'ok';
            
            /** RESPONSE */
            header('Content-Type: application/json');
            echo json_encode($_response_items);
            
            
        }
    }
    
    
    
    
    
    /**
     * 
     * SCAN ROTATING MODE 
     */
    function scan_rotating($param){
        
        
        $mode = 6;
        
        /** LOAD DATABASE */
        $this->load->model('tasks');
        
        /** LOAD HELPERS */
        $this->load->helper('file');
        
        /** LOAD QUALITY ATTRIBUTES */
        $quality        = $this->scan_model->get(array('id'=>$param['quality'], 'type'=> 'quality'));
		$quality_values = json_decode($quality->values);
		$quality_values = $quality_values->values;
        
        
        /**
		 * ADD TASK
		 */
		$_task_data['controller'] = 'scan';
		$_task_data['type']       = 'scan';
		$_task_data['status']     = 'running';
        
        $id_task = $this->tasks->add_task($_task_data);
        
        
        /**
		 * CREATE FOLDERS AND FILES
		*/
        $_time      = time();
        $task_files = $this->crate_folders_files($id_task, $mode, $_time);
        
        
        /** LAUNCH SCAN COMMAND */
        $_command_scan = 'sudo python /var/www/myfabtotum/python/r_scan.py -s'.$quality_values->slices.' -d'.$task_files['destination_folder'].' -l'.$task_files['scan_monitor_file'].' -i'.$quality_values->iso.' -b'.$quality_values->b.' -e'.$quality_values->e.' -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.'  2>'.$task_files['destination_folder'].$task_files['scan_debug_file'].'  > /dev/null & echo $!';
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = trim(str_replace('\n', '', $_output_scan));
        
        
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME */
        while(file_get_contents($task_files['destination_folder'].$task_files['scan_monitor_file']) == ''){   
            //aspetto
            sleep(0.5);
        }
        
        
        /** LAUNC PPROCESS COMMAND */
        $_param_for_triangulation = '-mr';
        $_command_pprocessing     = 'sudo python /var/www/myfabtotum/python/triangulation.py -i'.$task_files['destination_folder'].'images/ -o'.$task_files['destination_folder'].$task_files['pprocess_file'].' -s'.$quality_values->slices.' -b0 -e360 -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.' -z0 -a0 -l'.$task_files['destination_folder'].$task_files['pprocess_monitor_file'].' '.$_param_for_triangulation.' 2>'.$task_files['destination_folder'].$task_files['pprocess_debug_file'].' > /dev/null & echo $!';
        $_output_pprocessing      = shell_exec ( $_command_pprocessing );
		$_pprocess_pid            = trim(str_replace('\n', '', $_output_pprocessing));
        
        
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME */
        while(file_get_contents($task_files['destination_folder'].$task_files['pprocess_monitor_file']) == ''){   
            //aspetto
            sleep(0.5);
        }
        
         
        /**
		 *  DATA FOR UPDATING TASK
		*/
		$_attributes_items['scan_pid']            = $_scan_pid;
		$_attributes_items['folder']              = $task_files['destination_folder'];
		$_attributes_items['time']                = $_time;
		$_attributes_items['scan_monitor']        = $task_files['scan_monitor_file'];
		$_attributes_items['scan_uri']            = $task_files['uri'];
		$_attributes_items['mode']                = 6; 
		$_attributes_items['mode_name']           = 'rotating';
		$_attributes_items['step']                = 4; 
        $_attributes_items['pprocess_pid']        = $_pprocess_pid;
        $_attributes_items['pprocess_monitor']    = $task_files['pprocess_monitor_file'];
        $_attributes_items['pprocess_file']       = $task_files['pprocess_file'];
        $_attributes_items['slices']              = $quality_values->slices;
		$_attributes_items['iso']                 = $quality_values->iso;
		$_attributes_items['width']               = $quality_values->resolution->width;
		$_attributes_items['height']              = $quality_values->resolution->height;
        $_attributes_items['quality']             = $quality->id;
        $_attributes_items['quality_name']        = json_decode($quality->values)->info->name;
        $_attributes_items['scan_stats_file']     = $task_files['destination_folder'].$task_files['scan_stats_file'];
        $_attributes_items['pprocess_stats_file'] = $task_files['destination_folder'].$task_files['pprocess_stats_file'];
        
        /** UPDATE TASK */
        $_data_update['attributes']= json_encode($_attributes_items);
        $this->tasks->update($id_task, $_data_update);
        
        /** DATA FOR RESPONSE */
        $_response_items['task_id']               = $id_task;
		$_response_items['scan_monitor_file']     = $task_files['destination_folder'].$task_files['scan_monitor_file'];
		$_response_items['scan_uri']              = $task_files['uri'];
		$_response_items['folder']                = $task_files['destination_folder'];
		$_response_items['scan_command']          = $_command_scan;
		$_response_items['scan_pid']              = $_scan_pid;
        $_response_items['pprocess_command']      = $_command_pprocessing;
        $_response_items['pprocess_monitor_file'] = $task_files['destination_folder'].$task_files['pprocess_monitor_file'];
        $_response_items['pprocess_pid']          = $_pprocess_pid;
        $_response_items['scan_stats_file']       = $task_files['destination_folder'].$task_files['scan_stats_file'];
        $_response_items['pprocess_stats_file']   = $task_files['destination_folder'].$task_files['pprocess_stats_file'];
                
        header('Content-Type: application/json');
		echo json_encode($_response_items);
 
    }
    
    
    
    
    
    /**
     * 
     * SCAN SWEEP MODE 
     */
    function scan_sweep($param){
        
        
        $mode = 7;
        
        /** LOAD DATABASE */
        $this->load->model('tasks');
        
        /** LOAD HELPERS */
        $this->load->helper('file');
        
        /** LOAD QUALITY ATTRIBUTES */
        $quality        = $this->scan_model->get(array('id'=>$param['quality'], 'type'=> 'quality'));
		$quality_values = json_decode($quality->values);
		$quality_values = $quality_values->values;
        
        
        /**
		 * ADD TASK
		 */
		$_task_data['controller'] = 'scan';
		$_task_data['type']       = 'scan';
		$_task_data['status']     = 'running';
        
        $id_task = $this->tasks->add_task($_task_data);
        
        
        /**
		 * CREATE FOLDERS AND FILES
		*/
        $_time = time();
        $task_files = $this->crate_folders_files($id_task, $mode, $_time);
        
        
        /** LAUNCH SCAN COMMAND */
        $_command_scan = 'sudo python /var/www/myfabtotum/python/s_scan.py -s'.$quality_values->slices.' -i'.$quality_values->iso.' -d'.$task_files['destination_folder'].' -l'.$task_files['scan_monitor_file'].' -b'.$param['x1'].' -e'.$param['x2'].' -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.' 2>'.$task_files['destination_folder'].$task_files['scan_debug_file'].'  > /dev/null & echo $!';
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = trim(str_replace('\n', '', $_output_scan));
        
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME */
        while(file_get_contents($task_files['destination_folder'].$task_files['scan_monitor_file']) == ''){   
            //aspetto
            sleep(0.5);
        }
        
        
        /** LAUNC PPROCESS COMMAND */
        $_param_for_triangulation = '-ms';
        $_command_pprocessing     = 'sudo python /var/www/myfabtotum/python/triangulation.py -i'.$task_files['destination_folder'].'images/ -o'.$task_files['destination_folder'].$task_files['pprocess_file'].' -s'.$quality_values->slices.' -b0 -e360 -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.' -z0 -a0 -l'.$task_files['destination_folder'].$task_files['pprocess_monitor_file'].' '.$_param_for_triangulation.' 2>'.$task_files['destination_folder'].$task_files['pprocess_debug_file'].' > /dev/null & echo $!';
        $_output_pprocessing      = shell_exec ( $_command_pprocessing );
		$_pprocess_pid            = trim(str_replace('\n', '', $_output_pprocessing));
        
        
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME */
        while(file_get_contents($task_files['destination_folder'].$task_files['pprocess_monitor_file']) == ''){   
            //aspetto
            sleep(0.5);
        }
        
        
        /**
		 *  DATA FOR UPDATING TASK
		*/
		$_attributes_items['scan_pid']            = $_scan_pid;
		$_attributes_items['folder']              = $task_files['destination_folder'];
		$_attributes_items['time']                = $_time;
		$_attributes_items['scan_monitor']        = $task_files['scan_monitor_file'];
		$_attributes_items['scan_uri']            = $task_files['uri'];
		$_attributes_items['mode']                = 7; 
		$_attributes_items['mode_name']           = 'sweep';
		$_attributes_items['step']                = 4; 
        $_attributes_items['pprocess_pid']        = $_pprocess_pid;
        $_attributes_items['pprocess_monitor']    = $task_files['pprocess_monitor_file'];
        $_attributes_items['pprocess_file']       = $task_files['pprocess_file'];
        $_attributes_items['slices']              = $quality_values->slices;
		$_attributes_items['iso']                 = $quality_values->iso;
		$_attributes_items['width']               = $quality_values->resolution->width;
		$_attributes_items['height']              = $quality_values->resolution->height;
        $_attributes_items['quality']             = $quality->id;
        $_attributes_items['quality_name']        = json_decode($quality->values)->info->name;
        $_attributes_items['start']               = $param['x1'];
		$_attributes_items['end']                 = $param['x2'];
        $_attributes_items['scan_stats_file']     = $task_files['destination_folder'].$task_files['scan_stats_file'];
        $_attributes_items['pprocess_stats_file'] = $task_files['destination_folder'].$task_files['pprocess_stats_file'];
        
        /** UPDATE TASK */
        $_data_update['attributes']= json_encode($_attributes_items);
        $this->tasks->update($id_task, $_data_update);
        
        /** DATA FOR RESPONSE */
        $_response_items['task_id']               = $id_task;
		$_response_items['scan_monitor_file']     = $task_files['destination_folder'].$task_files['scan_monitor_file'];
		$_response_items['scan_uri']              = $task_files['uri'];
		$_response_items['folder']                = $task_files['destination_folder'];
		$_response_items['scan_command']          = $_command_scan;
		$_response_items['scan_pid']              = $_scan_pid;
        $_response_items['pprocess_command']      = $_command_pprocessing;
        $_response_items['pprocess_monitor_file'] = $task_files['destination_folder'].$task_files['pprocess_monitor_file'];
        $_response_items['pprocess_pid']          = $_pprocess_pid;
        $_response_items['scan_stats_file']       = $task_files['destination_folder'].$task_files['scan_stats_file']; 
        $_response_items['pprocess_stats_file']   = $task_files['destination_folder'].$task_files['pprocess_stats_file'];
        
        
        header('Content-Type: application/json');
		echo json_encode($_response_items);
        
        
        
    }
    
    
    
    
    /**
     * 
     * SCAN PROBE MODE 
     */
    function scan_probe($param){
        
        
        $mode = 8;
        
        /** LOAD DATABASE */
        $this->load->model('tasks');
        
        /** LOAD HELPERS */
        $this->load->helper('file');
        
        
        /**
		 * ADD TASK
		 */
		$_task_data['controller'] = 'scan';
		$_task_data['type']       = 'scan';
		$_task_data['status']     = 'running';
        
        $id_task = $this->tasks->add_task($_task_data);
        
        
        /**
		 * CREATE FOLDERS AND FILES
		*/
        $_time = time();
        $task_files = $this->crate_folders_files($id_task, $mode, $_time);
        
        
        /** LAUNCH SCAN COMMAND */
        $_command_scan = 'sudo python /var/www/myfabtotum/python/p_scan.py -x'.$param['x1'].' -y.'.$param['y1'].' -i'.$param['x2'].' -j'.$param['y2'].' -n'.$param['density'].' -a'.$param['axis_increment'].' -b'.$param['start_degree'].' -e'.$param['end_degree'].' -l'.$task_files['scan_monitor_file'].' -d'.$task_files['destination_folder'].' -v1 -t'.$task_files['probing_trace_file'].' 2>'.$task_files['destination_folder'].$task_files['probing_debug_file'].'  & echo $!';
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = trim(str_replace('\n', '', $_output_scan));
        
        
         
        /**
		 *  DATA FOR UPDATING TASK
		*/
		$_attributes_items['scan_pid']           = $_scan_pid;
		$_attributes_items['folder']             = $task_files['destination_folder'];
		$_attributes_items['time']               = $_time;
		$_attributes_items['scan_monitor']       = $task_files['scan_monitor_file'];
		$_attributes_items['mode']               = 8; 
		$_attributes_items['mode_name']          = 'probing';
		$_attributes_items['step']               = 4; 
        $_attributes_items['x1']                 = $param['x1'];
		$_attributes_items['x2']                 = $param['x2'];
        $_attributes_items['y1']                 = $param['y1'];
		$_attributes_items['y2']                 = $param['y2'];
    	$_attributes_items['density']            = $param['density'];
    	$_attributes_items['start_degree']       = $param['start_degree'];
        $_attributes_items['end_degree']         = $param['end_degree'];
        $_attributes_items['axis_increment']     = $param['axis_increment'];
        $_attributes_items['probing_trace_file'] = $task_files['probing_trace_file'];
        
        /** UPDATE TASK */
        $_data_update['attributes']= json_encode($_attributes_items);
        $this->tasks->update($id_task, $_data_update);
        
        /** DATA FOR RESPONSE */
        $_response_items['task_id']               = $id_task;
		$_response_items['scan_monitor_file']     = $task_files['destination_folder'].$task_files['scan_monitor_file'];
		$_response_items['scan_uri']              = $task_files['uri'];
		$_response_items['folder']                = $task_files['destination_folder'];
		$_response_items['scan_command']          = $_command_scan;
		$_response_items['scan_pid']              = $_scan_pid;
        $_response_items['probing_trace_file']    = $task_files['probing_trace_file'];
        
        
        header('Content-Type: application/json');
		echo json_encode($_response_items);
        
        
        
    }
    
    /**
     * CREATE FILES AND FOLDERS FOR THE TASK
     */
     function crate_folders_files($id_task, $mode, $_time){
        
        $list  = array();
        
       	$list['destination_folder'] = '/var/www/tasks/scan_'.$id_task.'_'.$_time.'/';
        $list['scan_monitor_file']  = 'scan_'.$id_task.'_'.$_time.'.monitor';
        $list['scan_monitor_file']  = 'scan_'.$id_task.'_'.$_time.'.monitor';
        $list['uri']                = '/tasks/scan_'.$id_task.'_'.$_time.'/';
        $list['scan_debug_file']    = 'scan_'.$id_task.'_'.$_time.'.debug';
        $list['scan_stats_file']    = 'scan_'.$id_task.'_'.$_time.'_stats.json';
        
        switch($mode){
            case 6:
            case 7:
                $list['pprocess_monitor_file'] = 'pprocess_'.$id_task.'_'.$_time.'.monitor';
                $list['pprocess_file']         = 'pprocess_'.$id_task.'_'.$_time.'.asc';
                $list['pprocess_debug_file']   = 'pprocess_'.$id_task.'_'.$_time.'.debug';
                $list['pprocess_stats_file']   = 'pprocess_'.$id_task.'_'.$_time.'_stats.json';
                break;
            case 8:
                 $list['probing_trace_file']   = 'probe_trace_'.$id_task.'_'.$_time.'.trace';
                 $list['probing_debug_file']   = 'probe_'.$id_task.'_'.$_time.'.debug';
                break;
        }
        
        
        
        /** CREAE FILES AND FOLDERS */
		mkdir($list['destination_folder'], 0777);
        /** create scan monitor file */
		write_file($list['destination_folder'].$list['scan_monitor_file'], '', 'w');
        
        
		switch($mode){
		  
            case 6:
            case 7:
                /** images folder */
                mkdir($list['destination_folder'].'images/', 0777);
                /** create pprocess monitor file */
                write_file($list['destination_folder'].$list['pprocess_monitor_file'], '', 'w');
                /** create pprocess file */
                write_file($list['destination_folder'].$list['pprocess_file'], '', 'w');
                /** create stats pprocess file */
                write_file($list['destination_folder'].$list['pprocess_stats_file'], '', 'w');
                /** create stats scan file */
                write_file($list['destination_folder'].$list['scan_stats_file'], '', 'w');
                break;
            case 8:
                /** create probing trace file */
                write_file($list['destination_folder'].$list['probing_trace_file'], '', 'w');
                break;
		}
        
        
        return $list;
        
        
     }


}

?>