<?php

class Controller extends Module {

	public function __construct() {
		parent::__construct();

	}

	public function index() {

	}

	public function updates() {

		$this -> load -> helper('update_helper');
		$_update_list = myfab_update_list();

		if (count($_update_list) > 0) {
			$data['update_list'] = $_update_list;

			echo $this -> load -> view('update', $data, TRUE);

		}

	}

	public function tasks() {

		echo $this -> load -> view('tasks', '', TRUE);

	}

	public function notifications() {

		echo $this -> load -> view('notifications', '', TRUE);

	}

	public function language() {

		if ($this -> input -> post()) {

			$language = $this -> input -> post('lang');
			$back_url = $this -> input -> post('back_url');

			$this -> load -> database();
			$this -> load -> model('configuration');

			$languages = json_decode($this -> configuration -> get_config_value('languages'), TRUE);

			$this -> configuration -> save_confi_value('language', $language);

			$_SESSION['language'] = $languages[$language];

			redirect($back_url);

		}

	}

	public function suggestion() {

		if ($this -> input -> post()) {

			$this -> config -> load('fabtotum', TRUE);

			$url = $this -> config -> item('fabtotum_suggestions_url', 'fabtotum');
			$text = $this -> input -> post('text');
			$title = $this->input->post('title');

			$fields = array();

			$fields_string = "";

			$fields['email'] = $_SESSION['user']['email'];
			$fields['first_name'] = $_SESSION['user']['first_name'];
			$fields['last_name'] = $_SESSION['user']['last_name'];
			$fields['text'] = $text;
			$fields['title'] = $title;

			$fields_string = '';
			
			foreach ($fields as $key => $value) {
				
				 $fields_string .= $key . '=' . $value . '&';
			}
			
			rtrim($fields_string, '&');

		
			$ch = curl_init();

			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			
			$_json['result'] = $response;
			
			echo json_encode($_json);
			
			
		}

	}
	
	
	
	
	
	public function bug() {

		if ($this -> input -> post()) {

			$this -> config -> load('fabtotum', TRUE);

			$url = $this -> config -> item('fabtotum_bugs_url', 'fabtotum');
			$text = $this -> input -> post('text');
			$title = $this->input->post('title');

			$fields = array();

			$fields_string = "";

			$fields['email'] = $_SESSION['user']['email'];
			$fields['first_name'] = $_SESSION['user']['first_name'];
			$fields['last_name'] = $_SESSION['user']['last_name'];
			$fields['text'] = $text;
			$fields['title'] = $title;

			$fields_string = '';
			
			foreach ($fields as $key => $value) {
				
				 $fields_string .= $key . '=' . $value . '&';
			}
			
			rtrim($fields_string, '&');
		
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			
			$_json['result'] = $response;
			
			echo json_encode($_json);
			
			
		}

	}



	public function wizard(){
		
		$set = $this->input->post('set');
		
		$set = $set == 0 ? false : true;
		
		$_SESSION['ask_wizard'] = $set;
		
		
		echo true;
		
	}


	/**
	 * STOP ALL 
	 */
	public function stop_all(){
		
		$this -> load -> helper('os_helper');
		
		
		$macros_pids = get_pids('fabui/python/gmacro.py');
		
		$create_pids = get_pids('fabui/python/gpusher_fast.py');
		
		$selftest_pids = get_pids('python/self_test.py');
		
		$bed_cal_pids = get_pids('python/manual_bed_lev.py');
		
		$all_pids = array_merge($macros_pids, $create_pids, $selftest_pids, $bed_cal_pids);
			
		kill_process($all_pids);
		
		$_command = 'sudo python '.PYTHONPATH.'force_reset.py';
		shell_exec($_command);
		
		sleep(3);
		
		echo 1;
		
		
		
	}


}
?>