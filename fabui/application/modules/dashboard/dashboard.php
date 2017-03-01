<?php
class Dashboard extends Module {

	private $_printer_busy = false;

	public function __construct() {
		
		
		
		parent::__construct();

		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);
		
		$this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT CHANGE SETTINGS  */
        if(is_printer_busy()){
            $this->layout->set_printer_busy(true);
        }

	}
	
	
	public function index(){
		
		$this->load->helper('smart_admin_helper');
		
		$this -> load -> library('WidgetsFactory');
		
		$twitter_widget = $this -> widgetsfactory -> load('twitter');
		$instagram_widget = $this->widgetsfactory->load('instagram');
		$blog_widget = $this->widgetsfactory->load('blog');
		
		$data['twitter_widget'] = $twitter_widget->content();
		$data['instagram_widget'] = $instagram_widget->content();
		$data['blog_widget'] = $blog_widget->content();
		
		
		//
		//$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/fancybox/jquery.fancybox.pack.js', 'comment' => 'javascript for the noUISlider'));
        //$this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/fancybox/jquery.fancybox.css', 'comment' => 'javascript for the noUISlider'));
		
		$this -> layout -> view('index/index', $data);
	}
	
	public function index_old() {

		$this->load->helper('serial_helper');

		//GET ALL ACTIVE WIDGETS
		$this -> load -> library('WidgetsFactory');

		$_widget_tasks = $this -> widgetsfactory -> load('tasks');
		$_widget_shortcut = $this -> widgetsfactory -> load('shortcut');
		$_widget_cam = $this -> widgetsfactory -> load('cam');

		/**
		 * HELPERS
		 */
		//$this->load->helper('smart_admin_helper');

		/**
		 * LAYOUT
		 */
		$this -> layout -> add_css_file(array('src' => 'application/modules/dashboard/assets/css/dashboard.css', 'comment' => 'dashboard css'));
		//$this->layout->add_js_file(array('src'=>'application/modules/dashboard/assets/js/dashboard.js', 'comment'=>'dashboard js'));

		$_block_1 = array();
		$_block_2 = array();
		$_block_3 = array();
		$_block_4 = array();

		$_block_1[] = $_widget_shortcut -> content();
		$_block_2[] = $_widget_tasks -> content();
		$_block_3[] = $_widget_cam -> content();

		$_template = 1;

		$data["_blok_1"] = $_block_1;
		$data["_blok_2"] = $_block_2;
		$data["_blok_3"] = $_block_3;
		$data["_blok_4"] = $_block_4;

		/** CHECK FOR WIZARD COMPLETE SESSION */
		if (isset($_SESSION['wizard_completed']) && $_SESSION['wizard_completed'] = true) {

			$data['wizard_complete'] = true;
			unset($_SESSION['wizard_completed']);

		}

		$js_in_page = $this -> load -> view('index/js', $data, TRUE);
		$this -> layout -> add_js_in_page(array('data' => $js_in_page, 'comment' => ''));

		$this -> layout -> set_layout_title(get_class($this));
		$this -> layout -> set_compress(false);
		$this -> layout -> view('index/template/' . $_template, $data);

	}

	public function edit() {

		/**
		 * LOAD CSS IN PAGE
		 */
		$this -> layout -> add_css_in_page(array('data' => $this -> load -> view('edit/css', '', TRUE), 'comment' => 'DASHBOARD EDIT  IN PAGE CSS'));

		/**
		 * LOAD JS IN PAGE
		 */
		$this -> layout -> add_js_in_page(array('data' => $this -> load -> view('edit/js', '', TRUE), 'comment' => 'DASHBOARD EDIT  IN PAGE JS'));

		/** JS PLUGIN */
		$this -> layout -> add_js_file(array('src' => 'application/layout/assets/js/jquery-sortable.js', 'comment' => ''));

		/** LOAD HELPERS */
		$this -> load -> helper('file');
		$this -> load -> helper('ft_widget_helper');

		/** LOAD WIDGETS */
		$widgets = get_dir_file_info(APPPATH . 'widgets');

		$data['_widgets'] = $widgets;

		$this -> layout -> view('edit/index', $data);

	}

}
?>