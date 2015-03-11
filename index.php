<?php

$_auto_intall_file = 'AUTOINSTALL';


$_redirect = file_exists($_auto_intall_file) ? '/recovery/install' : '/fabui';


header("Location: http://".$_SERVER['SERVER_ADDR'].$_redirect);
?>
