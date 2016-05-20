<?php
	$GLOBALS['errorCodes'] = array('400');

	function errors($array){

		$errors = null;

		foreach($GLOBALS['errorCodes'] AS $code){
			if(in_array($code, $array)){
				if($errors == null){
					$errors = [];
				}
				$errors[] = $code;
			}
		}

		return $errors;
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

	function arrayAddDistinct( $rows, $stack = array() ){

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

	  	foreach($rows AS $row){

		    if(!in_array($row, $stack)){

		      $stack[] = $row;
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
		          $stack = array_merge_recursive($stack, $relation);
		        }
		      } else {
		        $stack[$table] = $id;
		      }
		    }
	 	}
	 	
	  	return $stack;
	}

	function arrayMergeDistinct( array &$array1, array &$array2 ){ //array_merge_recursive_distinct

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

		$merged = $array1;

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

		transaction(array('transaction' => $transaction));
		return $merged;
	}
?>