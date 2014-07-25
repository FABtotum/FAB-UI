<?php 



class GcodeAnalyzer {


	private $_file_path;

	private $_gcode = array();

	private $_model = array();









	/**
	 * CONSTRUCT CLASS
	*/
	public function __construct(){


		
		$this->_file_path = '/var/www/upload/gc/kit.gc';
		
		$this->load_file();
		
		$this->parse();
		
		
		//echo "construct";



	}



	/**
	 *  LOAD GCODE FILE
	 */
	public function load_file(){

		$this->_gcode = file($this->_file_path);

	}





	/**
	 *  DO PARSE
	 */
	public function parse(){
		
		
		$argChar = ''; 
		$numSlice = '' ;
		
		$sendLayer = '';
		$sendLayerZ = 0;
		$sendMultiLayer = array();
		$sendMultiLayerZ = array();
		$lastSend = 0;
		
	
		$j = 0;
		$layer = 0;
		$extrude = false;
		
		$prevRetract = array('e'=>0, 'a'=>0, 'b'=>0, 'c' => 0);
		
		$retract = 0;
		$x = 0;
		$y = 0;
		$z = 0;
		$f = 0;
		$prevZ = 0;
		$prevX = 0;
		$prevY = 0;
		$lastF = 4000;
		$prev_extrude = array('a' => '', 'b' => '', 'c'=>'', 'e' => '', 'abs' => '');
		$extrudeRelative = false;
		$volPerMM = '';
		$extruder = '';
		$dcExtrude = false;
		$assumeNonDC = false;
		
		

		
		for($i = 0; $i < count($this->_gcode); $i++ ){
			
			echo $this->_gcode[i]."<br>";
			
			$x = '';
			$y = '';
			$z = '';
			
			$volPerMM = '';
			$retract = 0;
			
			
			$extrude = false;
			$extruder = null;
			$prev_extrude["abs"] = 0;
			
			//$this->_gcode[i] = explode('/[\(;]/', $this->_gcode[i])[0];
			
			//print_r($this->_gcode[i]);
			
			//$this->_gcode[i] = $this->_gcode[i].split(/[\(;]/)[0];
			
		}
		
	
		
		
		

	}





	/**
	 *
	 */
	public function analyze(){

	}


}


