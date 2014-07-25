<?php

class Objects extends CI_Model {


	protected $_table_name = 'sys_objects';


	

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
	function get_all($column_order = 'date_insert', $order = 'DESC', $array = false){


        $this->db->select($this->_table_name.'.id, '.$this->_table_name.'.obj_name, '.$this->_table_name.'.obj_description, '.$this->_table_name.'.date_insert, '.$this->_table_name.'.date_updated, count(id_file) as num_files');
        $this->db->group_by($this->_table_name.'.id');
        
        $this->db->join('sys_obj_files', 'sys_obj_files.id_obj = '.$this->_table_name.'.id', 'left');
        
		$this->db->order_by($column_order, $order);
		$query = $this->db->get($this->_table_name);
        
       
		
        if($array == true){
              return $query->result_array();
        }else{
              return $query->result();
        }

	}
    
    
    /**
     * GET ALL OBJECTS WITH PRINTABLE FILES
     */
    function get_for_print(){
        
        
        $printable_files[] = '.gc';
        $printable_files[] = '.gcode';
        $printable_files[] = '.nc';
        
        /** GET ALL OBJECTS */
        $all_objects = $this->get_all();
        
        $objects = array();
        
        /*
        foreach($all_objects as $object){
            
            $files = $this->get_files($object->id);
            
            foreach($files as $file_id){
                
                $query = $this->db->query("select * from sys_files where id=".$file_id);
                $row = $query->row();
                
                //print_r($row);
                //echo $row->file_ext;
                
                
              //  if(in_array($row->file_ext, $printable_files)){
                    
                    $objects[] = $object;
                   // break;
                    
                    
                //}
                
            }
            
        }*/
        
        return $all_objects;
        
        
        
    }


	/**
	 *
	 * @param unknown $data
	 */
	function insert_obj($data){
			
		$this->db->set('date_insert', 'now()', FALSE);
		
		$this->db->insert($this->_table_name, $data);

		return $this->db->insert_id();
		
	}


	/**
	 *
	 * @param unknown $id_file
	 */
	function delete($id_obj){

		return $this->db->delete($this->_table_name, array('id' => $id_obj));

	}

	/**
	 *
	 * @param unknown $id_file
	 */
	function get_obj_by_id($id_obj){

		$query = $this->db->get_where($this->_table_name, array('id' => $id_obj));
               
		$result = $query->result();
		if(isset($result[0])){
			return $result[0];
		}else{
			return FALSE;
		}

	}
	
	
	/**
	 * 
	 * @param unknown $id_obj - int
	 * @param unknown $files - array
	 */
	function insert_files($id_obj, $files){
		
		
		foreach($files as $file){
			
            
            if($file > 0){
            
    			$_data['id_obj']  = $id_obj;
    			$_data['id_file'] = $file;
    			
    			$this->db->insert('sys_obj_files', $_data);
            
            }
			
		}
		
	}
	
	
	
	
	function get_files($id_obj){
		
		$query = $this->db->get_where('sys_obj_files', array('id_obj' => $id_obj));
		$result = $query->result();
		
		$_files = array();
		
		foreach($result as $res){
			
			$_files[] = $res->id_file;
			
		}
		
		return $_files;
		
	}
	
	
	/**
	 * 
	 * @param unknown $id_obj
	 * @param unknown $files
	 */
	function delete_files($id_obj, $files){
		
		foreach($files as $file){
			
			$_data['id_obj'] = $id_obj;
			$_data['id_file'] = $file;
			
			$this->db->delete('sys_obj_files', $_data);
		}
		
	}
    
    
    
    function update($object_id, $data){
        
        
        foreach($data as $key => $value){
            
            $this->db->set($key, $value);
        }
        
        $this->db->set('date_updated', 'now()', FALSE);
        
        $this->db->where('id' ,$object_id);
		$this->db->update($this->_table_name);
        
    }
    
    
    
    function get_by_file($id_file){
        
        
        $query = $this->db->get_where('sys_obj_files', array('id_file' => $id_file));
        
        $result = $query->result_array();
        
        return isset($result[0]) ? $result[0]['id_obj'] : false;
        
        
    }
	






}