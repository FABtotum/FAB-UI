<?php

class Plugin extends Module {

	public function __construct() {
		parent::__construct();
		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);

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

		redirect('plugin');

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

		redirect('plugin');

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
			redirect('plugin');

		}

	}

	public function upload() {

		$data = array();

		if (isset($_FILES['plugin-file'])) {

			$config['upload_path'] = '../temp/';
			$config['allowed_types'] = 'zip';

			$this -> load -> library('upload', $config);

			if (!$this -> upload -> do_upload('plugin-file')) {
						
				$data['error'] = strip_tags($this -> upload -> display_errors());
				
			} else { // if uploaded with success
	
				$file_data = array('upload_data' => $this -> upload -> data());
				
				$zip = new ZipArchive;

				$file = $file_data['upload_data']['full_path'];

				chmod($file, 0777);
				
				// unzip uploaded file
				if ($zip -> open($file) === TRUE) {
						
					$zip -> extractTo(TEMPPATH . $file_data['upload_data']['raw_name']);
					$zip -> close();

					$temp_plugin_path = TEMPPATH . $file_data['upload_data']['raw_name'] . '/' . $file_data['upload_data']['raw_name'];
					
					if (file_exists($temp_plugin_path) && file_exists($temp_plugin_path.'/'.$file_data['upload_data']['raw_name'].'.php')) {
						
						// if the structure of the plugin is valid
						
						$_command_copy = 'sudo cp -rvf ' . TEMPPATH . $file_data['upload_data']['raw_name'] . '/' . $file_data['upload_data']['raw_name'] . ' ' . PLUGINSPATH;
						shell_exec($_command_copy);
						
						$data['installed'] = true;
						$data['file_name'] = $file_data['upload_data']['file_name'];

					}else{
						$data['error'] = "Invalid plugin, plaese upload a valid plugin";
					}
					
					shell_exec('sudo rm -rf ' . TEMPPATH . $file_data['upload_data']['raw_name']);
					

				} else {
					$data['error'] = "Error unzipping file";
				}
				
				shell_exec('sudo rm -rf ' . $file);

			}

		}

		$js_in_page = $this -> load -> view('upload/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> view('upload/index', $data);

	}

}
?>