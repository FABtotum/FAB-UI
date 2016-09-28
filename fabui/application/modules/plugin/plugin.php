<?php

class Plugin extends Module {

	public function __construct() {
		parent::__construct();
		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);
		$this->load->helper('print_helper');
		if(is_printer_busy()){
            redirect('dashboard');
            
        }

	}

	public function index() {


		$this -> load -> helper('ft_plugin_helper');

		$_installed_plugins = installed_plugins();

		$_data['installed_plugins'] = $_installed_plugins;

		$js_data = array();

		$message = $this -> session -> flashdata('message');
		$message_type = $this -> session -> flashdata('message_type');

		if ($message != '') {
			$js_data['message'] = $message;
			$js_data['message_type'] = $message_type;
		}

		$js_in_page = $this -> load -> view('index/js', $js_data, TRUE);

		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));
		$this -> layout -> view('index/index', $_data);
	}

	public function add() {

		$this -> load -> helper('update_helper');

		$data['_internet'] = is_internet_avaiable();

		if ($data['_internet'] == true) {

			$css_in_page = $this -> load -> view('add/css', '', TRUE);
			$this -> layout -> add_css_in_page(array('data' => $css_in_page, 'comment' => 'add css'));

			$this -> load -> database();
			$this -> load -> model('configuration');

			//echo $this->configuration->get_config_value('plugin_respository');

			/** LOAD SAVED REPOSITORY */
			$_repository = json_decode($this -> configuration -> get_config_value('plugin_respository'), TRUE);

			$_plugins = array();

			/** LOAD PLUGIN LIST */
			foreach ($_repository as $_repo) {

				//echo $_repo['url'].PHP_EOL;
				$ch = curl_init($_repo['url']);

				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$result = curl_exec($ch);
				$info = curl_getinfo($ch);
				curl_close($ch);

				if ($info['http_code'] == 200) {
					$_temp = $_repo;
					$_temp['plugins'] = json_decode($result, true);
					$_plugins[] = $_temp;
				}
			}

			$data['_plugins'] = $_plugins;

		}

		$this -> layout -> view('add/index', $data);

	}

	public function activate($plugin) {

		$this -> load -> database();
		$this -> load -> model('plugins');

		$this -> plugins -> active($plugin);

		$_SESSION['plugins'] = $this -> plugins -> get_activeted_plugins();
		
		include PLUGINSPATH . $plugin . '/' . $plugin . '.php';
		
		$pluginClassName = ucfirst($plugin);
		
		$reflection = new ReflectionClass($pluginClassName);
		
		// check if activate method exists for plugin class
		if ($reflection -> getMethod('activate') -> class == $pluginClassName) {
			redirect('plugin/' . $plugin . '/activate');
		}

		redirect('plugin/index');

	}

	public function deactivate($plugin) {

		$this -> load -> database();
		$this -> load -> model('plugins');

		$this -> plugins -> deactive($plugin);

		$_SESSION['plugins'] = $this -> plugins -> get_activeted_plugins();
		
		include PLUGINSPATH . $plugin . '/' . $plugin . '.php';
		
		$pluginClassName = ucfirst($plugin);
		
		$reflection = new ReflectionClass($pluginClassName);
		
		// check if deactivate method exists for plugin class
		if ($reflection -> getMethod('deactivate') -> class == $pluginClassName) {
			redirect('plugin/' . $plugin . '/deactivate');
		}

		redirect('plugin/index');

	}

	public function remove($plugin) {

		// check if plugin exists

		if (file_exists(PLUGINSPATH . $plugin . '/' . $plugin . '.php')) {

			include PLUGINSPATH . $plugin . '/' . $plugin . '.php';

			$pluginClassName = ucfirst($plugin);

			$reflection = new ReflectionClass($pluginClassName);

			// check if exists remove method for plugin class
			if ($reflection -> getMethod('remove') -> class == $pluginClassName) {
				redirect('plugin/' . $plugin . '/remove');
			} else {
				// if not exists use plugin module remove method

				/** remove files */
				shell_exec('sudo rm -rf ' . PLUGINSPATH . $plugin);

				/** SET MESSAGE TO DISPLAY */
				$this -> session -> set_flashdata('message', "Plugin <strong>" . $pluginClassName . "</strong> has been <strong>deleted</strong>.");
				$this -> session -> set_flashdata('message_type', 'info');

				/** REDIRECT TO PLUGINS PAGE */
				redirect('plugin');

			}

		} else {
			$this -> session -> set_flashdata('message', "Plugin <strong>" . $plugin . "</strong> doesn't exists");
			$this -> session -> set_flashdata('message_type', 'warning');
			redirect('plugin/index');

		}

	}

	/**
	 * 
	 */
	public function upload()
	{
		$data = array();
		if(isset($_FILES['plugin-file'])){ //if is uploading
			
			shell_exec('sudo chmod 777 '.PLUGINSPATH);
			$upload_config['upload_path']   = TEMPPATH;
			$upload_config['allowed_types'] = 'zip';
			//load upload library
			$this->load->library('upload', $upload_config);
			if($this->upload->do_upload('plugin-file')){ //do upload
				$github = false;
				$upload_data = $this->upload->data();
				$zip = new ZipArchive; //init zip class
				chmod($upload_data['full_path'], 0777);
				//check if is a master file from github
				if(strpos($upload_data['orig_name'], '-master') !== false) {
					$github = true;
					//rename file
					shell_exec('sudo mv '.$upload_data['full_path'].' '.str_replace('-master', '', $upload_data['full_path']));
					//update values
					$upload_data['file_name']   = str_replace('-master', '', $upload_data['file_name']);
					$upload_data['full_path']   = str_replace('-master', '', $upload_data['full_path']);
					$upload_data['raw_name']    = str_replace('-master', '', $upload_data['raw_name']);
					$upload_data['client_name'] = str_replace('-master', '', $upload_data['client_name']);
				}
				//unzip folder
				shell_exec('sudo unzip '.$upload_data['full_path'].' -d '.$upload_data['file_path'].$upload_data['raw_name']);
				if($github){ //if is a file from github need to move all files from *-master folder
					shell_exec('sudo mv '.$upload_data['file_path'].$upload_data['raw_name'].'/'.$upload_data['raw_name'].'-master/* '.$upload_data['file_path'].$upload_data['raw_name'].'/');
					shell_exec('sudo rm -vrf '.$upload_data['file_path'].$upload_data['raw_name'].'/'.$upload_data['raw_name'].'-master/');
				}else{
					shell_exec('sudo mv '.$upload_data['file_path'].$upload_data['raw_name'].'/'.$upload_data['raw_name'].'/* '.$upload_data['file_path'].$upload_data['raw_name'].'/');
					shell_exec('sudo rm -rvf '.$upload_data['file_path'].$upload_data['raw_name'].'/'.$upload_data['raw_name']);
				}
				//copy to plugin folder
				if(file_exists($upload_data['file_path'].$upload_data['raw_name'].'/'.$upload_data['raw_name'].'.php') && !file_exists(PLUGINSPATH.$upload_data['raw_name'])){
					shell_exec('sudo mv '.$upload_data['file_path'].$upload_data['raw_name'].' '.PLUGINSPATH.$upload_data['raw_name']);
					$data['installed'] = true;
					$data['file_name'] = $upload_data['file_name'];
				}else{
					$data['error'] = "Invalid plugin, plaese upload a valid plugin";
				}
				shell_exec('sudo rm -rvf '.$upload_data['full_path']);
				shell_exec('sudo rm -rvf '.$upload_data['file_path'].$upload_data['raw_name']);
			}else{
				$data['error'] = strip_tags($this->upload->display_errors());
			}
		}
		//ouput
		$this->layout->add_js_in_page(array('data' => $this->load->view('upload/js', '', TRUE), 'comment' => ''));
		$this->layout->view('upload/index', $data);
	}
}
?>