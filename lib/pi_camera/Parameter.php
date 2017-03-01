<?php


class Parameter {
	
	private $_name        = '';
	private $_code        = '';
	private $_description = '';
	private $_value       = '';
	
	
	
	public function __construct($name = '', $code = '', $description = '', $value = '') {
			
			$this->_name = $name;
			$this->_code = $code;
			$this->_description = $description;
			$this->_value = $value;
			
	}
	
	
	public function setValue($value){
		$this->_value = $value;
	}
	
	public function getValue(){
		return $this->_value;
	}
	
	
	public function getName(){
		return $this->_name;
	}
	
	public function getCode(){
		return $this->_code;
	}
	
	public function getDescription(){
		return $this->_description;
	}
	

}

?>