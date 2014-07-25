<?php 

class Jog extends Module {

	public function __construct()
	{
		parent::__construct();
        //FLUSH SERIAL PORT BUFFER INPUT/OUTPUT
        $this->load->helper('print_helper');
        /** IF PRINTER IS BUSY I CANT JOG  */
        if(is_printer_busy()){
            redirect('dashboard');
        }
        
	}

	public function index(){
        
        
       

        //shell_exec ('sudo python /var/www/myfabtotum/python/flush.py /dev/ttyAMA0 115200 &');

		//carico X class database
		$this->load->database();
		$this->load->model('configuration');

        //init printer
        $this->load->library('serial');
        
        $this->serial->deviceSet("/dev/ttyAMA0");
		$this->serial->confBaudRate(115200);
		$this->serial->confParity("none");
		$this->serial->confCharacterLength(8);
		$this->serial->confStopBits(1);
		$this->serial->deviceOpen();
        
        /** set extruder mode-A */
        $this->config->load('fabtotum');
        $_units = json_decode(file_get_contents($this->config->item('fabtotum_config_units')), true);
        $this->serial->sendMessage("M92 E".$_units['a']."\r\n");
		$reply = $this->serial->readPort();
		$this->serial->serialflush();
        
        
        /** set relative */
		$this->serial->sendMessage("G91 \r\n");
		$reply = $this->serial->readPort();
		$this->serial->serialflush();
        
        /** get temperature */
        $this->serial->sendMessage("M105 \r\n");
        $temperature = $this->serial->readPort();
        $this->serial->serialflush();
        
        $ext_temp = 0;
        $bed_temp = 0;
        
        if($temperature != ''){
            
            $temperature = str_replace('ok ', '', $temperature);
            $temperature = explode(' ', $temperature);
            $ext_temp    = explode(':', $temperature[0])[1];
            $bed_temp    = explode(':', $temperature[2])[1];
        }
        
        
        
        /** get position */
        $this->serial->sendMessage("M114 \r\n");
        $position = $this->serial->readPort();
        
        
        
        if($position != ''){
            $position = str_replace('ok', '', $position);
            
            $p = explode(' ', $position);
            
            $pos['planner']['x'] = str_replace('X:', '', $p[0]);
            $pos['planner']['y'] = str_replace('Y:', '', $p[1]);
            $pos['planner']['z'] = str_replace('Z:', '', $p[2]);
            $pos['planner']['e'] = str_replace('E:', '', $p[3]);
            
            $pos['stepper']['x'] = $p[6];
            $pos['stepper']['y'] = str_replace('Y:', '', $p[7]);
            $pos['stepper']['z'] = str_replace('Z:', '', $p[8]);
        }
       
        
        
		$this->serial->deviceClose();
        
        $this->configuration->save_confi_value('coordinates', 'relative');
        
        
           
        
        $data['_coordinates'] = $this->configuration->get_config_value('coordinates');
		$data['_motors']      = $this->configuration->get_config_value('motors');
        $data['_lights']      = $this->configuration->get_config_value('lights');
        
		$data['_ext_temp']    = $ext_temp;
        $data['_bed_temp']    = $bed_temp;
        $data['_position']    = $position;
        
         

		$css_in_page = $this->load->view('index/css', '', TRUE);
		$js_in_page  = $this->load->view('index/js', $data, TRUE);

		$this->layout->add_css_in_page(array('data'=> $css_in_page, 'comment' => 'JOG CSS'));
		$this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'JOG JS'));
        
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.js', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_css_file(array('src'=>'application/layout/assets/js/plugin/noUiSlider/jquery.nouislider.css', 'comment' => 'javascript for the noUISlider'));
        $this->layout->add_js_file(array('src'=> 'application/layout/assets/js/plugin/ace/src-min/ace.js', 'comment' => 'ACE EDITOR JAVASCRIPT')); 
        $this->layout->add_js_file(array('src'=>'application/layout/assets/js/plugin/knob/jquery.knob.min.js', 'comment'=>'KNOB'));


        //$this->layout->set_compress(false);

		$this->layout->view('index/index', $data);
	}
    
    
    
    
    public function setup(){
        
        $this->load->database();
		$this->load->model('configuration');
        
        
        
        
        
        $data['_unit']     = $this->configuration->get_config_value('unit');
		$data['_step']     = $this->configuration->get_config_value('step');
		$data['_feedrate'] = $this->configuration->get_config_value('feedrate');
        
        
        
        $js_in_page  = $this->load->view('setup/js', '', TRUE);
        $this->layout->add_js_in_page(array('data'=> $js_in_page, 'comment' => 'JOG JS'));
        
        
        
        $this->layout->view('setup/index', $data);
    }



	public function exec(){


		//se la chiamata è di tipo ajax allora posso fare...
		if($this->input->is_ajax_request()){
			
			
			//echo $function."<br>";
			//echo $value."<br>";
            
            $function = $this->input->post("function");
            $value = $this->input->post("value");
            
            
            
			
			
			//carico X class database
			$this->load->database();
			$this->load->model('configuration');

			$_unit     = $this->configuration->get_config_value('unit');
			$_step     = $this->configuration->get_config_value('step');
			$_feedrate = $this->configuration->get_config_value('feedrate');


			if($function != "" && $value != ""){


				$_functions["motors"]["on"]  = "M17";
				$_functions["motors"]["off"] = "M18";

				$_functions["coordinates"]["relative"] = "G91";
				$_functions["coordinates"]["absolute"] = "G90";

				$_functions["directions"]["up"]         = "G0 Y+".$_step;
				$_functions["directions"]["up-right"]   = "G0 Y+".$_step." X+".$_step;
				$_functions["directions"]["up-left"]    = "G0 Y+".$_step." X-".$_step;
				$_functions["directions"]["down"]       = "G0 Y-".$_step;
				$_functions["directions"]["down-right"] = "G0 Y-".$_step." X+".$_step;
				$_functions["directions"]["down-left"]  = "G0 Y-".$_step." X-".$_step;
				$_functions["directions"]["left"]       = "G1 X-".$_step;
				$_functions["directions"]["right"]      = "G1 X+".$_step;
                
                
                

				$_functions["directions"]["home"]       = "G0 X0 Y0 Z0";

				$_functions["rotation"] = "G90\r\nG0 E";

				$_functions["mdi"] = " ";

				$_functions["feed"] = " ";

				$_functions["unit"]["mm"]   = "G21";
				$_functions["unit"]["inch"] = "G20";

				$_functions['zero_all'] = "G92 X0 Y0 Z0 E0";
                
                $_functions['position'] = "M114";
                
                $_functions['ext-temp'] = "M104 S";
                
                $_functions['bed-temp'] = "M140 S";
                
                $_functions['get-temp'] = "M105";



				switch ($function){
					case 'rotation':
						$command_value = $_functions[$function]." ".$value;
						break;
					case 'mdi':
						$command_value = $_functions[$function].$value;
						break;
					case 'feed':
						break;
					case 'zero_all':
						$command_value = $_functions[$function];
						break;
                    case 'position':
						$command_value = $_functions[$function];
						break;
                    case 'ext-temp':
						$command_value = $_functions[$function].$value;
						break;
                    case 'bed-temp':
						$command_value = $_functions[$function].$value;
						break;
                    case 'get-temp':
						$command_value = $_functions[$function];
						break;
                    case 'zup':
						$command_value = 'GO Z+'.$value;
						break;
                    case 'zdown':
						$command_value = 'GO Z-'.$value;
						break;
                    case 'bed-align':
						$command_value = 'G90'.PHP_EOL.'G28'.PHP_EOL.'G0 Z60'.PHP_EOL.'M402'.PHP_EOL.'G29'.PHP_EOL.'G0 Z60'.PHP_EOL.'M402'.PHP_EOL.'G0 X90 Y70'.PHP_EOL.'G92 X0 Y0'; 
						break;
					default :
						$command_value = $_functions[$function][$value]; 
				}
				
				
                $command_value = $_feedrate != '' ? $command_value.' F'.$_feedrate : $command_value;
                
				//command_value.=" F".$_feedrate."";
				$command_value=str_replace("_","\r\n",$command_value);


				//echo "command send: ".$command_value." <br>";
					
				//carico la liberia per la gestione del seriale (ex. php_serial.class.php)
				$this->load->library('serial');

				//$serial = new phpSerial;
					
					
				$this->serial->deviceSet("/dev/ttyAMA0");
				$this->serial->confBaudRate(115200);
				$this->serial->confParity("none");
				$this->serial->confCharacterLength(8);
				$this->serial->confStopBits(1);
				$this->serial->deviceOpen();
				$this->serial->sendMessage($command_value."\r\n");
					
				$reply = $this->serial->readPort();

				$this->serial->serialflush();
				$this->serial->deviceClose();

			
                //$reply = str_replace('\n', '|', $reply);
                //$t = explode('|', $reply);
                
                //print_r($t);
                
                
                $_response_items['command'] = $command_value;
                $_response_items['response'] = $reply;
                
                header('Content-Type: application/json');
                echo json_encode($_response_items);
                
                

			}
		}







	}
	
	
	function save(){
		
		if($this->input->is_ajax_request()){
			
			$this->load->database();
			$this->load->model('configuration');
			
			/**
			 * Salva sul db
			 */
			foreach($this->input->post() as $key => $value){
				//db::inst()->save_configuration($key, $value);
				$this->configuration->save_confi_value($key, $value);
			}
            
            
           
			
			
		}
		
	}




}

?>