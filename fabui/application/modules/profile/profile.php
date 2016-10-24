<?php 
class Profile extends Module {

	public function __construct()
	{
		parent::__construct();
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);

	}

	public function index(){


		/** LOAD HELPER */
		$this->load->helper('form');
		$this -> load -> helper('smart_admin_helper');
		
		$data['user'] = $_SESSION['user'];
		
		/**  */
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js', 'comment'=>''));
		
		
		/** */
		$css_in_page = $this->load->view('index/css', '', TRUE);
		$js_in_page  = $this->load->view('index/js', '', TRUE);
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		$this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => ''));
		
		
		
		/** LOCK SCREEN OPTIONS */
		$lock_screen_options['0']     = 'None';
		//$lock_screen_options['10']    = '10 seconds';
		$lock_screen_options['300']   = 'after 5 mins'; 
		$lock_screen_options['600']   = 'after 10 mins';
		$lock_screen_options['1200']  = 'after 20 mins';
		$lock_screen_options['1800']  = 'after 30 mins';
		$lock_screen_options['3600']  = 'after 1 Hour';
		$lock_screen_options['28800'] = 'after 8 Hours'; 
		
		$data['lock_screen_options'] = $lock_screen_options;
		$data['lock_screen'] = isset($_SESSION['user']['lock-screen']) ? $_SESSION['user']['lock-screen'] : '0';
		
		$data['widget'] = $this -> load -> view('index/widget', $data, TRUE);
		$attr['data-widget-icon'] = 'fa fa-user';
		$attr['data-widget-fullscreenbutton'] = 'false';
		
		$toolbar = $this -> load -> view('index/widget_toolbar', $data, TRUE);
		
		$data['widget'] = widget('profile' . time(), 'Profile', $attr, $data['widget'], false, true, false, $toolbar);
		
		//print_r($_SESSION['user']);exit();
		
		
		$this -> layout -> set_compress(false);
		/** */
		$this->layout->view('index/index', $data); 
	}
}
?>