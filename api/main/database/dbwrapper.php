<?php
	function dbWrapper($account){

		$db = mysqli_connect($account['database-host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //functions need this

	 	return $db;
	}

  	//Checks for content node namespace availability (within a given user/entity context). Free returns correctly formatted string, existing returns state_id
	function nodeNamespace(){

 		$db = $GLOBALS['db'];

 		$input = func_get_args()[0];
		$input['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $input['name']); //removes special characters

        if($input['name']){

			if($input['node_id']){
        		
        	}
        	if($input['user_id']){

        	}

        	//content_

			/*$sql = 	"SELECT id, node_id, branch_id, id AS state_id FROM content_state WHERE ".
		    	   	"namespace = '{$name}'";
			$result = mysqli_query($db, $sql);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC));*/

			$sql = 	"SELECT id, node_id, branch_id, id AS state_id FROM content_branch WHERE ".
		    	   	"namespace = '{$name}'";
		} else {
			return null;
		}
	}

    //As functions building data output are calling other functions to supply specific datasets, blocks are merged
	/*function mergeBlocks($needle, $block, $buffer){ //taking in $block && $buffer (where ['state'] contains data and needle )

		if($pathway_parts = array_search_path($needle, $block)){ //http://stackoverflow.com/users/567663/paul

			foreach ($pathway_parts as $part){

			   // Possibly check if $newBlock[$part] is set before doing this.
			   $newBlock = &$newBlock[$part];
			}

			if($block['transaction']){ unset($buffer['transaction']); }

			$newBlock = $buffer['state'];
			unset($buffer['state']);

			$newBlock = array_merge($block, $newBlock);
			$newBlock = array_merge($newBlock, $buffer); //merge transaction & relations
		} else {
			$newBlock = array_merge($block, $buffer);
		}

		return $newBlock;
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
	}*/

	//needle: getBranches
	//
?>