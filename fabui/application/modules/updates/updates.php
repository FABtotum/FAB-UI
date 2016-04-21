<?php
/*
 * Update module
 */
class Updates extends Module {
	/* */
	public function index(){
		//load helper
		$this->load->helper('smart_admin_helper');
		$this->load->helper('update_helper');
		
		$data['internet_available'] = is_internet_avaiable();
		$data['remote_version'] = myfab_get_remote_version();
		
		if(!$data['internet_available'] || $data['remote_version'] == false){ // no connection or update server not reachable
			$this -> layout -> view('index/noconnection', $data);
			return;
		}
		//load model
		$this -> load -> model('tasks');
		$data['task'] = $this -> tasks -> get_running('updates'); //check if there's already an updated running process
		//get versions
		$_SESSION['fabui_version'] = $data['local_version']  = myfab_get_local_version();
		//$data['remote_version'] = 2;
		//$_SESSION['updates']['updated'] = $data['updated'] = version_compare($data['local_version'], $data['remote_version']) > -1;
		$_SESSION['updates']['updated'] = $data['updated'] = $data['local_version'] >= $data['remote_version'];
		$_SESSION['updates']['time'] = time();
		//layout
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('index/js', $data, TRUE), 'comment' => ''));
		$this -> layout -> add_css_in_page(array('data' => $this -> load -> view('index/css', $data, TRUE), 'comment' => ''));
		$this -> layout -> view('index/index', $data);
		
	}
	/* */
	public function aa(){
	}
	/* */
	public function uptodate(){
	}
	/* start the update process */
	public function doit(){
		//load helper
		$this->load->helper('update_helper');
		$this->load->helper('file_helper');
		//init data
		$data['local_version']  = myfab_get_local_version();
		$data['remote_version'] = myfab_get_remote_version();
		$data['updated'] = $data['local_version'] >= $data['remote_version'];
		
		//if($updated){
			//return;
		//}
		//load model
		$this->load->model('tasks');
		//crate data for record
		$_task_data['controller'] = 'updates';
		$_task_data['type']       = 'fabui';
		$_task_data['status']     = 'running';
		$_task_data['attributes'] = json_encode(array());
		$_task_data['user']       = $_SESSION['user']['id'];
		//add record to db
		$task_id = $this->tasks->add_task($_task_data);
		shell_exec('sudo echo '.$task_id.' > '.TEMPPATH.'/task.pid');
		//preaparing files and folders
		$_destination_folder = TASKSPATH.'update_fabui_'.$task_id.'_'.time().'/';
		$_debug_file         = $_destination_folder.'update_fabui_'.$task_id.'.debug';
		$_monitor_file       = TEMPPATH.'task_monitor.json';
		$_trace_file         = TEMPPATH.'task_trace';
		mkdir($_destination_folder, 0777);
		write_file($_monitor_file, '', 'w');
		write_file($_trace_file, '', 'w');
		//startin download script
		$_command          = 'php '.SCRIPTPATH.'download_install_update.php '.$data['remote_version'].' '.$task_id.' '.$_destination_folder.' '.$_monitor_file.' 2>'.$_debug_file.' > '.$_trace_file.' > /dev/null & echo $! > '.TEMPPATH.'/update.pid';
		$_response_command = shell_exec ( $_command);
		$_pid              = intval(trim(str_replace('\n', '', shell_exec('cat '.TEMPPATH.'/update.pid'))));
		//update task attributes
		$_attributes_items['pid']         =  $_pid;
		$_attributes_items['monitor']     =  $_monitor_file;
		$_attributes_items['uri_monitor'] =  '/temp/task_monitor.json';
		$_attributes_items['folder']      =  $_destination_folder;
		$_data_update['attributes']= json_encode($_attributes_items);
		$this->tasks->update($task_id, $_data_update);
		echo json_encode(array('command' => $_command));
	}
	/* check for updates */
	public function check($force = 0, $outputReturn = false){
		$time_to_check = (60 * 60) * 4; //every 4 hours
		$now = time();
		$check = false;
		$updates = isset($_SESSION["updates"]) ? $_SESSION["updates"] : array();
		if (!isset($_SESSION['updates']['time'])) $_SESSION['updates']['time'] = 0;
		if ((($now - $_SESSION['updates']['time']) > $time_to_check) || $force == 1) { // IF IS PASSED MORE THAN TIME TO CHECK, CHECK AGAIN IF THERE ARE UPDATES AVAILABLES
			//load helper
			$this->load->helper('update_helper');
			$updates = array();
			$updates['time'] = time();
			$data['local_version']  = myfab_get_local_version();
			$data['remote_version'] = myfab_get_remote_version();
			$updates['updated']     = $data['local_version'] >= $data['remote_version'];
			$_SESSION['updates']    = $updates;
			$check = true;			
		}
		$_response_items = array();
		$_response_items['updates'] = $updates;
		$_response_items['check'] = $check;
		if($outputReturn == false)$this->output->set_content_type('application/json')->set_output(json_encode($_response_items));
		else return $_response_items;	
	}
	/* cancel update */
	public function cancel(){
		if(!file_exists(TEMPPATH.'/task.pid') || !file_exists(TEMPPATH.'/update.pid')) exit('no task'); //it means there's no running task
		$pid = intval(trim(str_replace('\n', '', shell_exec('cat '.TEMPPATH.'/update.pid')))); //get update pid process
		shell_exec('sudo kill -9 '.$pid); //kill update process
		$task_id = intval(trim(str_replace('\n', '', shell_exec('cat '.TEMPPATH.'/task.pid')))); //get task id
		$this->load->model('tasks');
		$this->tasks->update($task_id, array('status' => 'canceled', 'finish_date' => 'now()')); //update task
		$task = $this->tasks->get_by_id($task_id);
		$_attributes = json_decode($task->attributes, TRUE);
		shell_exec('sudo rm -rf '.$_attributes['folder']); //remove temporary folders & files
		shell_exec('rm '.TEMPPATH.'/task.pid '.TEMPPATH.'/update.pid'); //remove pids files
		echo json_encode(array('response'=>true));
	}
	/* */
	public function notification(){
		$info = $this->check(1, true);
		if($info['updates']['updated'] == false){
			echo '<div class="padding-10">
			<div class="alert alert-danger alert-block animated fadeIn">
					
					<h4 class="alert-heading"> <i class="fa fa-refresh"></i> New important software updates are now available, <a style="text-decoration:underline; color:white;" href="/fabui/updates">update now!</a> 
					</h4>
				</div></div>';
		}else{
			
		}
	}
}