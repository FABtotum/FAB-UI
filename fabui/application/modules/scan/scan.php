<?php 
/***
 * Scan Module
*
*/
class Scan extends Module {

	public function __construct()
	{
		parent::__construct();
        
        $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT CHANGE SETTINGS  */
        if(is_printer_busy('scan')){
            redirect('dashboard');
        }
        
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);
        
	}
    
	/**
	 *
	 */
	public function index(){

        //ini_set('error_reporting', E_ALL);
        //error_reporting(E_ALL); 
		/**
		 * LOAD DATABASE MODEL
		 */
		$this->load->database();
		$this->load->model('scan_model');
		$this->load->model('tasks');
        $this->load->model('objects');

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


            $_task_attributes = json_decode($_task['attributes'], true);
			

			/** Load scan monitor file */
            
            if(isset($_task_attributes['scan_monitor'])){
                
                
                
                if(file_exists($_task_attributes['scan_monitor'])){
                //if(file_exists (json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->scan_monitor)){
                   // $_scan_monitor     =  json_decode(file_get_contents(json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->scan_monitor));
                    
                    
                    $_scan_monitor = json_decode(file_get_contents($_task_attributes['scan_monitor']), true);
					
					
                    
                   
                }
            }
            
            /** Load pprocess monitor file */
            
            if(isset($_task_attributes['pprocess_monitor'])){
                
                if(file_exists($_task_attributes['folder'].$_task_attributes['pprocess_monitor'])){
                //if(file_exists(json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->pprocess_monitor)){
                    //$_pprocess_monitor =  json_decode(file_get_contents(json_decode($_task['attributes'])->folder.json_decode($_task['attributes'])->pprocess_monitor));
                    $_pprocess_monitor = json_decode(file_get_contents($_task_attributes['folder'].$_task_attributes['pprocess_monitor']), true); 
                    
                }
            }
			
			
			
			
			

			/** if process not exist, unset the task */
			
			//pprocess_pid
			if(isset(json_decode($_task['attributes'])->pprocess_pid)){
				
				
				
				
				if(!exist_process(json_decode($_task['attributes'])->scan_pid) && !exist_process(json_decode($_task['attributes'])->pprocess_pid)){
					
					$this->tasks->delete($_task['id']);
					$_task = FALSE;
					
					
				}
				
				
			}else{
    		if(!exist_process(json_decode($_task['attributes'])->scan_pid) || !isset(json_decode($_task['attributes'])->scan_pid)){
    				
                    /** IF PROCESS DOESNT EXIST DELETE TASK RECORD FROM DB */
                   
                    $this->tasks->delete($_task['id']);
                    
                    $_task = FALSE;
                    
    			}
			
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
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/fuelux/wizard/wizard.min.js', 'comment' => 'javascript for the wizard'));
        
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/masked-input/jquery.maskedinput.min.js', 'comment' => 'masked input'));
		
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.all.min.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.min.css', 'comment' => 'javascript for the noUISlider'));
        
        /** JCROP */
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/jcrop/jquery.Jcrop.min.js', 'comment' => 'javascript for JCROP'));
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/jcrop/jquery.color.min.js', 'comment' => 'javascript for JCROP'));
        //$this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/jcrop/jquery.Jcrop.css', 'comment' => 'css for JCROP'));



		$data_for_steps['mode_list']           = $mode_list;
		$data_for_steps['_task']               = $_task;
		$data_for_steps['_task_attributes']    = $_task ? json_decode($_task['attributes'], true) : '';
        $data_for_steps['_monitor_attributes'] = $_task ? json_decode(file_get_contents($data_for_steps['_task_attributes']['scan_monitor']) ): '';
        $data_for_steps['_scan_monitor']       = $_task ? $_scan_monitor : '';
        $data_for_steps['_scan_stats']         = $_task && isset($data_for_steps['_task_attributes']['scan_stats_file']) ? json_decode(file_get_contents($data_for_steps['_task_attributes']['scan_stats_file']), true): '';
        $data_for_steps['_pprocess_stats']     = $_task && isset($data_for_steps['_task_attributes']['pprocess_stats_file']) ? json_decode(file_get_contents($data_for_steps['_task_attributes']['pprocess_stats_file']), true ): '';
		$data_for_steps['_pprocess_monitor']   = $_task ? $_pprocess_monitor : '';
        $data_for_steps['_objects']            = $this->objects->get_all();
        
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
		$data['_task_attributes'] = $_task ?  json_decode($_task['attributes'], true) : '';


		/**
		 *  LOAD IN PAGE JS
		 */
		$js_data['mode_list']           = $mode_list;
		$js_data['quality_list']        = $quality_list;
		$js_data['_task']               = $_task;
		$js_data['_task_attributes']    = $_task ? json_decode($_task['attributes'], true) : '';
		$js_data['_scan_monitor']       = $_task ? $_scan_monitor : '';
		$js_data['_pprocess_monitor']   = $_task ? $_pprocess_monitor : '';
		$js_data['_scan_monitor_response'] = $_task ? file_get_contents($js_data['_task_attributes']['scan_monitor']) : '{}';
		
		
		
        $js_data['_monitor_attributes'] = $_task ? json_decode(file_get_contents($js_data['_task_attributes']['scan_monitor'])) : '';
        $js_data['_scan_stats']         = $_task && isset($js_data['_task_attributes']['scan_stats_file'])? json_decode(file_get_contents($js_data['_task_attributes']['scan_stats_file']), true): '';
        $js_data['_pprocess_stats']     = $_task && isset($js_data['_task_attributes']['pprocess_stats_file'])? json_decode(file_get_contents($js_data['_task_attributes']['pprocess_stats_file']), true): '';
		
		
		
		
		$js_data['_pprocess_monitor_response'] = $_task && isset($js_data['_task_attributes']['pprocess_monitor']) ? file_get_contents($js_data['_task_attributes']['folder'].$js_data['_task_attributes']['pprocess_monitor']) : '{}';
		
		
		
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
    		$mode = $this->input->post('mode', TRUE);
    		/**
    		 * LOAD DATABASE MODEL
    		*/
    		$this->load->database();
    		$this->load->model('scan_model');
    		
    		$mode = $this->scan_model->get(array('id'=>$mode, 'type'=> 'mode'));
            
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
				case 15: /** PG MODE */
					$this->scan_pg($data);
					break;
            }
			
			//shell_exec('sudo python '.PYTHONPATH.'websocket_tasks.py');
        
        }

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
		
		$info = get_file_info($_attributes['folder'].$_attributes['pprocess_file']);
		
		
		$_data_file['file_name']   = $_attributes['pprocess_file'];
		$_data_file['file_type']   = 'application/octet-stream';
		$_data_file['file_path']   = '/var/www/upload/asc/';
		$_data_file['full_path']   = '/var/www/upload/asc/'.$_attributes['pprocess_file'];
		$_data_file['raw_name']    = str_replace('.asc', '', $_attributes['pprocess_file']);
		$_data_file['orig_name']   = $_attributes['pprocess_file'];
		$_data_file['client_name'] = $_attributes['pprocess_file'];
		$_data_file['file_ext']    = '.asc';
		$_data_file['file_size']   = $info['size'];
		
		$id_file = $this->files->insert_file($_data_file);
		
		/** MOVE STL FILE TO UPLOAD/STL */
		rename($_attributes['folder'].$_attributes['pprocess_file'], $_data_file['full_path']);
		
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
     
    function stop(){
        
        
        
        
        
        //KILL
        
        
        //LANCIO MACRO end_scan
        
        
        
        //FINALIZE
        
        if ($this->input->is_ajax_request()) {
            
            /** INIT 
            $_task_id = $this->input->post('task_id');
            
            //$pid      = $this->input->post('pid');
            //$ppid     = $this->input->post('ppid');
            
            
            //carico X class database
    		$this->load->database();
    		$this->load->model('tasks');
    		
    		$_task = $this->tasks->get_by_id($_task_id);
    		
    		$_attributes = json_decode($_task->attributes, TRUE);
            
            
            
                        
            /** KILL PROCESS 
            $_command_kill = 'sudo kill '.$_attributes['scan_pid'];
            shell_exec ( $_command_kill );
            
            sleep(1);
            /** KILL POST-PROCESSING PROCESS 
            if(isset($_attributes['pp_pid'])){
                
                
                $_command_kill = 'sudo kill '.$_attributes['pp_pid'];
                shell_exec ( $_command_kill );
            
                sleep(1);
                
            }
           
            
            /** CREATE LOG FILES 
            $_time                 = time();
            $_destination_trace    = '/var/www/temp/end_scan'.$_time.'.trace';
            $_destination_response = '/var/www/temp/end_scan'.$_time.'.log';
            
            $this->load->helper('file');
            
            write_file($_destination_trace, '', 'w');
            chmod($_destination_trace, 0777);
            
            write_file($_destination_response, '', 'w');
            chmod($_destination_response, 0777);
            
            /** EXEC MACRO 
            
            $_command        = 'sudo python /var/www/fabui/python/gmacro.py end_scan '.$_destination_trace.' '.$_destination_response;
            $_output_command = shell_exec ( $_command );
            //$_pid            = trim(str_replace('\n', '', $_output_command));
            
            sleep(1);
            
            
            
            /** FINALIZE 
            $_command_finalize = 'sudo php /var/www/fabui/script/finalize.php '.$_task_id. ' scan stopped';
            $_output_command = shell_exec ( $_command_finalize );

            /** LOAD MODEL 
            $this->load->model('tasks');
            $_task = $this->tasks->get_by_id($_task_id);
            
            $this->tasks->update($_task_id, array('status' => 'stopped', 'finish_date' =>'now()'));
            
            /** REMOVE FOLDERS AND FILES 
            $this->load->helper("file"); // load the helper
            delete_files(json_decode($_task->attributes)->folder, true);
            rmdir(json_decode($_task->attributes)->folder);
            
            $_response_items['status'] = 'ok';
            
            /** RESPONSE 
            
            
            $_response_items['status'] = 'ok';
            
            header('Content-Type: application/json');
            echo json_encode($_response_items);
            
            
        }
    }
    
    
    */
    
    
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
        $_task_data['user']       = $_SESSION['user']['id'];
		$_task_data['controller'] = 'scan';
		$_task_data['type']       = 'scan';
		$_task_data['status']     = 'running';
        
        $id_task = $this->tasks->add_task($_task_data);
		
		
		shell_exec('sudo php '.SCRIPTPATH.'/notifications.php &');
        
        
        /**
		 * CREATE FOLDERS AND FILES
		*/
        $_time      = time();
        $task_files = $this->crate_folders_files($id_task, $mode, $_time);
        
        
        /** LAUNCH SCAN COMMAND */
        $_command_scan = 'sudo python /var/www/fabui/python/r_scan.py -s'.$quality_values->slices.' -d'.$task_files['destination_folder'].' -l'.$task_files['scan_monitor_file'].' -i'.$quality_values->iso.' -b'.$quality_values->b.' -e'.$quality_values->e.' -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.'  2>'.$task_files['destination_folder'].$task_files['scan_debug_file'].'  > /dev/null & echo $!';
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = intval(trim(str_replace('\n', '', $_output_scan))) + 1;
        
        sleep(1);
		
		/*
		while(filesize($task_files['scan_monitor_file']) <= 0){
			sleep(0.1);
		}
		*/
		
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME 
        while(file_get_contents($task_files['scan_monitor_file']) == ''){   
            //aspetto
            sleep(0.1);
        }
        */
        
		//krios
		
        /** LAUNC PPROCESS COMMAND */
        $_param_for_triangulation = '-mr';
        $_command_pprocessing     = 'sudo python /var/www/fabui/python/triangulation.py -i'.$task_files['destination_folder'].'images/ -o'.$task_files['destination_folder'].$task_files['pprocess_file'].' -s'.$quality_values->slices.' -b0 -e360 -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.' -z0 -a0 -l'.$task_files['destination_folder'].$task_files['pprocess_monitor_file'].' '.$_param_for_triangulation.' -t'.$id_task.' 2>'.$task_files['destination_folder'].$task_files['pprocess_debug_file'].' > /dev/null & echo $!';
        $_output_pprocessing      = shell_exec ( $_command_pprocessing );
		$_pprocess_pid            = intval(trim(str_replace('\n', '', $_output_pprocessing)))+1;
        
		
		/*
		while(filesize($task_files['destination_folder'].$task_files['pprocess_monitor_file']) <= 0){
			sleep(0.1);
		}
        */
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME 
        while(file_get_contents($task_files['destination_folder'].$task_files['pprocess_monitor_file']) == ''){   
            //aspetto
            sleep(0.1);
        }
        */
         
        /**
		 *  DATA FOR UPDATING TASK
		*/
        $_attributes_items['new']                 = $param['new_object'];
        $_attributes_items['id_obj']              = $param['obj_id'];
        $_attributes_items['obj_name']            = $param['obj_name'];
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
        $_task_data['user']       = $_SESSION['user']['id'];
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
        $_command_scan = 'sudo python /var/www/fabui/python/s_scan.py -s'.$quality_values->slices.' -i'.$quality_values->iso.' -d'.$task_files['destination_folder'].' -l'.$task_files['scan_monitor_file'].' -b'.$param['x1'].' -e'.$param['x2'].' -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.' 2>'.$task_files['destination_folder'].$task_files['scan_debug_file'].'  > /dev/null & echo $!';
        
        
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = trim(str_replace('\n', '', $_output_scan));
        
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME */
        while(file_get_contents($task_files['scan_monitor_file'], FILE_USE_INCLUDE_PATH) == ''){   
            //aspetto
            sleep(0.5);
        }
        
        
        /** LAUNC PPROCESS COMMAND */
        $_param_for_triangulation = '-ms';
        $_command_pprocessing     = 'sudo python /var/www/fabui/python/triangulation.py -i'.$task_files['destination_folder'].'images/ -o'.$task_files['destination_folder'].$task_files['pprocess_file'].' -s'.$quality_values->slices.'  -b'.$param['x1'].' -e'.$param['x2'].' -w'.$quality_values->resolution->width.' -h'.$quality_values->resolution->height.' -z0 -a'.$param['a_offset'].' -l'.$task_files['destination_folder'].$task_files['pprocess_monitor_file'].' '.$_param_for_triangulation.' -t'.$id_task.' 2>'.$task_files['destination_folder'].$task_files['pprocess_debug_file'].' > /dev/null & echo $!';
        $_output_pprocessing      = shell_exec ( $_command_pprocessing );
		$_pprocess_pid            = trim(str_replace('\n', '', $_output_pprocessing));
        
		
		
		
		
		//echo $task_files['destination_folder'].$task_files['pprocess_monitor_file']; exit();
        
        /** WAIT FOR FILE TO BE WRITTEN FOR THE FIRST TIME
        while(file_get_contents($task_files['destination_folder'].$task_files['pprocess_monitor_file']) == ''){   
            //aspetto
            sleep(0.5); 
        }
		 *  */
        
        
        /**
		 *  DATA FOR UPDATING TASK
		*/
        $_attributes_items['new']                 = $param['new_object'];
        $_attributes_items['id_obj']              = $param['obj_id'];
        $_attributes_items['obj_name']            = $param['obj_name'];
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
        
		
		$param['axis_increment'] = isset($param['axis_increment']) ? $param['axis_increment'] : 0;
		$param['start_degree']   = isset($param['start_degree']) ? $param['start_degree'] : 0;
		$param['end_degree']     = isset($param['end_degree']) ? $param['end_degree'] : 0;
		$param['z_hop']          = isset($param['z_hop']) ? $param['z_hop'] : 0;
		$param['probe_skip']     = isset($param['probe_skip']) ? $param['probe_skip'] : 0;
       
     	   
        $mode = 8;
        
        /** LOAD DATABASE */
        $this->load->model('tasks');
        $this->load->model('scan_model');
        
        /** LOAD HELPERS */
        $this->load->helper('file');
        
        
		
        /**
		 * ADD TASK
		 */
        $_task_data['user']       = $_SESSION['user']['id'];
		$_task_data['controller'] = 'scan';
		$_task_data['type']       = 'scan';
		$_task_data['status']     = 'running';
        
        $id_task = $this->tasks->add_task($_task_data);
        
        
		shell_exec('sudo php '.SCRIPTPATH.'/notifications.php &');
		
        /**
		 * CREATE FOLDERS AND FILES
		*/
        $_time = time();
        $task_files = $this->crate_folders_files($id_task, $mode, $_time);
        
        
        
        /** LOAD PROBE DENSITY QUALITY */
        $probe_quality = $this->scan_model->get(array('id'=>$param['probe_quality'], 'type'=> 'probe_quality'));
        $probe_quality = json_decode($probe_quality->values, true);
		$probe_quality = $probe_quality['values'];
        
        
		//$y_max = 235;
		
		//$param['y1'] = $y_max - $param['y1'];
		//$param['y2'] = $y_max - $param['y2'];
		
        
        /** LAUNCH SCAN COMMAND */
        $_command_scan = 'sudo python /var/www/fabui/python/p_scan.py -x'.$param['x1'].' -y'.$param['y1'].' -i'.$param['x2'].' -j'.$param['y2'].' -n'.$probe_quality['mm'].' -a'.$param['axis_increment'].' -b'.$param['start_degree'].' -e'.$param['end_degree'].' -z'.$param['z_hop'].' -p'.$param['probe_skip'].'  -l'.$task_files['scan_monitor_file'].' -d'.$task_files['destination_folder'].' -v1 -t'.$task_files['destination_folder'].$task_files['probing_trace_file'].' -k'.$id_task.' 2>'.$task_files['destination_folder'].$task_files['probing_debug_file'].'  > /dev/null & echo $!'; 
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = intval(trim(str_replace('\n', '', $_output_scan)))+1;
		
        
        
         
        /**
		 *  DATA FOR UPDATING TASK
		*/
        $_attributes_items['new']                = $param['new_object'];
        $_attributes_items['id_obj']             = $param['obj_id'];
        $_attributes_items['obj_name']           = $param['obj_name'];
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
    	$_attributes_items['density']            = $probe_quality['mm'];
    	$_attributes_items['start_degree']       = $param['start_degree'];
        $_attributes_items['end_degree']         = $param['end_degree'];
        $_attributes_items['axis_increment']     = $param['axis_increment'];
        $_attributes_items['probing_trace_file'] = $task_files['probing_trace_file'];
        
        /** UPDATE TASK */
        $_data_update['attributes']= json_encode($_attributes_items);
        $this->tasks->update($id_task, $_data_update);
        
        /** DATA FOR RESPONSE */
        $_response_items['task_id']               = $id_task;
		$_response_items['scan_monitor_file']     = $task_files['scan_monitor_file'];
		$_response_items['scan_uri']              = $task_files['uri'];
		$_response_items['folder']                = $task_files['destination_folder'];
		$_response_items['scan_command']          = $_command_scan;
		$_response_items['scan_pid']              = $_scan_pid;
        $_response_items['probing_trace_file']    = $task_files['probing_trace_file'];
        
        
        
        
        sleep(2);
        
        
        $_json_status = file_get_contents($task_files['scan_monitor_file'], FILE_USE_INCLUDE_PATH);
        $status = json_encode($_json_status);
        
        while($_json_status == ''){
            $_json_status = file_get_contents($task_files['scan_monitor_file'], FILE_USE_INCLUDE_PATH);
            $status = json_encode($_json_status);   
        }
        
        
        sleep(1);
        
        header('Content-Type: application/json');
		echo json_encode($_response_items);
        
        
        
    }
    
	
	
	
	public function scan_pg($param){
			
	
		$mode = 15;
		
		
		$split_size = explode('-', $param['pg_size']);
		$width = $split_size[0];
		$height = $split_size[1];
		
		$pc_host_address = $param['pc_host_address'];
		$pc_host_port    = $param['pc_host_port'];
		 
		 
		/** LOAD DATABASE */
        $this->load->model('tasks');
        $this->load->model('scan_model');
        
        /** LOAD HELPERS */
        $this->load->helper('file');
		
		
		/**
		 * ADD TASK
		 */
        $_task_data['user']       = $_SESSION['user']['id'];
		$_task_data['controller'] = 'scan';
		$_task_data['type']       = 'scan';
		$_task_data['status']     = 'running';
		
		$id_task = $this->tasks->add_task($_task_data);
		
		shell_exec('sudo php '.SCRIPTPATH.'/notifications.php &');
		
		
		/**
		 * CREATE FOLDERS AND FILES
		*/
        $_time = time();
        $task_files = $this->crate_folders_files($id_task, $mode, $_time);
		
		
		
		
		/** LAUNCH SCAN COMMAND */
        $_command_scan = 'sudo python /var/www/fabui/python/pg_scan.py -s'.$param['pg_slices'].' -i'.$param['pg_iso'].' -l'.$task_files['scan_monitor_file'].' -d'.$task_files['destination_folder'].' -b0 -e360 -w'.$width.' -h'.$height.' -t'.$id_task.' -a'.$pc_host_address.' -p'.$pc_host_port.' 2> /var/www/temp/krios.log > /var/www/temp/kk.log  & echo $!'; 
        
        $_output_scan  = shell_exec ( $_command_scan );
		$_scan_pid     = intval(trim(str_replace('\n', '', $_output_scan)))+1;
		
		
		
		
		
		/**
		 *  DATA FOR UPDATING TASK
		*/
        $_attributes_items['new']                = $param['new_object'];
        $_attributes_items['id_obj']             = $param['obj_id'];
        $_attributes_items['obj_name']           = $param['obj_name'];
		$_attributes_items['scan_pid']           = $_scan_pid;
		$_attributes_items['folder']             = $task_files['destination_folder'];
		$_attributes_items['time']               = $_time;
		$_attributes_items['scan_monitor']       = $task_files['scan_monitor_file'];
		$_attributes_items['mode']               = 15; 
		$_attributes_items['mode_name']          = 'photogrammetry';
		$_attributes_items['slices']             = $param['pg_slices']; 
        $_attributes_items['iso']                = $param['pg_iso'];
		
		
		/** UPDATE TASK */
        $_data_update['attributes']= json_encode($_attributes_items);
        $this->tasks->update($id_task, $_data_update);
        
        /** DATA FOR RESPONSE */
        $_response_items['task_id']               = $id_task;
		$_response_items['scan_monitor_file']     = $task_files['scan_monitor_file'];
		$_response_items['scan_uri']              = $task_files['uri'];
		$_response_items['folder']                = $task_files['destination_folder'];
		$_response_items['scan_command']          = $_command_scan;
		$_response_items['scan_pid']              = $_scan_pid;
		
		
		
		sleep(2);
        
        
        $_json_status = file_get_contents($task_files['scan_monitor_file'], FILE_USE_INCLUDE_PATH);
        $status = json_encode($_json_status);
        
        while($_json_status == ''){
            $_json_status = file_get_contents($task_files['scan_monitor_file'], FILE_USE_INCLUDE_PATH);
            $status = json_encode($_json_status);   
        }
        
        
        sleep(1);
        
        header('Content-Type: application/json');
		echo json_encode($_response_items);
		
		
		
	}
	
	
	
	
	
	
    /**
     * CREATE FILES AND FOLDERS FOR THE TASK
     */
     function crate_folders_files($id_task, $mode, $_time){
        
        $list  = array();
        
       	$list['destination_folder'] = '/var/www/tasks/scan_'.$id_task.'_'.$_time.'/';
       	
		$list['scan_monitor_file']  = TEMPPATH.'task_monitor.json';
		
        $list['uri']                = '/tasks/scan_'.$id_task.'_'.$_time.'/';
		$list['uri_monitor']        = '/temp/task_monitor.json';
        
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
			case 15:
				
				$list['pg_images_folder'] = $list['destination_folder'].'images/';
				break;
	
        }
        
        
        
        /** CREAE FILES AND FOLDERS */
		mkdir($list['destination_folder'], 0777);
        /** create scan monitor file */
		write_file($list['scan_monitor_file'], '', 'w');
        
        
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
			case 15:
				/** images folder */
                mkdir($list['destination_folder'].'images/', 0777);
				break;
		}
        
        
        return $list;
        
        
     }


}

?>