<?php

class Tasks extends CI_Model {


	protected $_table_name = 'sys_tasks';
	
	
	protected $_COMPLETED_STATUS = array('performed', 'stopped', 'deleted');


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
		
		//$this->db->join('sys_objects', 'sys_objects.id = sys_tasks.id_object', 'left');
		//$this->db->join('sys_files', 'sys_files.id = sys_tasks.id_file', 'left');
		
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
        }
        
        //return $this->db->delete($this->_table_name, array('id' => $id_task));
        
        $this->update($id_task, array('status' => 'deleted', 'finish_date' =>'now()'));
        
    }
	
	
	
	function get_make_tasks($filters = array()){
		
		
		
		$CI =& get_instance();
		
		$CI->load->helper('ft_date_helper');
		
		$this->db->select('sys_tasks.id as id, sys_tasks.user as user, sys_tasks.controller as controller, sys_tasks.type as type, sys_tasks.status as status, sys_tasks.id_object as id_object, 
						 sys_tasks.id_file as id_file, sys_tasks.start_date as start_date, sys_tasks.finish_date as finish_date, sys_objects.obj_name as object_name, sys_files.file_name as file_name,
						 sys_tasks.attributes as task_attributes, sys_files.raw_name as raw_name,
						 TIMEDIFF(sys_tasks.finish_date, sys_tasks.start_date) as duration', false)
				->where('controller', 'make')
				->where('sys_tasks.user', $_SESSION['user']['id'])
				->join('sys_objects', 'sys_objects.id = sys_tasks.id_object', 'left')
				->join('sys_files', 'sys_files.id = sys_tasks.id_file', 'left')
				->order_by('finish_date', 'DESC');
		
		
		
		if(is_array($filters)){
			
			if(isset($filters['start_date']) && $filters['start_date'] != ''){
				
				$this->db->where("finish_date >=", date_to_mysql($filters['start_date'])." 00:00:00");
			}
			
			if(isset($filters['end_date']) && $filters['end_date'] != ''){

				$this->db->where("finish_date <=", date_to_mysql($filters['end_date'])." 23:59:59");
			}
			
			
			if(isset($filters['type']) && $filters['type'] != ''){
				$this->db->where('type', $filters['type']);
			}

			if(isset($filters['status']) && $filters['status'] != ''){
				$this->db->where('status', $filters['status']);
			}
			
		}
		//$result = $this->db->get($this->_table_name)->result_array();
		//echo $this->db->last_query(); exit();
		return $this->db->get($this->_table_name)->result_array();
	}
	
	function get_total_time($controller, $type, $status, $from_date, $to_date){
		
		$CI =& get_instance();
		$CI->load->helper('ft_date_helper');
		
		$this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(finish_date, start_date))))) as total_time', false)
						   ->where('controller', $controller)
						   ->where('type', $type)
						   ->where('sys_tasks.user', $_SESSION['user']['id']);
		
		if($status != ''){
			$this->db->where('status', $status);
		}	   
		
		if($from_date != ''){
			$this->db->where("finish_date >=", date_to_mysql($from_date)." 00:00:00");
		}
		
		if($to_date != ''){
			$this->db->where("finish_date <=", date_to_mysql($to_date)." 23:59:59");
		}				   
				
						   
		$result = $this->db->get($this->_table_name)->result_array();
						   
		return isset($result[0]['total_time']) ? $result[0]['total_time'] : 0;
		
	}

	function get_total_tasks($controller, $type, $status, $from_date, $to_date){
		
		$CI =& get_instance();
		$CI->load->helper('ft_date_helper');
		
		$this->db->select('count(*) as total', false)
						   ->where('controller', $controller)
						   ->where('type', $type)
						   ->where('status', $status)
						   ->where('sys_tasks.user', $_SESSION['user']['id']);
						   
		if($from_date != ''){
			$this->db->where("finish_date >=", date_to_mysql($from_date)." 00:00:00");
		}
		
		if($to_date != ''){
			$this->db->where("finish_date <=", date_to_mysql($to_date)." 23:59:59");
		}
		
		$result = $this->db->get($this->_table_name)->result_array();				   
		
		return isset($result[0]['total']) ? $result[0]['total'] : 0;
		
	}
	
	
	function get_file_stats($id_file, $from_date, $to_date){
		
		$CI =& get_instance();
		$CI->load->helper('ft_date_helper');
				
		$this->db->select('count(*) as total, status,  DATE(finish_date) as date, SEC_TO_TIME(SUM(TIME_TO_SEC((TIMEDIFF(finish_date, start_date))))) as total_time', false)->where('id_file', $id_file);
		
		if($from_date != ''){
			$this->db->where("finish_date >=", date_to_mysql($from_date)." 00:00:00");
		}
		
		if($to_date != ''){
			$this->db->where("finish_date <=", date_to_mysql($to_date)." 23:59:59");
		}
		
		
		$this->db->group_by('status');
		$this->db->group_by('DATE(finish_date)');
		$this->db->order_by('DATE(finish_date)', 'ASC');
			
		$result =  $this->db->get($this->_table_name)->result_array();
		
		
		return $result;
		
	}
    
	
	
	function get_last_creations($type, $limit_end = 10, $limit_start = 0){
		
		$this->db->select('st.id_file as id, st.id_object as id_object, st.finish_date as finish_date, sys_files.raw_name, sys_files.note as note, st.status, TIMEDIFF(st.finish_date, st.start_date) as duration, sys_files.attributes as attributes, sys_files.file_type, sys_objects.obj_name as object_name', false)
				  ->from('sys_tasks as st')
				  ->where_in('st.status', $this->_COMPLETED_STATUS)
				  ->where('st.type', $type)
				  ->where('st.user', $_SESSION['user']['id'])
				  ->where('st.finish_date = (select MAX(sys_tasks.finish_date) from sys_tasks where sys_tasks.id_object = st.id_object and sys_tasks.id_file = st.id_file)')
				  ->join('sys_objects', 'sys_objects.id = st.id_object', 'left')
				  ->join('sys_files', 'sys_files.id = st.id_file', 'left')
				  ->group_by('st.id_file')
				  ->order_by('st.finish_date', 'DESC');
						
		$result = $this->db->get($this->_table_name, $limit_end, $limit_start)->result_array();
		
		return $result;
		
	}
	
	
	function get_min_date($controller = ''){
		
		$this->db->select('MIN(finish_date) as min', false);
		
		if($controller != ''){
			$this->db->where('controller', $controller);
		}
		
		$query = $this->db->get($this->_table_name);
		
		if ($query->num_rows() > 0){
			
			$row = $query->row_array(); 
			return $row['min'];
		}

		return NULL;	
	}

	function get_file_tasks($file_id, $filters){
		
		$CI =& get_instance();
		$CI->load->helper('ft_date_helper');
		
		$this->db->select('*, TIMEDIFF(finish_date, start_date) as duration', false);
		$this->db->where('id_file', $file_id);
		
		if(is_array($filters)){
			
			if(isset($filters['start_date']) && $filters['start_date'] != ''){
				$this->db->where("finish_date >=", date_to_mysql($filters['start_date'])." 00:00:00");
			}
			
			if(isset($filters['end_date']) && $filters['end_date'] != ''){
				$this->db->where("finish_date <=", date_to_mysql($filters['end_date'])." 23:59:59");
			}	
		}
		
		$this->db->order_by('finish_date', 'DESC');
		
		$result = $this->db->get($this->_table_name)->result_array();
		
		return $result;
		
	}
	


}