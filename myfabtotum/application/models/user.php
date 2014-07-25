<?php

class User extends CI_Model {


	protected $_table_name = 'sys_user';


	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $email
	 * @param unknown $password
	 */
	function login($email, $password){

		$this->db->where('email', $email);
		$this->db->where('password', md5($password));

		$query = $this->db->get($this->_table_name);
        
        

		if( $query->num_rows == 1 )  {
			return true;
		}else{
			return false;
		}

	}

	
	/**
	 * 
	 * @param unknown $email
	 * @return unknown|string
	 */
	function get_user($email){

		$this->db->where('email', $email);

		$query = $this->db->get($this->_table_name);

		$result = $query->result();

		if(isset($result[0]) && $result[0] != ''){
			return $result[0];
		}else{
			return '';
		}

	}





}