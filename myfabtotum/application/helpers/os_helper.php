<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('installed_plugins'))
{
	
	
	
	function exist_process($pid){
		
        //echo $pid;
		$cmd = 'sudo ps '. $pid;
        
        //echo '>>'.posix_getsid($pid);
		
		// run the system command and assign output to a variable ($output)
		exec($cmd, $output, $result);
        
        //$res = shell_exec($cmd);
        
        //print_r($res);
		
		// check the number of lines that were returned
		if(count($output) >= 2){
		
			// the process is still alive
			return true;
		}
		
		// the process is dead
		return false;
		
	}
    
    
 }
    
    
    
    
    /**
     *  
     * Search a string in a file using GREP
     * return TRUE if the string is present
     * 
     */
function search($string, $file){
        
        $_command = 'grep '.$string.' '.$file;
        
        $_output = shell_exec($_command);
        
        return strlen($_output) > 0 ? true : false;
        
    }
    
    
    
    /**
     * 
     * SCAN WIFI NETWORKS
     *  
     */ 
 function scan_wlan(){
        
        
    $_wlan_list = array();

	$_scan_result = shell_exec("sudo iwlist wlan0 scan");
	
	$_wlan_device = array();
	
	$_scan_result = explode( "\n", $_scan_result);
	
	$device = $cell = "";
	
	foreach($_scan_result as $zeile){
		
		if(substr( $zeile, 0, 1 ) != ' '){
			$device = substr($zeile, 0, strpos($zeile, ' '));
		}
		else{
			
			$zeile = trim($zeile);
			
			if(substr($zeile, 0, 5) == 'Cell '){
				$cell = (int)substr($zeile, 5, 2);
				$_wlan_device[$device][$cell] = array();
				$doppelp_pos = strpos($zeile, ':');
				$_wlan_device[$device][$cell]['address'] =
				trim(substr($zeile, $doppelp_pos + 1));
			}
			elseif(substr($zeile, 0, 8) == 'Quality='){
				$first_eq_pos = strpos($zeile, '=');
				$last_eq_pos = strrpos($zeile, '=');
				$slash_pos = strpos($zeile, '/') - $first_eq_pos;
				$_wlan_device[$device][$cell]['quality'] = trim(substr($zeile, $first_eq_pos + 1, $slash_pos - 1));
				$_wlan_device[$device][$cell]['signal_level'] = str_replace('/100', '', trim(substr($zeile, $last_eq_pos + 1)));
			}
			else{
				$doppelp_pos = strpos($zeile, ':');
				$feld = trim( substr( $zeile, 0, $doppelp_pos ) );
				if(!empty($_wlan_device[$device][$cell][strtolower($feld)]))
					$_wlan_device[$device][$cell][strtolower($feld)] .= "\n";
				// Leer- und "-Zeichen rausschmeissen - ESSID steht immer in ""
				@$_wlan_device[$device][$cell][strtolower($feld)] .= trim(str_replace('"', '', substr($zeile, $doppelp_pos + 1)));
			}
			
			
				
		}
	}
	
	
	
	foreach($_wlan_device['wlan0'] as $wlan){
		array_push($_wlan_list, $wlan);
	}
	return $_wlan_list;

}





function lan(){
    
    
    $_ethernet_result = shell_exec("sudo ifconfig eth0");
	
	$interfaces = array();
	
	foreach (preg_split("/\n\n/", $_ethernet_result) as $int) {
	
		preg_match("/^([A-z]*\d)\s+Link\s+encap:([A-z]*)\s+HWaddr\s+([A-z0-9:]*).*" .
				"inet addr:([0-9.]+).*Bcast:([0-9.]+).*Mask:([0-9.]+).*" .
				"MTU:([0-9.]+).*Metric:([0-9.]+).*" .
				"RX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*frame:([0-9.]+).*" .
				"TX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*carrier:([0-9.]+).*" .
				"RX bytes:([0-9.]+).*\((.*)\).*TX bytes:([0-9.]+).*\((.*)\)" .
				"/ims", $int, $regex);
	
		if (!empty($regex)) {
	
			$interface = array();
			
			$interface['name']      = trim($regex[1]);
			$interface['type']      = trim($regex[2]);
			$interface['mac']       = trim($regex[3]);
			$interface['ip']        = trim($regex[4]);
			$interface['broadcast'] = trim($regex[5]);
			$interface['netmask']   = trim($regex[6]);
			$interface['mtu']       = trim($regex[7]);
			$interface['metric']    = trim($regex[8]);
	
			$interface['rx']['packets']  = (int) $regex[9];
			$interface['rx']['errors']   = (int) $regex[10];
			$interface['rx']['dropped']  = (int) $regex[11];
			$interface['rx']['overruns'] = (int) $regex[12];
			$interface['rx']['frame']    = (int) $regex[13];
			$interface['rx']['bytes']    = (int) $regex[19];
			$interface['rx']['hbytes']   = (int) $regex[20];
	
			$interface['tx']['packets']  = (int) $regex[14];
			$interface['tx']['errors']   = (int) $regex[15];
			$interface['tx']['dropped']  = (int) $regex[16];
			$interface['tx']['overruns'] = (int) $regex[17];
			$interface['tx']['carrier']  = (int) $regex[18];
			$interface['tx']['bytes']    = (int) $regex[21];
			$interface['tx']['hbytes']   = (int) $regex[22];
	
			$interfaces[] = $interface;
		}
	}
	
	
	return count($interfaces) == 1 ? $interfaces[0]: $interfaces;
}





function wlan(){
    
    
    /*
    $_wlan_result = str_replace('wlan0     ', '', shell_exec("sudo iwconfig wlan0"));
    
    $temp = explode(PHP_EOL,$_wlan_result);
    
    $p = "";
    
    foreach($temp as $t){
        
        $p.=trim($t);
        
    }
    
    
    print_r(explode('  ', $p));
    
    
    
    //echo $_wlan_result;*/
    
    
    
    $_ethernet_result = shell_exec("sudo ifconfig wlan0");
	
	$interfaces = array();
	
	foreach (preg_split("/\n\n/", $_ethernet_result) as $int) {
	
		preg_match("/^([A-z]*\d)\s+Link\s+encap:([A-z]*)\s+HWaddr\s+([A-z0-9:]*).*" .
				"inet addr:([0-9.]+).*Bcast:([0-9.]+).*Mask:([0-9.]+).*" .
				"MTU:([0-9.]+).*Metric:([0-9.]+).*" .
				"RX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*frame:([0-9.]+).*" .
				"TX packets:([0-9.]+).*errors:([0-9.]+).*dropped:([0-9.]+).*overruns:([0-9.]+).*carrier:([0-9.]+).*" .
				"RX bytes:([0-9.]+).*\((.*)\).*TX bytes:([0-9.]+).*\((.*)\)" .
				"/ims", $int, $regex);
	
		if (!empty($regex)) {
	
			$interface = array();
			
			$interface['name']      = trim($regex[1]);
			$interface['type']      = trim($regex[2]);
			$interface['mac']       = trim($regex[3]);
			$interface['ip']        = trim($regex[4]);
			$interface['broadcast'] = trim($regex[5]);
			$interface['netmask']   = trim($regex[6]);
			$interface['mtu']       = trim($regex[7]);
			$interface['metric']    = trim($regex[8]);
	
			$interface['rx']['packets']  = (int) $regex[9];
			$interface['rx']['errors']   = (int) $regex[10];
			$interface['rx']['dropped']  = (int) $regex[11];
			$interface['rx']['overruns'] = (int) $regex[12];
			$interface['rx']['frame']    = (int) $regex[13];
			$interface['rx']['bytes']    = (int) $regex[19];
			$interface['rx']['hbytes']   = (int) $regex[20];
	
			$interface['tx']['packets']  = (int) $regex[14];
			$interface['tx']['errors']   = (int) $regex[15];
			$interface['tx']['dropped']  = (int) $regex[16];
			$interface['tx']['overruns'] = (int) $regex[17];
			$interface['tx']['carrier']  = (int) $regex[18];
			$interface['tx']['bytes']    = (int) $regex[21];
			$interface['tx']['hbytes']   = (int) $regex[22];
	
			$interfaces[] = $interface;
		}
	}
	
	
	return count($interfaces) == 1 ? $interfaces[0]: $interfaces;
    

}