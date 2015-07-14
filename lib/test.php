<?php

session_start();


foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}

		session_destroy(); 

?>