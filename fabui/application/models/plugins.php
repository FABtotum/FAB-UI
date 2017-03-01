<?php

class Plugins extends CI_Model {


	protected $_table_name = 'sys_plugins';

	
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	
	function is_active($plugin){
		
		$this->db->where('name', $plugin);
		
		$query  = $this->db->get($this->_table_name);
		
		return $query->num_rows() > 0 ? true : false;
		
	}
	
	
	
	function active($plugin){
		
		
		if(!$this->is_active($plugin)){
			
			
			if(file_exists(APPPATH.'plugins/'.$plugin.'/'.$plugin.'.php')){
					
				$this->load->helper('ft_plugin_helper');	
				
				$plugin_info = plugin_info($plugin);
				
				$this->db->insert($this->_table_name, array('name' => $plugin, 'attributes'=>json_encode($plugin_info)));
			
			}

		}
		
	}
	
	
	function deactive($plugin){
		
		if($this->is_active($plugin)){
			
			$this->db->delete($this->_table_name, array('name' => $plugin));
			
		}
		
	}
	
	
	
	function get_activeted_plugins(){
		
		
		$this->db->order_by('name');
		$query = $this->db->get($this->_table_name);
		return $query->result();
	}


}