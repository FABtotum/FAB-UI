<?php

if ( ! function_exists('mysql_to_human'))
{
	function mysql_to_human($date, $format = 'YYYY/mm/dd')
	{
		
		if($date == '')
			return '';
		
		$temp = explode(' ', $date);
		
		$date = $temp[0];
		
		$minute_hours = isset($temp[1]) ? $temp[1] : '';
		
		
		$temp_date = explode('-', $date);
		
		
		$date = $temp_date[2].'/'.$temp_date[1].'/'.$temp_date[0];
		
		
		if(isset($temp[1]) && $temp[1] != ''){
			$date .= ' '.$temp[1];
		}
		
		return $date;
		
	}
}



if ( ! function_exists('elapsed_time')){
    
    
    function elapsed_time($date){
       
        
       $time = time() - strtotime($date);
        
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
    
    
        
    
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
        }
        
        
    }
    
}

