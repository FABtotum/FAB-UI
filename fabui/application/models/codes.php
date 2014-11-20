<?php

class Codes extends CI_Model {


	protected $_table_name = 'sys_codes';


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	/**
	 *
	 * @param string $order
	 */
	function get_all($type=''){
		
		if($type != ''){
			$this->db->where('type', $type);
		}
		
		
		$this->db->order_by('code', 'ASC');
		
		$query = $this->db->get($this->_table_name);
		return $query->result_array();

	}
	

}