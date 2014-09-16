<?php

class Tasks extends CI_Model {


	protected $_table_name = 'sys_tasks';


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}



	/**
	 *
	 * @param unknown $data
	 */
	function add_task($data = array()){

		foreach($data as $key => $value){

			$this->db->set($key, $value);
		}

		$this->db->set('start_date', 'NOW()', false);

		$this->db->insert($this->_table_name);

		return $this->db->insert_id();

	}



	function update($id, $data = array()){

		if(isset($data['finish_date']) && $data['finish_date'] == strtolower('now()')){
			$this->db->set('finish_date',  'NOW()', false);
			unset( $data['finish_date']);
		}

		foreach($data as $key => $value){
			$this->db->set($key, $value);
		}

		$this->db->where('id' ,$id);
		$this->db->update($this->_table_name);
		
		



	}


	function get_by_id($id){

		$query = $this->db->get_where($this->_table_name, array('id' => $id));
		$result = $query->result();

		if(isset($result[0])){
			return $result[0];
		}else{
			return FALSE;
		}

	}





	function get_running($controller = '', $type = ''){
		
		if($controller !=  ''){
			$this->db->where('controller', $controller);
		}
		
		if($type !=  ''){
			$this->db->where('type', $type);
		}
		
		$this->db->where('status' ,'running');
		$query = $this->db->get($this->_table_name);
		$result = $query->result_array();



		if(isset($result[0])){
			return $result[0];
		}else{
			return FALSE;
		}

	}
	
	
	
	
	function get_lasts($limit_start = 0, $limit_end = 10){
		
		
        $this->db->where('status != "running"');
		$this->db->where('user', $_SESSION['user']['id']);
		$this->db->order_by('start_date', 'desc');
		$query = $this->db->get($this->_table_name, $limit_end, $limit_start);
		
	
		return $query->result_array();
	}
    
    
    function delete($id_task){
        
        
        $_task = $this->get_by_id($id_task);
        
        if(isset(json_decode($_task->attributes)->folder) && json_decode($_task->attributes) != ''){
            
            $this->load->helper("file"); // load the helper
            
            
            shell_exec('sudo rm -rf '.json_decode($_task->attributes)->folder);
            
            
            //delete_files(json_decode($_task->attributes)->folder, true);
            //rmdir(json_decode($_task->attributes)->folder);
            
        }
        
        //return $this->db->delete($this->_table_name, array('id' => $id_task));
        
        $this->update($id_task, array('status' => 'deleted', 'finish_date' =>'now()'));
        
        
        
    }
    
    
    
    
    
    
    
    

}