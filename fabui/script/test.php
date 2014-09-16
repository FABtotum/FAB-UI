<?php 
require_once '/var/www/fabui/ajax/config.php';
require_once '/var/www/fabui/ajax/lib/database.php';
require_once '/var/www/fabui/ajax/lib/utilities.php';

/** CHECK IF EXIST FOLDER SQL */
		
		
		
		
			$dir = "/var/www/test/sql/*";

			
			foreach(glob($dir) as $file_sql) 
			{
				/** EXEC SQL FILES */
				if(file_exists($file_sql)){
					
					
					//echo $sql;
					
					$_exec_sql = 'sudo mysql -u '.DB_USERNAME.' -p'.DB_PASSWORD.' -h '.DB_HOSTNAME.'  < '.$file_sql;
					//shell_exec($_exec_sql);
					
					echo $_exec_sql;
					
					
	
				}
			}
        



?>