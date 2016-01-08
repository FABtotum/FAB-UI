<?php

class Settings extends Module {

	public function __construct() {
		parent::__construct();

		$this -> load -> helper('print_helper');
		/** IF PRINTER IS BUSY I CANT CHANGE SETTINGS  */
		if (is_printer_busy()) {
			redirect('dashboard');
		}

		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);

	}

	public function index() {

		$this -> load -> database();
		$this -> load -> model('configuration');

		$this -> config -> load('fabtotum', TRUE);

		$_units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);

		$data['_standby_color'] = $_units['color'];
		$data['_safety_door'] = isset($_units['safety']['door']) ? $_units['safety']['door'] : '1';
		$data['_switch'] = isset($_units['switch']) ? $_units['switch'] : '0';
		$data['_feeder_disengage'] = isset($_units['feeder']['disengage-offset']) ? $_units['feeder']['disengage-offset'] : 2;
		$data['_feeder_extruder_steps_per_unit_e_mode'] = isset($_units['e']) ? $_units['e'] : 3048.1593;
		$data['_feeder_extruder_steps_per_unit_a_mode'] = isset($_units['a']) ? $_units['a'] : 177.777778;
		$data['_both_y_endstops'] = isset($_units['bothy']) ? $_units['bothy'] : "None";
		$data['_both_z_endstops'] = isset($_units['bothz']) ? $_units['bothz'] : "None";
		$data['_upload_api_key'] = isset($_units['api']['keys'][$_SESSION['user']['id']]) ? $_units['api']['keys'][$_SESSION['user']['id']] : '';
		$data['_zprobe'] = isset($_units['zprobe']['disable']) ? $_units['zprobe']['disable'] : '0';
		$data['_zmax'] = isset($_units['zprobe']['zmax']) ? $_units['zprobe']['zmax'] : '206';
		$data['_milling_sacrificial_layer_offset'] = isset($_units['milling']['layer-offset']) ? $_units['milling']['layer-offset'] : 12.0;

		/** LOAD TAB HEADER */
		$_tab_header = $this -> tab_header();

		$data['_breadcrumb'] = 'General';
		$data['_tab_header'] = $_tab_header;
		$data['_tab_content'] = $this -> load -> view('index/general/index', $data, TRUE);

		/** LAYOUT */
		$js_in_page = $this -> load -> view('index/general/js', $data, TRUE);
		$css_in_page = $this -> load -> view('index/general/css', '', TRUE);

		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => 'settings js'));
		$this -> layout -> add_css_in_page(array('data' => $css_in_page, 'comment' => 'settings css'));

		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.all.min.js', 'comment' => 'javascript for the noUISlider'));
		$this -> layout -> add_css_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.min.css', 'comment' => 'javascript for the noUISlider'));

		$this -> layout -> view('index/index', $data);
	}

	public function create() {

		$this -> load -> database();
		$this -> load -> model('configuration');

		if ($this -> input -> post()) {
			foreach ($this->input->post() as $key => $value) {
				$this -> configuration -> save_confi_value($key, $value);
			}

		}

		$data['_start_gcode'] = $this -> configuration -> get_config_value('start_gcode');
		$data['_end_gcode'] = $this -> configuration -> get_config_value('end_gcode');
		$data['_slicer_presets'] = json_decode($this -> configuration -> get_config_value('slicer_presets'), TRUE);

		$_tab_header = $this -> tab_header('create');

		$data['_breadcrumb'] = 'Print';
		$data['_tab_header'] = $_tab_header;
		$data['_tab_content'] = $this -> load -> view('index/create/index', $data, TRUE);

		$js_in_page = $this -> load -> view('index/create/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT'));
		$this -> layout -> set_compress(false);
		$this -> layout -> view('index/index', $data);

	}

	public function scan() {

		$_tab_header = $this -> tab_header('scan');

		/** LOAD DATABASE */
		$this -> load -> model('scan_model');

		/** LOAD SCAN CONFIGURATIONS */
		$quality_list = $this -> scan_model -> get(array('type' => 'quality'));

		$data['_breadcrumb'] = 'Scan';
		$data['_tab_header'] = $_tab_header;
		$data['_tab_content'] = $this -> load -> view('index/scan/index', '', TRUE);

		$this -> layout -> view('index/index', $data);

	}

	public function hardware() {

		$this -> config -> load('fabtotum', TRUE);

		$data['_breadcrumb'] = 'Hardware';
		$_tab_header = $this -> tab_header('hardware');
		$data['_tab_header'] = $_tab_header;

		$config_units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);

		shell_exec('sudo chmod 0777 ' . CONFIG_FOLDER);

		if (!file_exists($this -> config -> item('fabtotum_custom_config_units', 'fabtotum'))) {
			$this -> load -> helper('file');
			write_file($this -> config -> item('fabtotum_custom_config_units', 'fabtotum'), json_encode($config_units), 'w');
		}

		$custom_config_units = json_decode(file_get_contents($this -> config -> item('fabtotum_custom_config_units', 'fabtotum')), TRUE);

		if (!isset($custom_config_units['custom_overrides'])) {
			$custom_config_units['custom_overrides'] = '/var/www/fabui/config/custom_overrides.txt';
		}

		if (!file_exists($custom_config_units['custom_overrides'])) {
			$this -> load -> helper('file');
			write_file('/var/www/fabui/config/custom_overrides.txt', '', 'w');
		}

		$data['settings_type'] = isset($config_units['settings_type']) ? $config_units['settings_type'] : 'default';
		$data['feeder_extruder_steps_per_unit_e_mode'] = isset($custom_config_units['e']) ? $custom_config_units['e'] : 3048.1593;
		$data['feeder_extruder_steps_per_unit_a_mode'] = isset($custom_config_units['a']) ? $custom_config_units['a'] : 177.777778;
		$data['show_feeder'] = isset($custom_config_units['feeder']['show']) ? $custom_config_units['feeder']['show'] : true;
		$data['custom_overrides'] = isset($custom_config_units['custom_overrides']) ? file_get_contents($custom_config_units['custom_overrides']) : '';
		$data['invert_x_endstop_logic'] = isset($custom_config_units['invert_x_endstop_logic']) ? $custom_config_units['invert_x_endstop_logic'] : false;

		$data['_tab_content'] = $this -> load -> view('index/hardware/index', $data, TRUE);

		$js_in_page = $this -> load -> view('index/hardware/js', $data, TRUE);
		$css_in_page = $this -> load -> view('index/hardware/css', '', TRUE);

		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));
		$this -> layout -> add_css_in_page(array('data' => $css_in_page, 'comment' => ''));

		$this -> layout -> set_compress(false);
		$this -> layout -> view('index/index', $data);

	}

	function network() {

		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/masked-input/jquery.maskedinput.min.js', 'comment' => ''));

		/** LOAD HELPERS */
		$this -> load -> helper("os_helper");

		$this -> load -> database();
		$this -> load -> model('configuration');

		$saved_wifi = $this -> configuration -> get_config_value('wifi');
		$saved_wifi = json_decode($saved_wifi, true);

		$networkConfiguration = networkConfiguration();

		$ethEndIp = explode('.', $networkConfiguration['eth']);
		$ethEndIp = end($ethEndIp);

		//current_wlan();
		$data['ethEndIp'] = $ethEndIp;
		$_tab_header = $this -> tab_header('network');
		$data['wifi_saved'] = $saved_wifi;
		$data['_breadcrumb'] = 'Network';
		$data['_tab_header'] = $_tab_header;
		$data['lan'] = lan();
		$data['con_wlan'] = wlan();
		$data['wlan'] = scan_wlan();
		$data['networkConfiguration'] = $networkConfiguration;

		$data['imOnCable'] = $_SERVER['SERVER_ADDR'] == $networkConfiguration['eth'] ? true : false;

		$data['_tab_content'] = $this -> load -> view('index/network/index', $data, TRUE);

		$js_in_page = $this -> load -> view('index/network/js', $data, TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		//$this->layout->set_compress(false);
		$this -> layout -> view('index/index', $data);
	}

	public function seteth() {

		$number = $this -> input -> post('number');
		/** LOAD HELPERS */
		$this -> load -> helper("os_helper");

		setEthIP($number);

		echo true;
	}

	public function setwifi() {

		$net = $this -> input -> post('net');
		$password = $this -> input -> post('password');
		$address = $this -> input -> post('address');
		/** LOAD HELPERS */
		$this -> load -> helper("os_helper");

		$wlans = scan_wlan();

		$type = '';

		foreach ($wlans as $wl) {
			if ($wl['address'] == $address) {
				$type = $wl['type'];
			}
		}

		if (setWifi($net, $password, $type)) {

			$wlan = wlan();
			$wlan_ip = isset($wlan['ip']) ? $wlan['ip'] : '';

			$this -> load -> database();
			$this -> load -> model('configuration');

			/** SAVE NEW WIFI CONFIGURATION TO DB */
			$this -> configuration -> save_confi_value('wifi', json_encode(array('ssid' => $net, 'password' => $password, 'ip' => $wlan_ip)));

			$response_items['wlan_ip'] = $wlan_ip;
			$response_items['response'] = 'OK';

		} else {
			$response_items['response'] = 'KO';
		}

		echo json_encode($response_items);

	}

	function jog() {

		$this -> load -> database();
		$this -> load -> model('configuration');

		if ($this -> input -> post()) {

			foreach ($this->input->post() as $key => $value) {
				$this -> configuration -> save_confi_value($key, $value);
			}

		}

		$_tab_header = $this -> tab_header('jog');

		$data['_unit'] = $this -> configuration -> get_config_value('unit');
		$data['_step'] = $this -> configuration -> get_config_value('step');
		$data['_feedrate'] = $this -> configuration -> get_config_value('feedrate');

		$data['_breadcrumb'] = 'Jog';
		$data['_tab_header'] = $_tab_header;
		$data['_tab_content'] = $this -> load -> view('index/jog/index', $data, TRUE);

		$this -> layout -> view('index/index', $data);

	}

	public function eeprom() {

		//$this->load->model('eeprom');
		//$configs = $this->eeprom->get_all();

		//$data['configs']      = $configs;
		$data['_tab_header'] = $this -> tab_header('eeprom');
		$data['_tab_content'] = $this -> load -> view('index/eeprom/index', $data, TRUE);

		$js_in_page = $this -> load -> view('index/eeprom/js', $data, TRUE);
		$css_in_page = $this -> load -> view('index/eeprom/css', '', TRUE);

		// == LAYOUT
		/*$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/datatables/jquery.dataTables.min.js', 'comment' => ''));
		 $this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/datatables/dataTables.colVis.min.js', 'comment' => ''));
		 $this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/datatables/dataTables.tableTools.min.js', 'comment' => ''));
		 $this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/datatables/dataTables.bootstrap.min.js', 'comment' => ''));
		 */

		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));
		$this -> layout -> add_css_in_page(array('data' => $css_in_page, 'comment' => ''));

		$this -> layout -> view('index/index', $data);

	}

	public function getEepromLine($lines, $key) {

		foreach ($lines as $line) {

			if (strpos($line, $key) !== false) {
				return $line;
			}

		}

	}

	public function raspicam() {
		
		$this -> load -> library('WidgetsFactory');
		
		$raspicam_widget = $this -> widgetsfactory -> load('cam');
		$data['raspicam_widget'] = $raspicam_widget->content();
		
		
		$data['_tab_header'] = $this -> tab_header('raspicam');
		$data['_tab_content'] = $this -> load -> view('index/raspicam/index', $data, TRUE);
		
		$this -> layout -> view('index/index', $data);

	}

	function tab_header($current = 'settings') {

		$_tabs[] = array('name' => 'settings', 'label' => 'General', 'url' => site_url('settings'), 'icon' => 'fa fa-lg fa-fw fa fa-list-ul');
		//$_tabs[] = array('name' => 'scan',        'label'=>'Scan',        'url' => site_url('settings/scan'),        'icon' => 'fab-lg fab-fw icon-fab-scan');
		//$_tabs[] = array('name' => 'create',      'label'=>'Print',       'url' => site_url('settings/create'),      'icon' => 'fab-lg fab-fw icon-fab-print');
		//$_tabs[] = array('name' => 'jog',         'label'=>'Jog',         'url' => site_url('settings/jog'),         'icon' => 'fab-lg fab-fw icon-fab-jog');
		//$_tabs[] = array('name' => 'plugin',    'label'=>'Plugin',   'url' => site_url('settings/plugin'),   'icon' => 'fab-lg fab-fw icon-fab-plugin');

		//$_tabs[] = array('name' => 'maintenance', 'label'=>'Maintenance', 'url' => site_url('settings/maintenance'), 'icon' => 'fa fa-lg fa-fw fa-wrench');
		$_tabs[] = array('name' => 'network', 'label' => 'Network', 'url' => site_url('settings/network'), 'icon' => 'fa fa-lg fa-fw fa-sitemap');
		$_tabs[] = array('name' => 'hardware', 'label' => 'Hardware', 'url' => site_url('settings/hardware'), 'icon' => 'fa fa-lg fa-fw fa-gear');
		$_tabs[] = array('name' => 'raspicam', 'label' => 'Raspi Cam', 'url' => site_url('settings/raspi-cam'), 'icon' => 'fa fa-lg fa-fw fa-camera');
		//$_tabs[] = array('name' => 'eeprom',      'label'=>'Firmware EEPROM Settings',      'url' => site_url('settings/eeprom'),      'icon' => 'fa fa-lg fa-fw fa-hdd-o');

		$data['_current'] = $current;
		$data['_tabs'] = $_tabs;

		return $this -> load -> view('index/tab_header', $data, TRUE);

	}

}
?>