<?php
/**
 *
 * @return boolean
 */
function is_logged_in() {
    

    
    if(!isset($_SESSION['user']['email']) || $_SESSION['user']['email']== '' || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == ''){  
        redirect('login/out');
	} else {
		return true;
	}
}

