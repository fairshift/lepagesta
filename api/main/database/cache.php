<?php
//Cache works more efficiently when table has less entries and is therefore cleared after a set amount of time, while querys stay in chain forever unmodified
	//An examplary difference of how number of entries among querychain and cache might differ (in a small database)
		//Blockchain length ------------------------------------------------------
		//Cache length	 	----

  //Get existing cache - is holding cached datasets & their states for specific functions & inputs
    function existingCache($transaction){

    	$db = $GLOBALS['db'];

    	$sql = "SELECT relations, response, nodes FROM cache WHERE transaction = '{$transaction}' AND (unsynchronized_time > ".time()." OR unsynchronized_time = 0)";
	    $result = mysqli_query($db, $sql);
	    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	    	$query['cache-relations'] = json_decode($row['relations']);
	    	$query['response'] = json_decode($row['response']);
	    	$query['nodes'] = json_decode($row['nodes']);

	    	mysqli_query($db, "UPDATE cache SET time_called = ".time().", usage_count = '".($row['usage_count'] + 1)."' WHERE id = '{$row['id']}'");

	    	return $query;
	    } else {
	    	return null;
	    }
    }

  //When datasets & their states are not cached, specific functions & inputs can store these for later reuse
    function updateCache($query){

    	$db = $GLOBALS['db'];
    	$user_id = $GLOBALS['user_id'];

    	if(isset($query['cache-relations']) && isset($query['response']) && isset($query['nodes'])){

    		$transaction = $query['transaction'];
	    	$relations = json_encode($query['cache-relations']);
	    	$response = json_encode($query['response']);
	    	$nodes = json_encode($GLOBALS['cache-nodes'][$transaction]);

	    	$timestamp = time();

	    	$sql = "SELECT relations, response, nodes, time_unsynchronized FROM cache WHERE transaction = '{$transaction}')";

	    	$result = mysqli_query($db, $sql);
		    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		    	if($row['relations'] != $relations || $row['response'] != $response || $row['nodes'] != $nodes){
	              	$sql = "UPDATE cache SET transaction = '{$transaction}', ".
	              							"relations = '{$relations}', ".
	              							"response = '{$response}', ".
	              							"nodes = '{$nodes}', ".
	              							"time_unsynchronized = 0, ".
	              							"time_updated = {$timestamp} ".
	              						"WHERE id = '{$row['id']}'";
		    	} else {
	              	if($row['time_unsynchronized'] > 0){ 
	              		$sql = "UPDATE cache SET time_unsynchronized = 0, time_updated = {$timestamp} WHERE id = '{$row['id']}'";
	              	} else {
	              		$sql = null;
	              	}
	            }
		    } else {

	          	$sql = "INSERT INTO cache (time_created, time_updated, transaction, relations, response, nodes, time_unsynchronized) VALUES (".
	          							"'{$timestamp}', ".
	          							"'{$timestamp}', ".
	          							"'{$transaction}', ".
	          							"'{$relations}', ".
	          							"'{$response}', ".
	          							"'{$nodes}', ".
	          							"0); ";
			}

			if($sql){
		    	mysqli_query($db, $sql);
			}
		}

  		$GLOBALS['nodes'] = array_merge($GLOBALS['nodes'], $GLOBALS['cache-nodes'][$transaction]);

	    return true;
    }

  //Data entries that have been modified are unsynchronized by matching dataview
    function unsyncCache($query){

    	$db = $GLOBALS['db'];
      	$unsynchronize = $query['cache-relations'];

    	if(is_array($unsynchronize)){
			/*
			USAGE EXAMPLE:
			User was included in a circle:
				$query['relations']['circle_commoner.commoner_user_id'] = $route['user_id'];

			Entity was included in a circle:
				$query['relations']['circle_commoner.commoner_entity_id'] = $route['entity_id'];

			Content was updated
				$updated['93204a0sddk3304kf0']['content.table_name'] = 'reflection';
				$updated['93204a0sddk3304kf0']['content.entry_id'] = '11';
			*/
	    	foreach($unsynchronize AS $table_field => $key){
	    		if(!is_array($key)){
		    		$array = '"'.$table_field.'":"'.$key.'"'; //
		    		//All caches with ("circle_commoner.commoner_user_id" = "{$user['id']}") should be unsynchronized
		    		$relations[] = "(relations LIKE '%{$array}%')";
	    		} else {
	    			unset($relations_and);
	    			foreach($key AS $table_field_and => $key_and){
	    			    $array_and = '"'.$table_field_and.'":"'.$key_and.'"';
	    				$relations_and[] = "(relations LIKE '%{$array_and}%')";
	    			}
	    			$relations[] = '('.implode(' AND ', $relations_and).')';
	    			//All caches with ("content.table_name" = "reflection" AND "content.entry_id" = "11") should be unsynchronized
	    		}
	    	}
	    	$sql = "UPDATE cache SET time_unsynchronized = ".time()." WHERE ".implode(' OR ', $relations); //Outdating one row at a time, with OR in between
	    	mysqli_query($db, $sql);
    	}
    }

	function mergeCache($merging, $merged){

		$merging['cache-relations'] = array_merge($merging['cache-relations'], $merged['cache-relations']);

		return $merging;
	}

	/*function globalCache($){

	}*/
?>