<?php
class Error extends Module {

	public function __construct() {
		
		parent::__construct();
		$this -> lang -> load($_SESSION['language']['name'], $_SESSION['language']['name']);

	}
	
	
	
	
	public function index(){
		
	}
	
	

	public function show_404(){
		
	
		$this->layout->view('404/index');
		
	}

}
