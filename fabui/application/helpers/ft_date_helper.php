<?php

if (!function_exists('mysql_to_human')) {
	function mysql_to_human($date, $format = 'YYYY/mm/dd') {

		if ($date == '')
			return '';

		$temp = explode(' ', $date);

		$date = $temp[0];

		$minute_hours = isset($temp[1]) ? $temp[1] : '';

		$temp_date = explode('-', $date);

		$date = $temp_date[2] . '/' . $temp_date[1] . '/' . $temp_date[0];

		if (isset($temp[1]) && $temp[1] != '') {
			$date .= ' ' . $temp[1];
		}

		return $date;

	}

}

if (!function_exists('elapsed_time')) {

	function elapsed_time($date) {

		$time = time() - strtotime($date);

		$tokens = array(31536000 => 'year', 2592000 => 'month', 604800 => 'week', 86400 => 'day', 3600 => 'hour', 60 => 'minute', 1 => 'second');

		foreach ($tokens as $unit => $text) {
			if ($time < $unit)
				continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
		}

	}

}

function get_time_past($created_date) {

	$differences = dateDiff(time(), $created_date);

	if (isset($differences["year"]) || isset($differences["years"])) {

		if (isset($differences["year"]))
			return $differences["year"] . " year";

		if (isset($differences["years"]))
			return $differences["years"] . " years";
	}

	if (isset($differences["month"]) || isset($differences["months"])) {

		if (isset($differences["month"]))
			return $differences["month"] . " month";

		if (isset($differences["months"]))
			return $differences["months"] . " months";
	}

	if (isset($differences["day"]) || isset($differences["days"])) {

		if (isset($differences["day"]))
			return $differences["day"] . " day";

		if (isset($differences["days"]))
			return $differences["days"] . " days";
	}

	if (isset($differences["hour"]) || isset($differences["hours"])) {

		if (isset($differences["hour"]))
			return $differences["hour"] . " hr";

		if (isset($differences["hours"]))
			return $differences["hours"] . " hrs";
	}

	if (isset($differences["minute"]) || isset($differences["minutes"])) {

		if (isset($differences["minute"]))
			return $differences["minute"] . " min";

		if (isset($differences["minutes"]))
			return $differences["minutes"] . " min";
	}

	if (isset($differences["second"]) || isset($differences["seconds"])) {

		if (isset($differences["second"]))
			return $differences["second"] . " second";

		if (isset($differences["seconds"]))
			return $differences["seconds"] . " seconds";
	}

}

// Time format is UNIX timestamp or
// PHP strtotime compatible strings
function dateDiff($time1, $time2, $precision = 6) {
	// If not numeric then convert texts to unix timestamps
	if (!is_int($time1)) {
		$time1 = strtotime($time1);
	}
	if (!is_int($time2)) {
		$time2 = strtotime($time2);
	}

	// If time1 is bigger than time2
	// Then swap time1 and time2
	if ($time1 > $time2) {
		$ttime = $time1;
		$time1 = $time2;
		$time2 = $ttime;
	}

	// Set up intervals and diffs arrays
	$intervals = array('year', 'month', 'day', 'hour', 'minute', 'second');
	$diffs = array();

	// Loop thru all intervals
	foreach ($intervals as $interval) {
		// Create temp time from time1 and interval
		$ttime = strtotime('+1 ' . $interval, $time1);
		// Set initial values
		$add = 1;
		$looped = 0;
		// Loop until temp time is smaller than time2
		while ($time2 >= $ttime) {
			// Create new temp time from time1 and interval
			$add++;
			$ttime = strtotime("+" . $add . " " . $interval, $time1);
			$looped++;
		}

		$time1 = strtotime("+" . $looped . " " . $interval, $time1);
		$diffs[$interval] = $looped;
	}

	$count = 0;
	$times = array();
	// Loop thru all diffs
	foreach ($diffs as $interval => $value) {
		// Break if we have needed precission
		if ($count >= $precision) {
			break;
		}
		// Add value and interval
		// if value is bigger than 0
		if ($value > 0) {
			// Add s if value is not 1
			if ($value != 1) {
				$interval .= "s";
			}
			// Add value and interval to times array
			//$times[] = $value . " " . $interval;
			$times[$interval] = $value;
			$count++;
		}
	}

	// Return string with times
	return $times;
}

function seconds_to_time($seconds) {
	$dtF = new DateTime("@0");
	$dtT = new DateTime("@$seconds");
	return $dtF -> diff($dtT) -> format('%ad %hh %im');
}

function date_to_mysql($date, $time = FALSE, $separator = "/") {

	$date = explode(' ', $date);

	$temp = explode($separator, $date[0]);

	$return = $temp[2] . "-" . $temp[1] . "-" . $temp[0];

	if ($time == TRUE) {
		$return .= ' ' . $date[1];
	}

	return $return;
}
