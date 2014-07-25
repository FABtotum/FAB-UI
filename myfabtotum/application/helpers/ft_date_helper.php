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

