<?php

class Configuration extends CI_Model {


	protected $_table_name = 'sys_configuration';

	var $title   = '';
	var $content = '';
	var $date    = '';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}



	/**
	 * 
	 * @param unknown $key
	 * @return string
	 */
	function get_config_value($key){

		$this->db->select('value');
		$query = $this->db->get_where($this->_table_name, array('key' => $key));
		$result =  $query->result();
        
        

		if(isset($result[0]))
			return $result[0]->value;
		else
			return '';

	}
	
	
	/**
	 * 
	 * @param unknown $key
	 * @param unknown $value
	 */
	function save_confi_value($key, $value){
		
        
        $this->exist_key($key);
        
		$this->db->set('value', $value);
		$this->db->where('key' ,$key);
		$this->db->update($this->_table_name);
		
        
       
        
		
	}
    
    
    
    function exist_key($key){
        
        $query = $this->db->get_where($this->_table_name, array('key' => $key));
        $result =  $query->result();
        
        if(count($result) < 1){    
            $this->add_key($key);
            
        }
           
    }
    
    
    
    function add_key($key){
        
        $this->db->set('key', $key);
		
		$this->db->insert($this->_table_name);
        
    }





}