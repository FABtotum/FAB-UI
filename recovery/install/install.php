<?php

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0){


    //=========== CONFIG FILES
    include_once ('../config/config.php');
    include_once ('../utilities.php');
    

    $_first_name = $_POST['first_name'];
    $_last_name  = $_POST['last_name'];
    $_email      = $_POST['email'];
    $_password   = $_POST['password'];
    
    
    //creazione utente
    
    //inizialitizzo database
    $_command = 'sudo mysql -u '.DB_USERNAME.' -p'.DB_PASSWORD.' -h '.DB_HOSTNAME.'  < '.SQL_INSTALL_DB;
    shell_exec($_command);
    
    $database = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    
    if (mysqli_connect_errno()) {
      die("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    
    
    //cancello per sicurezza tutti gli eventuali record
    if (!mysqli_query($database,'delete from sys_user')) {
        die('Error: ' . mysqli_error($database));
    }
    
    
    
    $_sql = 'insert into sys_user (first_name, last_name, email, password) values ("'.$_first_name.'", "'.$_last_name.'", "'.$_email.'", "'.md5($_password).'")';
   
       
    //inserisco l'utnente'
    if (!mysqli_query($database,$_sql)) {
        die('Error: ' . mysqli_error($database));
    }
    
    //impostazione rete
    
    //redirect interfaccia
    set_install(0);    
    header('Location: /');
    
     
}else{
    
    echo "Access denied";
    
}


?>