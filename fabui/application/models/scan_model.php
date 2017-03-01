<?php

class Scan_model extends CI_Model {


	protected $_table_name = 'sys_scan_configuration';

    
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}


	/**
	 *
	 * @param string $column_order
	 * @param string $order
	 */
	function get_all(){

		$query = $this->db->get($this->_table_name);
		return $query->result();

	}
	
	
	/**
	 * 
	 * @param unknown $data
	 */
	function get($data){
		
		foreach($data as $key => $value){
			$this->db->where($key, $value);
		}
		
		$query = $this->db->get($this->_table_name);
		
		if($query->num_rows() == 1){
			return $query->row();
		}else{
			return $query->result();
		}
		
		 
		
		
	}

	
	






}