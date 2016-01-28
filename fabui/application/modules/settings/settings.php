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
		
		
		$_units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);
		
		$data = array();
		
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
		
		$data['options_customized_actions'] = array('None'=>'None', 'Shutdown'=>'Shutdown');
		
		$data['show_feeder'] = $this -> layout -> getFeeder();
		
		
		// == LAYOUT
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('general/js', $data, TRUE), 'comment' => 'settings js'));
		$this -> layout -> add_css_in_page(array('data' => $this -> load -> view('general/css', $data, TRUE), 'comment' => 'settings css'));
		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.all.min.js', 'comment' => 'javascript for the noUISlider'));
		$this -> layout -> add_css_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.min.css', 'comment' => 'javascript for the noUISlider'));
		
		
		$data['widget'] = $this -> load -> view('general/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-list';
		$attr['data-widget-fullscreenbutton'] = 'false';
		
		$toolbar = $this -> load -> view('general/widget_toolbar', $data, TRUE);
		
		$data['widget'] = widget('general' . time(), 'General', $attr, $data['widget'], false, true, false, $toolbar);
		
		
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
			
			if($response == 'true'){				
				$data['message'] = array('type' => 'alert-success', 'text' => '<h4 class="alert-heading"><i class="fa fa-check"></i> Great!</h4>Network connection established successfully');
			}else{
				$data['message'] = array('type' => 'alert-danger', 'text' => '<h4 class="alert-heading"><i class="fa fa-warning"></i> Error!</h4> Unable to connect to <strong>'.$essid.'</strong> Please check the password and try again');
			}			
		} 
		
		$data['info'] = wlan_info();
		$data['widget'] = $this -> load -> view('network/wlan/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-wifi';
		$attr['data-widget-fullscreenbutton'] = 'false';
		
		$switch = '<div class="widget-toolbar" id="switch-1">
				<span class="onoffswitch-title">Enable</span>
				<span class="onoffswitch">
					<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="wifi-switch">
					<label class="onoffswitch-label" for="wifi-switch"> 
						<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
						<span class="onoffswitch-switch"></span> </label> 
					</span>
				</div>';
		
		$data['widget'] = widget('network_wifi' . time(), 'Network - Wifi', $attr, $data['widget'], false, false, false);
		
		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js', 'comment' => ''));
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
		$data['widget'] = widget('network_eth' . time(), 'Network - Ethernet', $attr, $data['widget'], false, false, false);
		
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('network/eth/js', $data, TRUE), 'comment' => ''));
		
		$this->layout->view('network/eth/index', $data);
		
	}
	
	
}



