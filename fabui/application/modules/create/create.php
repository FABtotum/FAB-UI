<?php
/***
 * Print Module
 *
 *
 *
 *
 */
class Create extends Module {
	
	
	protected $_PRINT_VALID_HEADS = array('hybrid', 'print_v2');
	protected $_MILL_VALID_HEADS  = array('hybrid', 'mill_v2');
	protected $_MAKE_TYPES        = array('additive' => 'print', 'subtractive' => 'mill');

	public function __construct() {
		parent::__construct();

		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);
	}

	public function index($type = 'additive') {

		/** INIT DB & MODELS */
		$this -> load -> database();
		$this -> load -> model('objects');
		$this -> load -> model('tasks');

		/**
		 * LOAD HELPERS
		 */
		$this -> load -> helper('ft_date_helper');
		$this -> load -> helper('smart_admin_helper');
		$this -> load -> helper('os_helper');
		$this -> load -> helper('print_helper');

		/** LOAD REQUEST PARAMETER */
		$_request_obj = $this -> input -> get('obj');
		$_request_file = $this -> input -> get('file');

		/**
		 * check if printer is already printing
		 */
		if ($type == 'subtractive') {
			
			$data['valid_head'] = in_array(get_head(), $this->_MILL_VALID_HEADS);
			$_task = $this -> tasks -> get_running('make', 'mill');
			
		} else {
			$data['valid_head'] = in_array(get_head(), $this->_PRINT_VALID_HEADS);
			$_task = $this -> tasks -> get_running('make', 'print');
		}

		$_running = $_task ? true : false;

		if ($_running) {

			/** GET TASK ATTRIBUTES */
			$_attributes = json_decode(file_get_contents($_task['attributes']), TRUE);

			/** CHECK IF PID IS STILL ALIVE */
			if (exist_process($_attributes['pid'])) {

				$this -> load -> model('files');

				$_object = $this -> objects -> get_obj_by_id($_attributes['id_object']);
				$_file = $this -> files -> get_file_by_id($_attributes['id_file']);

				if (isset($_attributes['pid']) && $_attributes['pid'] != '') {

					if (isset($_attributes['monitor']) && $_attributes['monitor'] != '') {

						$_monitor = file_get_contents($_attributes['monitor']);
						$_monitor_encoded = json_decode($_monitor);
						$_stats = json_decode(file_get_contents($_attributes['stats']), TRUE);

					}

				} else {
					$this -> tasks -> delete($_task['id']);
					$_running = FALSE;
				}

			} else {
				/** PROCESS IS DEAD */
				$_running = false;
				$this -> tasks -> delete($_task['id']);

			}

		}

		$this -> config -> load('fabtotum', TRUE);
		$units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);

		if (isset($units['settings_type']) && $units['settings_type'] == 'custom') {
			$units = json_decode(file_get_contents($this -> config -> item('fabtotum_custom_config_units', 'fabtotum')), TRUE);
		}

		$data['max_temp'] = $this -> layout -> get_max_temp();
		$data['label'] = $type == 'subtractive' ? 'Mill' : 'Print';
		
		

		/**
		 *  IMPOSTAZIONI STEP1
		 */
		$data_step1['objects'] = !$_running ? $this -> objects -> get_for_create($type) : array();
		$data_step1['type'] = $type;
		$data_step1['last_creations'] = !$_running ? $this->tasks->get_last_creations($this->_MAKE_TYPES[$type]) : array();
		$data_step1['status_label'] = array('performed' => '<span class="label label-success">COMPLETED</span>', 'stopped' => '<span class="label label-warning">ABORTED</span>', 'deleted' => '<span class="label label-danger">STOPPED</span>');
		
		
		$_widget_tabs   = $this -> load -> view('index/step1/toolbar', $data_step1, TRUE);
		$data_widget_step1['table_objects'] = $this -> load -> view('index/step1/table_objects', $data_step1, TRUE);
		$data_widget_step1['table_recent']  = $this -> load -> view('index/step1/table_recent',  $data_step1, TRUE);
		
		
		
		$_widget        = $this -> load -> view('index/step1/widget',  $data_widget_step1, TRUE);
		
		$_widget_step_1 = widget('widget_step_1' . time(), '', '', $_widget, false, true, false, $_widget_tabs);
		
		/*
		$_table_objects = $this -> load -> view('index/step1/table_objects', $data_step1, TRUE);
		$_widget_table_objects = widget('objects' . time(), 'Objects', '', $_table_objects, false, true, true);
		
		$_table_recent = $this -> load -> view('index/step1/table_recent', $data_step1, TRUE);
		$_widget_table_recent = widget('recent_' . time(), 'Recent', '', $_table_recent, false, true, true);
		*/
		
		$data_step1['_running'] = $_running;
		$data_step1['_widget'] = $_widget_step_1;
		//$data_step1['_table_objects'] = $_widget_table_objects;
		//$data_step1['_table_recent'] = $_widget_table_recent;
		////////////////////////////////////////////////////////////////////////////////////////////////////

		/**
		 * IMPOSTAZIONI STEP2
		 */
		$data_step2[] = '';

		/**
		 * IMPOSTAZIONI STEP4
		 */

		$data_step4 = array();

		/**
		 * IMPOSTAZIONI STEP5
		 */

		$data_widget_step5['_progress_percent'] = $_running ? number_format($_monitor_encoded -> print -> stats -> percent, 2, ',', ' ') : '0';
		$data_widget_step5['_lines'] = $_running ? $_monitor_encoded -> print -> lines : '-';
		$data_widget_step5['_current_line'] = $_running ? $_monitor_encoded -> print -> stats -> line_number : '-';
		//$data_widget_step5['_position'] = $_running ? $_monitor_encoded -> print -> stats -> position : '-';
		$data_widget_step5['_temperature'] = $_running  && isset($_monitor_encoded -> print -> stats -> extruder)? $_monitor_encoded -> print -> stats -> extruder : 0;
		$data_widget_step5['_temperature_target'] = $_running && isset($_monitor_encoded -> print -> stats -> extruder_target) ? $_monitor_encoded -> print -> stats -> extruder_target : '-';
		$data_widget_step5['_bed_temperature'] = $_running && isset($_monitor_encoded -> print -> stats -> bed)? $_monitor_encoded -> print -> stats -> bed : 0;
		$data_widget_step5['_bed_temperature_target'] = $_running && isset($_monitor_encoded -> print -> stats -> bed_target) ? $_monitor_encoded -> print -> stats -> bed_target : '-';
		$data_widget_step5['_pid'] = $_running ? $_attributes['pid'] : 0;
		$data_widget_step5['_velocity'] = $_running && isset($_attributes['speed']) ? $_attributes['speed'] : 100;
		$data_widget_step5['_rpm'] = $_running && isset($_attributes['rpm']) ? $_attributes['rpm'] : 6000;
		$data_widget_step5['_running'] = $_running;
		$data_widget_step5['_file_type'] = $_running ? trim($_file -> print_type) : 'additive';
		$data_widget_step5['mail'] = $_running && isset($_attributes['mail']) ? $_attributes['mail'] : $_SESSION['user']['end-print-email'];
		$data_widget_step5['layer_total'] = $_running && isset($_monitor_encoded -> print -> stats -> layers -> total) ? intval($_monitor_encoded -> print -> stats -> layers -> total) : 0;
		$data_widget_step5['layer_actual'] = $_running && isset($_monitor_encoded -> print -> stats -> layers -> actual) ? intval($_monitor_encoded -> print -> stats -> layers -> actual) : 0;
		$data_widget_step5['flow_rate'] = $_running && isset($_attributes['flow_rate']) ? $_attributes['flow_rate'] : 100;
		$data_widget_step5['fan'] = $_running && isset($_attributes['fan']) ? $_attributes['fan'] : 0;
		$data_widget_step5['z_override'] = $_running ? $_monitor_encoded -> print -> stats -> z_override : 0;
		//$data_widget_step5['mail'] = $_running && isset($_attributes['mail']) && $_attributes['mail'] == true ? 'checked' : '';
		$data_widget_step5['mail'] = $_SESSION['user']['end-print-email'] == true ? 'checked' : '';
		if($_running ){
			$data_widget_step5['mail'] = $_attributes['mail'] == true ? 'checked' : '';
		}
		$data_widget_step5['note'] = $_running && isset($_attributes['note']) ? $_attributes['note'] : '';
		$data_widget_step5['_object_name'] = $_running ? $_object -> obj_name : '';
		$data_widget_step5['_file_name'] = $_running ? $_file -> raw_name : '';
		//$data_widget_step5['ext_temp']          = $_running ? $ext_temp : 0;
		//$data_widget_step5['bed_temp']          = $_running ? $bed_temp : 0;

		//$data_step5['_tab5_monitor_widget'] = widget('_tab5_monitor_widget', 'Print Monitor', '', $this->load->view('index/step5/widget', $data_widget_step5, TRUE), false);
		$data_step5['_tab5_monitor_widget'] = $this -> load -> view('index/step5/widget', array_merge($data_widget_step5, $data), TRUE);
		$data_step5['_running'] = $_running;
		$data_step5['mail'] = $_running && isset($_attributes['mail']) ? $_attributes['mail'] : $_SESSION['user']['end-print-email'];

		/**
		 *
		 * IMPOSTAZIONI STEP6
		 */

		//inclusione dei step
		$data['_step_1'] = $this -> load -> view('index/step1/index', $data_step1, TRUE);
		$data['_step_2'] = $this -> load -> view('index/step2/index', $data_step2, TRUE);
		//$data['_step_3']  = $this->load->view('index/step3/index', $data_step3, TRUE);
		$data['_step_4'] = $this -> load -> view('index/step4/index', $data_step4, TRUE);
		$data['_step_5'] = $this -> load -> view('index/step5/index', $data_step5, TRUE);
		$data['_step_6'] = $this -> load -> view('index/step6/index', array('label' => $data['label']), TRUE);

		$data['_running'] = $_running;
		$data['_object_name'] = $_running ? ' > ' . $_object -> obj_name : '';
		$data['_file_name'] = $_running ? ' > ' . $_file -> file_name : '';
		$data['_file_type'] = $_running ? $_file -> print_type : 'additive';
		
		
		$data_js['_id_file'] = $_running ? $_task['id_file'] : 0;
		$data_js['_id_object'] = $_running ? $_task['id_object'] : 0;
		$data_js['_id_task'] = $_running ? $_task['id'] : 0;
		$data_js['_pid'] = $_running ? $_attributes['pid'] : 0;
		$data_js['_monitor'] = $_running ? $_monitor : '';
		$data_js['_monitor_file'] = $_running ? $_attributes['monitor'] : '';
		$data_js['_data_file'] = $_running ? $_attributes['data'] : '';
		$data_js['_trace_file'] = $_running ? $_attributes['trace'] : '';
		$data_js['_stats_file'] = $_running ? $_attributes['stats'] : '';
		$data_js['_folder'] = $_running ? $_attributes['folder'] : '';
		$data_js['_debug_file'] = $_running ? $_attributes['debug'] : '';
		$data_js['_uri_monitor'] = $_running ? $_attributes['uri_monitor'] : '';
		$data_js['_uri_trace'] = $_running ? $_attributes['uri_trace'] : '';
		$data_js['_seconds'] = $_running ? (time() - intval($_monitor_encoded -> print -> started)) : 0;
		$data_js['_print_type'] = $_running ? $_attributes['print_type'] : $type;
		$data_js['progress_percent'] = $data_widget_step5['_progress_percent'];
		$data_js['print_started'] = $_running ? strtolower($_monitor_encoded -> print -> print_started) : 'false';

		$data_js['layer_total'] = $data_widget_step5['layer_total'];
		$data_js['layer_actual'] = $data_widget_step5['layer_actual'];
		$data_js['flow_rate'] = $data_widget_step5['flow_rate'];
		$data_js['fan'] = $data_widget_step5['fan'];

		//$data_js['_estimated_time']   = $_running && is_array($_stats) ? 'new Array('.implode(',', $_stats['estimated_time']).')' : 'new Array()';
		//$data_js['_progress_steps']   = $_running && is_array($_stats) ? 'new Array('.implode(',', $_stats['progress_steps']).')' : 'new Array()';

		$data_js['_estimated_time'] = $_running && is_array($_stats) ? 'FixedQueue(10, [' . implode(',', $_stats['estimated_time']) . '])' : 'FixedQueue(10, [])';
		$data_js['_progress_steps'] = $_running && is_array($_stats) ? 'FixedQueue(10, [' . implode(',', $_stats['progress_steps']) . '])' : 'FixedQueue(10, [])';

		$data_js['ext_temp'] = $_running && isset($_monitor_encoded -> print -> stats -> extruder)? $_monitor_encoded -> print -> stats -> extruder : 0;
		$data_js['bed_temp'] = $_running && isset($_monitor_encoded -> print -> stats -> bed) ? $_monitor_encoded -> print -> stats -> bed : 0;
		$data_js['ext_target'] = $_running && isset($_monitor_encoded -> print -> stats -> extruder_target) ? $_monitor_encoded -> print -> stats -> extruder_target : 0;
		$data_js['bed_target'] = $_running && isset($_monitor_encoded -> print -> stats -> bed_target) ? $_monitor_encoded -> print -> stats -> bed_target : 0;
		$data_js['_velocity'] = $_running && isset($_attributes['speed']) ? $_attributes['speed'] : 100;
		$data_js['_rpm'] = $_running && isset($_attributes['rpm']) ? $_attributes['rpm'] : 6000;

		$data_js['_request_obj'] = $_request_obj;
		$data_js['_request_file'] = $_request_file;

		$data_js['attributes_file'] = $_running ? $_task['attributes'] : '';
		$data_js['z_override'] = $_running ? $_monitor_encoded -> print -> stats -> z_override : 0;
		$data_js['max_temp'] = $data['max_temp'];

		$_time = $_running ? (time() - intval($_monitor_encoded -> print -> started)) : 0;

		/**
		 * IMPOSTAZIONI LAYOUT
		 */

		$this -> layout -> add_css_file(array('src' => '/fabui/application/modules/create/assets/css/create.css', 'comment' => 'create css'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/fuelux/wizard/wizard.min.js', 'comment' => 'javascript for the wizard'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/jquery.dataTables.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/dataTables.colVis.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/dataTables.tableTools.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/dataTables.bootstrap.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatable-responsive/datatables.responsive.min.js', 'comment' => ''));
		//$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.all.min.js', 'comment' => 'javascript for the noUISlider'));
		//$this -> layout -> add_css_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.min.css', 'comment' => 'javascript for the noUISlider'));
		//$this -> layout -> add_css_file(array('src' => 'application/layout/assets/js/plugin/noUiSlider.7.0.10/jquery.nouislider.pips.min.css', 'comment' => 'javascript for the noUISlider'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/bootstrap-progressbar/bootstrap-progressbar.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/fixed_queue.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/knob/jquery.knob.min.js', 'comment' => 'KNOB'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT'));
		$this -> layout -> add_js_file(array('src' => '/fabui/application/modules/create/assets/js/utilities.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.cust.min.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.resize.min.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.fillbetween.min.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.orderBar.min.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.time.min.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.tooltip.min.js', 'comment' => 'create utilities'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/flot/jquery.flot.axislabels.js', 'comment' => 'create utilities'));

		$js_in_page = $this -> load -> view('index/js', $data_js, TRUE);

		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => 'create module'));

		$this -> layout -> set_compress(false);
		$this -> layout -> view('index/index', $data);

	}

	/** show additive o subtractive preparation print */
	public function show($type) {

		$this -> load -> helper('serial_helper');
		
		$data = array();

		if ($type == 'additive') {
			
			$this -> config -> load('fabtotum', TRUE);
			
			if(!file_exists($this -> config -> item('fabtotum_config_units', 'fabtotum'))){
				$this->load->helper('print_helper');
				create_default_config();
			}
			
			$config_units = json_decode(file_get_contents($this -> config -> item('fabtotum_config_units', 'fabtotum')), TRUE);
			
			if($config_units['settings_type'] == 'custom'){
				$config_units = json_decode(file_get_contents($this -> config -> item('fabtotum_custom_config_units', 'fabtotum')), TRUE);
			}
			

			$label_button = 'Engage';
			$action_button = 'feeder';

			$data['show_feeder'] = $this -> layout -> getFeeder();

			if (!$data["show_feeder"]) {
				$label_button = 'Continue';
				$action_button = '';
			}

			$data['label_button']  = $label_button;
			$data['action_button'] = $action_button;
			$data['calibration']   = isset($config_units['print']['calibration']) ? $config_units['print']['calibration'] : 'homing';

		}

		$this -> load -> view('index/ajax/' . $type, $data);
	}
	
	
	function pre_heat(){
		
	}
	
	public function history() {

		$this -> load -> model('tasks');
		
		$this -> load -> helper('smart_admin_helper');
		$this -> load -> helper('ft_date_helper');
		
		$data['start_date'] = date('d/m/Y', strtotime('today - 30 days'));
		$data['end_date'] = date('d/m/Y', strtotime('today'));
		
		$data['min_date'] = date('d/m/Y', strtotime($this->tasks->get_min_date('make')));
		
		/** LAYOUT */
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/jquery.dataTables.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/dataTables.colVis.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/dataTables.tableTools.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatables/dataTables.bootstrap.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/datatable-responsive/datatables.responsive.min.js', 'comment' => ''));

		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/bootstrap-datepicker/moment.min.js', 'comment' => ''));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/bootstrap-datepicker/daterangepicker.min.js', 'comment' => ''));
		$this -> layout -> add_css_file(array('src' => '/assets/js/plugin/bootstrap-datepicker/daterangepicker.css', 'comment' => ''));
		
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/morris/raphael.min.js', 'comment' => 'charts'));
		$this -> layout -> add_js_file(array('src' => '/assets/js/plugin/morris/morris.min.js', 'comment' => 'charts'));

		$this -> layout -> add_css_in_page(array('data' => $this -> load -> view('history/css', '', TRUE), 'comment' => 'create module'));
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('history/js', $data, TRUE), 'comment' => 'create module'));
		
		
		

		$table = $this -> load -> view('history/table', $data, TRUE);
		

		$attr['data-widget-icon'] = 'fa fa-history';
		$data['widget_table'] = widget('history' . time(), 'History', $attr, $table, false, true, false);

		//$attr['data-widget-icon'] = 'fa fa-bar-chart';
		//$data['widget_stats'] = widget('stats', 'Stats', $attr, '', false, false, false);
		$this -> layout -> set_compress(false);

		$this -> layout -> view('history/index', $data);
	}

	function _get_make_tasks($filters) {

		$this -> load -> model('tasks');
		return $this -> tasks -> get_make_tasks($filters);
	}

	function history_table_data() {

		$params = $this -> input -> get();

		$filters['start_date'] = !isset($params['start_date']) || $params['start_date'] == '' ? date('d/m/Y', strtotime('today - 30 days')) : $params['start_date'];
		$filters['end_date']   = !isset($params['end_date'])   || $params['end_date']   == '' ? date('d/m/Y', strtotime('today'))  : $params['end_date'];
		$filters['type']       = isset($params['type']) ? $params['type'] : '';
		$filters['status']     = isset($params['status']) ? $params['status'] : '';

		$tasks = $this -> _get_make_tasks($filters);

		$data['icons'] = array('print' => 'icon-fab-print', 'mill' => 'icon-fab-mill', 'scan' => 'icon-fab-scan');

		$data['status_label'] = array('performed' => '<span class="label label-success">COMPLETED</span>', 'stopped' => '<span class="label label-warning">ABORTED</span>', 'deleted' => '<span class="label label-danger">STOPPED</span>');

		$aaData = array();

		foreach ($tasks as $task) {
			
				$attributes = json_decode(utf8_encode(preg_replace('!\\r?\\n!', "<br>", $task['task_attributes'])), true);
				
				$when = strtotime($task['finish_date']) > strtotime("-1 day") ? get_time_past($task['finish_date']) . ' ago' : date('d M, Y', strtotime($task['finish_date']));
				$info = '<h4>';
				if ($task['file_name'] != '')
					$info .= '<a href="' . site_url('objectmanager/edit/' . $task['id_object']) . '"><i class="fa fa fa-file-o"></i> ' . $task['raw_name'] . '</a>';
				if ($task['object_name'] != '')
					$info .= ' <small>> <i class="fa fa fa-folder-open-o"></i> ' . $task['object_name'] . '</small>';
				if (isset($attributes['mode_name']) && $attributes['mode_name'] != '')
					$info .= '<a href="#">' . ucfirst($attributes['mode_name']) . '</a><small> </small>';
				$info .= '</h4>';
	
	
				$td_0 = '<a href="#" > <i class="fa fa-chevron-right fa-lg" data-toggle="row-detail" title="Show Details"></i> </a>';
				$td_1 = $when;
				$td_2 = '<strong><i class="<' . $data['icons'][$task['type']] . '"></i> <span class="hidden-xs">' . ucfirst($task['type']) . '</strong></span>';
				$td_3 = $data['status_label'][$task['status']];
				$td_4 = $info;
				$td_5 = $task['duration'];
				$td_6 = date('d M, Y', strtotime($task['start_date'])) . ' at ' . date('G:i', strtotime($task['start_date']));
				$td_7 = date('d M, Y', strtotime($task['finish_date'])) . ' at ' . date('G:i', strtotime($task['finish_date']));
				$td_8 = isset($attributes['note']) ? $attributes['note'] : '';
				$td_9 = $task['type'];
				$td_10 = $task['id_file'];
				$td_11 = $task['id_object'];
	
				$aaData[] = array($td_0, $td_1, $td_2, $td_3, $td_4, $td_5, $td_6, $td_7, $td_8, $td_9, $td_10, $td_11);
			}


		$stats = $this -> load -> view('history/stats', $data, TRUE);

		echo json_encode(array('aaData' => $aaData));

	}

	function history_stats_data() {

		$params = $this -> input -> get();

		$filters['start_date'] = $params['start_date'] == '' ? date('d/m/Y', strtotime('today - 30 days')) : $params['start_date'];
		$filters['end_date'] = $params['end_date'] == '' ? date('d/m/Y', strtotime('today')) : $params['end_date'];
		$filters['type'] = $params['type'];
		$filters['status'] = $params['status'];

		$tasks = $this -> _get_make_tasks($filters);
		
		$this -> load -> helper('ft_date_helper');
		
		
		
		$data['icons'] = array('print' => 'icon-fab-print', 'mill' => 'icon-fab-mill', 'scan' => 'icon-fab-scan');

		$data['status_label'] = array('performed' => '<span class="label label-success">COMPLETED</span>', 'stopped' => '<span class="label label-warning">ABORTED</span>', 'deleted' => '<span class="label label-danger">STOPPED</span>');

		$data['stats_label'] = array('total_time' => '<i class="fa fa-clock-o"></i> Total time', 'performed' => '<i class="fa fa-check"></i> Completed', 'stopped' => '<i class="fa fa-times"></i> Aborted', 'deleted' => '<i class="fa fa-ban"></i> Stopped');

		$data['type_options'] = array('print' => 'Print', 'mill' => 'Mill', 'scan' => 'Scan');

		$data['status_options'] = array('performed' => 'Completed', 'stopped' => 'Aborted', 'deleted' => 'Stopped');
		$data['status_colors']  = array('performed' => '#7e9d3a', 'stopped' => '#FF9F01', 'deleted' => '#a90329');
		

		$data['stats'] = array();
		
		if(count($tasks) > 0 ){
			
		

		if ($filters['type'] == '') {

			foreach ($data['type_options'] as $type => $label) {
				if ($type != '')
					$data['stats'][$type]['total_time'] = $this -> tasks -> get_total_time('make', $type, $filters['status'], $filters['start_date'], $filters['end_date']);

				if ($filters['status'] == '') {
					foreach ($data['status_options'] as $status => $label) {
						if ($status != '')
							$data['stats'][$type][$status] = $this -> tasks -> get_total_tasks('make', $type, $status, $filters['start_date'], $filters['end_date']);
					}
				} else {
					$data['stats'][$type][$filters['status']] = $this -> tasks -> get_total_tasks('make', $type, $filters['status'], $filters['start_date'], $filters['end_date']);
				}

			}

		} else {
			$data['stats'][$filters['type']]['total_time'] = $this -> tasks -> get_total_time('make', $filters['type'], $filters['status'], $filters['start_date'], $filters['end_date']);

			if ($filters['status'] == '') {
				foreach ($data['status_options'] as $status => $label) {
					if ($status != '')
						$data['stats'][$filters['type']][$status] = $this -> tasks -> get_total_tasks('make', $filters['type'], $status, $filters['start_date'], $filters['end_date']);
				}
			} else {
				$data['stats'][$filters['type']][$filters['status']] = $this -> tasks -> get_total_tasks('make', $filters['type'], $filters['status'], $filters['start_date'], $filters['end_date']);
			}
		}
		
		}
		
		
		echo $stats = $this -> load -> view('history/stats', $data, TRUE);
		
		

	}

}
