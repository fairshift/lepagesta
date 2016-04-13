<?php
	function array_search_path($needle, array $haystack, array $path = []) { //http://stackoverflow.com/questions/27151958/searching-for-a-value-and-returning-its-path-in-a-nested-associative-array-in-ph
	    foreach ($haystack as $key => $value) {
	        $currentPath = array_merge($path, [$key]);
	        if (is_array($value) && $result = array_search_path($needle, $value, $currentPath)) {
	            return $result;
	        } else if ($value === $needle) {
	            return $currentPath;
	        }
	    }
	    return false;
	}?>