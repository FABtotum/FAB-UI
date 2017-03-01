<?php

class Printsettings extends CI_Model {


	protected $_table_name = 'sys_files';

	var $title   = '';
	var $content = '';
	var $date    = '';

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	
	
	function get_sections(){
		
		
		$query = $this->db->get('sys_print_settings_sections');
		return $query->result();
		
	}
	
	
	
	function get_fieldsets($id_section, $id_group){
		
		$query = $this->db->get_where('sys_print_settings_fieldset', array('parent_section' => $id_section, 'parent_group'=>$id_group));
		$result = $query->result();
		
		
		
		return $result;
		
	}
	
	
	
	
	function get_fields($id_section, $id_group, $id_fieldset){
		
		$query = $this->db->get_where('sys_print_settings_fields', array('parent_fieldset' => $id_fieldset, 'parent_section'=>$id_section, 'parent_group' => $id_group));
		$result = $query->result();
		
		return $result;
	}
	
	
	
	
	function get_groups($id_section){
		

		$query = $this->db->get_where('sys_print_settings_groups', array('parent_section' => $id_section));
		$result = $query->result();
		
		
		
		return $result;
		
	}
	
	
	
	function get_section_by_id($id_section){
		
		$query = $this->db->get_where('sys_print_settings_sections', array('id' => $id_section));
		$result = $query->result();
		if(isset($result[0])){
			return $result[0];
		}else{
			return FALSE;
		}
	}
	

	/**
	 *
	 * @param string $column_order
	 * @param string $order
	 */
	function get_all($column_order = 'insert_date', $order = 'DESC'){

		$this->db->order_by($column_order, $order);
		$query = $this->db->get($this->_table_name);
		return $query->result();

	}


	/**
	 *
	 * @param unknown $data
	 */
	function insert_file($data){
			
		$this->db->set('insert_date', 'now()', FALSE);
		
		$data['file_size'] = str_replace('.', '', $data['file_size']);

		$this->db->insert($this->_table_name, $data);

		return $this->db->insert_id();

	}


	/**
	 *
	 * @param unknown $id_file
	 */
	function delete($id_file){
		
		$_file = $this->get_file_by_id($id_file);
		//cancello il file dal server
		unlink($_file->full_path);

		//cancello il file dal db
		return $this->db->delete($this->_table_name, array('id' => $id_file));

	}

	/**
	 *
	 * @param unknown $id_file
	 */
	function get_file_by_id($id_file){

		$query = $this->db->get_where($this->_table_name, array('id' => $id_file));
		$result = $query->result();
		if(isset($result[0])){
			return $result[0];
		}else{
			return FALSE;
		}

	}
	
	
	/**
	 * 
	 */
	function get_file_extensions(){
		
		$this->db->select('file_ext');
		$this->db->group_by('file_ext');
		$query = $this->db->get($this->_table_name);
		return $query->result();
	}
	
	
	/**
	 * 
	 */
	function get_total_space(){
		
		$this->db->select_sum('file_size');
		$query = $this->db->get($this->_table_name);
		$result =  $query->result();
		
		if(isset($result[0]) && $result[0] != ''){
			
			return $result[0]->file_size;
			
		}else{
			return 0;
		}
	}





}