<?php
//remote updater
//error_reporting(E_ALL);


function getfile($file_path,$url){
	if(file_put_contents($file_path,file_get_contents($repo_url))){
		//file_put_contents($strFilePath."file.zip",fopen($strBlobURL,"r")or die("error retrieving file"));
		return 1;
	}else{
		return 0;
	}
}

function post_redir($nextstep,$timeout){
	echo'<script>setTimeout(function() { window.location.href = "updater.php?step='.$nextstep.'";}, '.$timeout.');</script>';
}
		
$client_version=0;	
$step=intval($_GET['step']);
//echo $step;

//step
if($step==0){   
	ob_end_flush();
	//stage 0: verify version.txt
	$remote_file="http://update.fabtotum.com/FAB-UI/version.txt";
	if (false === file_get_contents($remote_file,0,null,0,1)) {
		echo "<div align=center>Check for updates failed. Check your internet connection.</div>";
		}else{
			//get file content
			$version=file_get_contents($remote_file);
			if($version>$client_version){
				echo "<div align=center><h3>Software Update Available</h3>Version".$version." is available.<br>Software is now loading<br><img src=loading.gif></div>";
				
					echo'<script>setTimeout(function() { window.location.href = "test_flush.php?";}, 3000);</script>';
				//redir to next step.
				//post_redir("updater.php?",3000);
				//sleep(5);
				//header("location: http://".$_SERVER['SERVER_ADDR']."/recovery/updater.php?step=1");
			}
			
		}
	}


if($step==1){ 

}

if($step==1){ 
//verify with MD5

//unzip

//rename folders

//update local client version to match remote version

//head to new interface

}


?>