<?php 

class Login extends Module {

	public function __construct()
	{
		parent::__construct();

	}

	public function index(){
	   

        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == TRUE){
            redirect('dashboard');
        }
        
		$this->load->view('index/index');

	}
    
    
    
    public function do_login(){
        
        
        if($this->input->post()){
		  
          
            $post = $this->input->post();
            
			//carico X class database
			$this->load->database();
			$this->load->model('user');

			$email    = $this->input->post('email');
			$password = $this->input->post('password');
   
                 
			if($this->user->login($email, $password) == TRUE){
			 
				$user = $this->user->get_user($email);
                
                /** CHECK IF FILE SESSION ALREADY EXISTS */
                /*
                if(file_exists('/var/www/temp/background_process.json')){
                    //se esiste killo killo l'eventuale script notification in run'
                    
                    $_data = json_decode(file_get_contents('/var/www/temp/background_process.json'), TRUE);
                    shell_exec ( 'sudo kill '.$_data['not_pid']);
                    
                    /** DELETE SESSION JSON 
                    shell_exec('sudo rm -f /var/www/temp/background_process.json');
                }*/
                
                /*shell_exec('sudo rm  /var/www/temp');

                $_command_notifications = 'sudo python /var/www/recovery/python/notification.py > /dev/null  & echo $!';  
                $_output_command        = shell_exec ( $_command_notifications );
                $_notification_pid      = trim(str_replace('\n', '', $_output_command));

                
                /** WRITE SESSION TO JSON 
                file_put_contents('/var/www/temp/background_process.json', json_encode(array('not_pid'=> $_notification_pid)), FILE_USE_INCLUDE_PATH);
                */
               
                $_SESSION['first_name'] = $user->first_name;
                $_SESSION['last_name'] = $user->last_name;
                $_SESSION['email'] = $user->email;
                $_SESSION['logged_in'] = TRUE;
                $_SESSION['type'] = 'fabtotum';
                //$_SESSION['not_pid'] = $_notification_pid;
               
				redirect('dashboard');

			}else{
			 redirect('login');
			}

		}

    }


	public function out(){
	   
        /* KILL */
        //shell_exec ( 'sudo kill '.$_SESSION['not_pid']);
        
        unset( $_SESSION['email'] );
        unset( $_SESSION['logged_in'] );
        unset( $_SESSION['first_name'] );
        unset( $_SESSION['last_name'] );
        unset( $_SESSION['facebook_id'] );
        unset( $_SESSION['update_check'] );
        unset( $_SESSION['update_list'] );
        unset( $_SESSION['not_pid'] );
        
        /** DELETE SESSION JSON */
        //shell_exec('sudo rm -f /var/www/temp/background_process.json');
        
		redirect('login');
	}

}

