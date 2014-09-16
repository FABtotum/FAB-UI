<?php

include_once('config/config.php');

function check_install(){
    
   $_install =  file_get_contents(INSTALL_FILE, FILE_USE_INCLUDE_PATH ) == 1 ? true : false ;
   
   if($_install){
        header("Location: /recovery/install");
   }else{
        header("Location: /fabui");
   }
    
}




function set_install($val){
    
    file_put_contents(INSTALL_FILE, $val);
    
}





function exec_sql_file(){
    
    
    $link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
    if (!$link) {
      die ("MySQL Connection error");
    }
     
    $database_name = 'databasename';
    mysql_select_db(DB_DATABASE, $link) or die ("Wrong MySQL Database");
     
    // read the sql file
    
   
    
    $f = fopen(SQL_INSTALL_DB,"r+");
    $sqlFile = fread($f, filesize(SQL_INSTALL_DB));
    
    $sqlArray = explode(';',$sqlFile);
    
    foreach ($sqlArray as $stmt) {
      if (strlen($stmt)>3 && substr(ltrim($stmt),0,2)!='/*') {
        
        echo $stmt.'<br>';
        
        $result = mysql_query($stmt);
        if (!$result) {
          $sqlErrorCode = mysql_errno();
          $sqlErrorText = mysql_error();
          $sqlStmt = $stmt;
          break;
        }
      }
    }
    if ($sqlErrorCode == 0) {
      echo "Script is executed succesfully!";
    } else {
      echo "An error occured during installation!<br/>";
      echo "Error code: $sqlErrorCode<br/>";
      echo "Error text: $sqlErrorText<br/>";
      echo "Statement:<br/> $sqlStmt<br/>";
    }
     
    
}



?>