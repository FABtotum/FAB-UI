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
			return false;
		}

	}
    
    
    
    public function update_login($id){
        
        $this->db->set('last_login', 'now()', FALSE);
		$this->db->set('session_id', session_id());
        
        $this->db->where('id', $id);
        $this->db->update($this->_table_name); 
        
    }
	
	
	
	public function add($data){
		
		$this->db->set('first_name', $data['first_name']);
		$this->db->set('last_name', $data['last_name']);
		$this->db->set('email', $data['email']);
		$this->db->set('password', md5($data['password']));
		$this->db->set('settings', $data['settings']);
		
		
		$this->db->insert($this->_table_name);
		return $this->db->insert_id();
		
	}
	
	public function update($id, $data){
		
		foreach($data as $key => $value){
			
			$this->db->set($key, $value);
			
		}
		
		$this->db->where('id', $id);
        $this->db->update($this->_table_name); 
		
		
	}
	
	
	function get_by_token($token){
		
		
		$query = $this->db->get($this->_table_name);
		$result = $query->result();
		
		$user = false;
		
		foreach($result as $row){
			
			$_settings = json_decode($row->settings, true);
			
			
			if(isset($_settings['token']) && $_settings['token'] == $token){
				
				$user = $row;
				break;
				
			}
		}
		
		return $user;
		
		/*
		
		$this->db->where('token', $token);

		$query = $this->db->get($this->_table_name);

		$result = $query->result();

		if(isset($result[0]) && $result[0] != ''){
			return $result[0];
		}else{
			return false;
		} */
		
		
		
		
	}





}