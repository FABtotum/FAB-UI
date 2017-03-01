<?php

class Eeprom extends CI_Model {

	protected $_table_name = 'eeprom_configs';
	
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
	public function get_all(){
		
		return $this->db->from($this->_table_name)
						->order_by('name')
						->get()
						->result_array();
		
	}

}
