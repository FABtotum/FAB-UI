<?php 
class Profile extends Module {

	public function __construct()
	{
		parent::__construct();

	}

	public function index(){

		$this->layout->view('index/index');
	}
}
?>