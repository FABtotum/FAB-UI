<?php 
class Profile extends Module {

	public function __construct()
	{
		parent::__construct();
        $this->lang->load($_SESSION['language']['name'], $_SESSION['language']['name']);

	}

	public function index(){



		$data['user'] = $_SESSION['user'];
		
		/**  */
		$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/jquery-validate/jquery.validate.min.js', 'comment'=>''));
		
		
		//$this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/holder/holder.js', 'comment'=>''));
		//$this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/fileinput/fileinput.min.css', 'comment'=>''));
		
		
		/** */
		$css_in_page = $this->load->view('index/css', '', TRUE);
		$js_in_page  = $this->load->view('index/js', '', TRUE);
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => ''));
		$this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => ''));
		
		
		
		
		/** */
		$this->layout->view('index/index', $data); 
	}
}
?>