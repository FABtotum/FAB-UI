<?php
class Maintenance extends Module {

	public function __construct() {
		parent::__construct();

		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);

		$this -> load -> helper('print_helper');
		if (is_printer_busy()) {
			redirect('dashboard');
		}

	}

	public function index() {

	}

	public function spool() {

		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT'));

		$js_in_page = $this -> load -> view('spool/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> view('spool/index', '');

	}

	public function feeder() {

		$js_in_page = $this -> load -> view('feeder/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> view('feeder/index', '');

	}

	public function fourthaxis() {

		$js_in_page = $this -> load -> view('fourthaxis/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> view('fourthaxis/index', '');
	}

	public function selftest() {

		/** INIT DB & MODELS */
		$this -> load -> database();
		$this -> load -> model('tasks');

		/**
		 * LOAD HELPERS
		 */
		$this -> load -> helper('os_helper');

		$_task = $this -> tasks -> get_running('maintenance', 'self_test');

		$_running = $_task ? true : false;

		if ($_running) {

			/** GET TASK ATTRIBUTES */
			$_attributes = json_decode($_task['attributes'], TRUE);

			if (exist_process($_attributes['pid'])) {

				$data['monitor_file'] = $_attributes['uri_monitor'];
				$data['trace_file'] = $_attributes['uri_trace'];
				$data['trace_content'] = file_get_contents($_attributes['trace']);
				$this -> layout -> set_compress(false);

			} else {

				$_running = false;
				$this -> tasks -> delete($_task['id']);

			}
		}

		$data['running'] = $_running;

		$js_in_page = $this -> load -> view('selftest/js', $data, TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT'));

		$this -> layout -> view('selftest/index', $data);
	}

	public function bedcalibration() {

		$js_in_page = $this -> load -> view('bedcalibration/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> view('bedcalibration/index', '');
	}

	public function probecalibration() {

		$js_in_page = $this -> load -> view('probecalibration/js', '', TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> view('probecalibration/index', '');
	}

	public function firstsetup() {

		$this -> config -> load('fabtotum', TRUE);
		$this -> load -> helper('form');
		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/fuelux/wizard/wizard.min.js', 'comment' => ''));

		$heads_options = array_merge(array('head_shape' => '---'), $this -> config -> item('heads_list', 'fabtotum'));
		$data['show_feeder'] = $this -> layout -> getFeeder();

		$data['step1'] = $this -> load -> view('firstsetup/step1/index', '', TRUE);
		$dataStep2['heads_options'] = $heads_options;
		$data['step2'] = $this -> load -> view('firstsetup/step2/index', $dataStep2, TRUE);
		$data['step3'] = $this -> load -> view('firstsetup/step3/index', '', TRUE);
		$data['step4'] = $this -> load -> view('firstsetup/step4/index', '', TRUE);
		$data['step5'] = $this -> load -> view('firstsetup/step5/index', '', TRUE);
		$data['step6'] = $this -> load -> view('firstsetup/step6/index', '', TRUE);

		$js_in_page = $this -> load -> view('firstsetup/js', $data, TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> set_setup_wizard(FALSE);

		$this -> layout -> view('firstsetup/index', '');
	}

	public function head() {

		$this -> config -> load('fabtotum', TRUE);
		$this -> load -> helper('form');
		$this -> load -> helper('smart_admin_helper');

		$_units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);

		if (isset($_units['settings_type']) && $_units['settings_type'] == 'custom') {
			$_units = json_decode(file_get_contents($this -> config -> item('fabtotum_custom_config_units', 'fabtotum')), TRUE);
		}

		$data['units'] = $_units;

		$data['heads_list'] = array_merge(array('head_shape' => '---'), $this -> config -> item('heads_list', 'fabtotum'), array('more_heads' => 'Get more heads'));
		$data['heads_descriptions'] = $this -> config -> item('heads_descriptions', 'fabtotum');

		$data['head'] = isset($_units['hardware']['head']['type']) ? $_units['hardware']['head']['type'] : 'head_shape';

		$widget_config['data-widget-icon'] = 'fa fa-toggle-down';

		$widget = widget('heads' . time(), 'Heads', $widget_config, $this -> load -> view('head/widget', $data, TRUE), false, true, false);

		$data['widget'] = $widget;

		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('head/js', $data, TRUE), 'comment' => ''));

		$this -> layout -> view('head/index', $data);
	}

	public function systeminfo() {
		
		$this->layout->set_printer_busy(true);

		$this -> load -> helper('os_helper');
		$this -> load -> helper('ft_date_helper');
		$this -> load -> helper('smart_admin_helper');
		$this -> load -> helper('update_helper');
		$this -> load -> helper('serial_helper');

		// ==== MEMORY
		$output_memory = shell_exec('cat /proc/meminfo');
		$table_rows    = preg_split('/$\R?^/m', $output_memory);
		$row_mem_total = $table_rows[0];
		$row_mem_free  = $table_rows[1];
		$data['mem_total'] = explode(' ', $row_mem_total);
		$data['mem_total'] = $data['mem_total'][count($data['mem_total']) - 2];
		$data['mem_free']  = explode(' ', $row_mem_free);
		$data['mem_free']  = $data['mem_free'][count($data['mem_free']) - 2];
		$data['mem_used_percentage'] = floor((($data['mem_total'] - $data['mem_free']) / $data['mem_total']) * 100);

		// === BOARD TEMPERATURE
		$output       = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
		$data['temp'] = intval($output) / 1000;
		// === BOARD TIME ALIVE
		$output             = shell_exec('echo "$(</proc/uptime awk \'{print $1}\')"');
		$data['time_alive'] = seconds_to_time(intval($output));
		
		// === NETWORK
		$output        = shell_exec('sh /var/www/fabui/script/transfer_rate.sh');
        $data['rates'] = explode(' ', $output);
		
		
		// == STORAGE
		$output               = shell_exec('df -H');
		$data['table_rows']   = preg_split('/$\R?^/m', $output);
		$data['table_header'] = explode(' ', $data['table_rows'][0]);
		$data['table_rows']   = array_splice($data['table_rows'], 1);
		$data['table_header'] = array_splice($data['table_header'], 0, count($data['table_header']) - 1);

		$widget_content = $this -> load -> view('systeminfo/widget', $data, TRUE);
		
		
		$data['widget'] = widget('systeminfo' . time(), 'System Info', null, $widget_content, true, false, true);
		
		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js', 'comment' => ''));
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('systeminfo/js', $data, TRUE), 'comment' => ''));
		$this -> layout -> view('systeminfo/index', $data);

		
		
	}

}
?>