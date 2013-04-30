<?php

/*
* Returns a new array with the keys specified. Optionally pop 
* them from the original array, if $pop_keys parameter is true. 
*/
function array_filter_keys(&$array, $keys, $pop_keys=false) {
	$array_keys = array();
	
	foreach($keys as $key) {
		$array_keys[$key] =& $array[$key];
		if ($pop_keys)
			unset($array[$key]);
	}
	
	return $array_keys;
}
