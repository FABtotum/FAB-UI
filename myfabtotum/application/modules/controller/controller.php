<?php 

class Controller extends Module {

	public function __construct()
	{
		parent::__construct();

	}

	public function index(){

		
	}
	
	
	public function updates(){
		
		$this->load->helper('update_helper');
		$_update_list = myfab_update_list();

		
		if(count($_update_list) > 0){
			$data['update_list'] = $_update_list;
			
			echo $this->load->view('update', $data, TRUE);
			
		}
		
	}
	
	
	public function tasks(){
		
		echo $this->load->view('tasks', '', TRUE);
		
	}

	
	
	public function notifications(){
		
		echo $this->load->view('notifications', '', TRUE);
		
	}



}

?>