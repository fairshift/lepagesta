<?php

	function arrayAddDistinct( $rows, $stack = array() ){

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

		if(!is_array($rows)){
			if(!in_array($row, $stack)){
				$stack[] = $rows;
			}
		}

		if(is_numeric(key($rows))){
		  	foreach($rows AS $key => $row){

		  		if(is_numeric($key)){
				    if(!in_array($row, $stack)){

				      $stack[] = $row;
				    }

		  		} else {

		  			$stack[] = $rows;
		  		}
		  	}
	  	} else {
		    if(!in_array($rows, $stack)){

		      $stack[] = $rows;
		    }  		
	  	}

		transaction(array('transaction' => $transaction));
	  	return $stack;
	}

	function arrayAddRecursive( $rows, $stack = array() ){

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

	  	foreach($rows AS $row){
		    foreach($row AS $key => $value){

		      if($stack[$key]){
		        if(!in_array($value, $stack[$key]) && $stack[$key] != $value){
		          $stack = array_merge_recursive($stack, $value);
		        }
		      } else {
		        $stack[$key] = $value;
		      }
		    }
	 	}
	 	
	  	return $stack;
	}

	function arrayMergeDistinct( $array1, $array2 ){ //array_merge_recursive_distinct

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

		$merged = $array1;

		if(is_array($array1) && is_array($array2)){
			foreach ( $array2 as $key => &$value )
			{
			    if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			    {
			      $merged [$key] = arrayMergeDistinct ( $merged [$key], $value );
			    }
			    else
			    {
			      $merged [$key] = $value;
			    }
			}
		} elseif(is_array($array1) && !is_array($array2)){
			return $array1;

		} elseif(is_array($array2) && !is_array($array1)){
			return $array2;

		} else {
			return null;
		}

		transaction(array('transaction' => $transaction));
		return $merged;
	}

	function uncompress($array){
		//This function will uncompress data keys (as these are taking most of the space)
		return $array;
	}

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
	}

?>