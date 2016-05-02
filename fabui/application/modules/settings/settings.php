<?php

class Settings extends Module {
	
	public function index() {
		
		$this->general();
		
	}
	
	
	public function general(){
		
		$this -> load -> helper('smart_admin_helper');
		$this->load->helper('form');
		$this -> config -> load('fabtotum', TRUE);
		
		
		if(!file_exists($this -> config -> item('fabtotum_config_units', 'fabtotum'))){
			$this->load->helper('print_helper');
			create_default_config();
		}
		
		$config_units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);
		
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
		
		
		$data = array();
		
		$data['_standby_color'] = $config_units['color'];
		$data['_safety_door'] = isset($config_units['safety']['door']) ? $config_units['safety']['door'] : '1';
		$data['_collision_warning'] = isset($config_units['safety']['collision-warning']) ? $config_units['safety']['collision-warning'] : '1';
		$data['_switch'] = isset($config_units['switch']) ? $config_units['switch'] : '0';
		$data['_feeder_disengage'] = isset($config_units['feeder']['disengage-offset']) ? $config_units['feeder']['disengage-offset'] : 2;
		$data['_feeder_extruder_steps_per_unit_e_mode'] = isset($config_units['e']) ? $config_units['e'] : 3048.1593;
		$data['_feeder_extruder_steps_per_unit_a_mode'] = isset($config_units['a']) ? $config_units['a'] : 177.777778;
		$data['_both_y_endstops'] = isset($config_units['bothy']) ? $config_units['bothy'] : "None";
		$data['_both_z_endstops'] = isset($config_units['bothz']) ? $config_units['bothz'] : "None";
		$data['_upload_api_key'] = isset($config_units['api']['keys'][$_SESSION['user']['id']]) ? $config_units['api']['keys'][$_SESSION['user']['id']] : '';
		$data['_zprobe'] = isset($config_units['zprobe']['disable']) ? $config_units['zprobe']['disable'] : '0';
		$data['_zmax'] = isset($config_units['zprobe']['zmax']) ? $config_units['zprobe']['zmax'] : '206';
		$data['_milling_sacrificial_layer_offset'] = isset($config_units['milling']['layer-offset']) ? $config_units['milling']['layer-offset'] : 12.0;
		$data['_print_preheating_extruder'] = isset($config_units['print']['pre-heating']['extruder']) ? $config_units['print']['pre-heating']['extruder'] : 150;
		$data['_print_preheating_bed'] = isset($config_units['print']['pre-heating']['bed']) ? $config_units['print']['pre-heating']['bed'] : 50;
		$data['max_temp'] = $this -> layout -> get_max_temp();
		
		/***
		 * HARDWARE
		 * 
		 */
		$data['invert_x_endstop_logic'] = isset($custom_config_units['invert_x_endstop_logic']) ? $custom_config_units['invert_x_endstop_logic'] : false;
		$data['custom_overrides'] = isset($custom_config_units['custom_overrides']) ? file_get_contents($custom_config_units['custom_overrides']) : '';
		$data['hw_feeder_extruder_steps_per_unit_e_mode'] = isset($custom_config_units['e']) ? $custom_config_units['e'] : 3048.1593;
		$data['hw_feeder_extruder_steps_per_unit_a_mode'] = isset($custom_config_units['a']) ? $custom_config_units['a'] : 177.777778;
		$data['settings_type'] = isset($config_units['settings_type']) ? $config_units['settings_type'] : 'default';
		
		$data['options_customized_actions'] = array('None'=>'None', 'Shutdown'=>'Shutdown');
		
		$data['show_feeder'] = $this -> layout -> getFeeder();
		
		
		// == LAYOUT
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('general/js', $data, TRUE), 'comment' => 'settings js'));
		$this -> layout -> add_css_in_page(array('data' => $this -> load -> view('general/css', $data, TRUE), 'comment' => 'settings css'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.all.min.js', 'comment' => 'javascript for the noUISlider'));
		$this -> layout -> add_css_file(array('src' => '/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.min.css', 'comment' => 'javascript for the noUISlider'));
		
		
		$data['widget'] = $this -> load -> view('general/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-cog';
		$attr['data-widget-fullscreenbutton'] = 'false';
		
		$toolbar = $this -> load -> view('general/widget_toolbar', $data, TRUE);
		
		$data['widget'] = widget('general' . time(), 'Hardware', $attr, $data['widget'], false, true, false, $toolbar);
		
		
		
		
		$this -> layout -> view('general/index', $data);
		
	}

	public function hardware(){
		
		$this -> config -> load('fabtotum', TRUE);
		$this -> load -> helper('smart_admin_helper');
		
		$data = array();
		
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
		
		
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('hardware/js', $data, TRUE), 'comment' => ''));
		$this -> layout -> add_css_in_page(array('data' => $this -> load -> view('hardware/css', '', TRUE), 'comment' => ''));
		
		
		$data['widget'] = $this -> load -> view('hardware/widget', $data, TRUE);
		
		$attr['data-widget-icon'] = 'fa fa-cog';
		$data['widget'] = widget('hardware' . time(), 'Hardware', $attr, $data['widget'], false, true, false);
		
		$this -> layout -> view('hardware/index', $data);
		
		//return $this->load->view('hardware/index', $data, true);
			
	}


	public function raspicam() {
		
		$this -> load -> library('WidgetsFactory');
		$raspicam_widget = $this -> widgetsfactory -> load('cam');
		$data['raspicam_widget'] = $raspicam_widget->content();
		
		$this -> layout -> view('raspicam/index', $data);
	}

	
	public function wlan(){
				
		$this->load->helper('os_helper');
		$this -> load -> helper('smart_admin_helper');

		if($this->input->post()){

			$essid = $this->input->post('essid');
			$response = $this->input->post('response');
			$action = $this->input->post('action');
			$post = $this->input->post();
			
			
			if($response == 'true'){
				if($action == 'connect') $data['message'] = array('type' => 'alert-success', 'text' => '<h4 class="alert-heading"><i class="fa fa-check"></i> Great!</h4>Network connection established successfully');
				if($action == 'disconnect') $data['message'] = array('type' => 'alert-success', 'text' => '<h4 class="alert-heading"><i class="fa fa-check"></i> Great!</h4> Disconnection performed successfully');
			}else{
				if($action == 'connect') $data['message'] = array('type' => 'alert-danger', 'text' => '<h4 class="alert-heading"><i class="fa fa-warning"></i> Error!</h4> Unable to connect to <strong>'.$essid.'</strong> Please check the password and try again');
				if($action == 'disconnect') $data['message'] = array('type' => 'alert-danger', 'text' => '<h4 class="alert-heading"><i class="fa fa-warning"></i> Error!</h4> Unable to disconnect from <strong>'.$essid.'</strong> Please try again');
			}			
		} 
		
		$data['info'] = wlan_info(); 
		$data['networks'] = scan_wlan();
		$data['widget'] = $this -> load -> view('network/wlan/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-wifi';
		$attr['data-widget-fullscreenbutton'] = 'false';

		$button = '
			<div class="widget-toolbar">
				<button data-action="down" class="btn show-details"> Details <i class="fa fa-chevron-down"></i></button>
			</div>';
		if($data['info']['ip_address'] == '') $button = '';
		
		$widget_label = $data['info']['ip_address'] != '' ? ' - '.$data['info']['ip_address'] : '';
		$data['widget'] = widget('network_wifi' . time(), 'Wifi'.$widget_label, $attr, $data['widget'], false, true, false, $button);
		
		$this -> layout -> add_css_file(array('src' => '/assets/css/line-icons-pro/styles.css'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js', 'comment' => ''));
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('network/wlan/js', $data, TRUE), 'comment' => ''));
		
		//$this -> layout -> set_compress(false);
		
		$this->layout->view('network/wlan/index', $data);
		
	}

	public function eth(){
		
		$this->load->helper('os_helper');
		$this -> load -> helper('smart_admin_helper');	
		$data['info'] = eth_info();
		
		$data['widget'] = $this -> load -> view('network/eth/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-sitemap';
		$attr['data-widget-fullscreenbutton'] = 'false';
		$data['widget'] = widget('network_eth' . time(), 'Network - Ethernet', $attr, $data['widget'], false, false, false);
		
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/inputmask/jquery.inputmask.bundle.js', 'comment' => ''));
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('network/eth/js', $data, TRUE), 'comment' => ''));
		
		$this->layout->view('network/eth/index', $data);
		
	}
	
	
	public function dns(){
			
		if($this->input->post()){
			
			$response = $this->input->post('response');
			
			if($response == 'ok'){				
				$data['message'] = array('type' => 'alert-success', 'text' => '<h4 class="alert-heading"><i class="fa fa-check"></i> Great!</h4>New hostname correctly configured ');
			}else{
				$data['message'] = array('type' => 'alert-danger', 'text' => '<h4 class="alert-heading"><i class="fa fa-warning"></i> Error!</h4> Unable to configure the hostname</strong>. Please try again');
			}
			
		}	

		$this->load->helper('os_helper');
		$this -> load -> helper('smart_admin_helper');
		
		
		$data['current_hostname'] = shell_exec('sudo hostname');
		$data['current_name'] = avahi_service_name();
		
		$data['widget'] = $this -> load -> view('network/dns/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-binoculars';
		$attr['data-widget-fullscreenbutton'] = 'false';
		$data['widget'] = widget('network_hostname' . time(), 'Make the FABtotum Personal Fabricator easily discoverable on local network', $attr, $data['widget'], false, true, false);
		
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('network/dns/js', $data, TRUE), 'comment' => ''));
		
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/jquery-validate/jquery.validate.min.js', 'comment' => 'jquery validate'));
		
		$this->layout->view('network/dns/index', $data);
		
	}
	
	
}



