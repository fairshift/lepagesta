<?php
//Cache works more efficiently when table has less entries and is therefore cleared after a set amount of time, while blocks stay in chain forever unmodified
	//An examplary difference of how number of entries among blockchain and cache might differ (in a small database)
		//Blockchain length ------------------------------------------------------
		//Cache length	 	----

  //Get existing cache block - is holding cached datasets & their states for specific functions & inputs
    function existingCacheBlock($transaction){

    	$db = $GLOBALS['db'];

    	$sql = "SELECT relations, state FROM cache WHERE transaction = '{$transaction}' AND (unsynchronized_time > ".time()." OR unsynchronized_time = 0)";
	    $result = mysqli_query($db, $sql);
	    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	    	$block['transaction'] = $transaction;
	    	$block['relations'] = json_decode($row['relations']);
	    	$block['state'] = json_decode($row['state']);
	    	return $block;
	    } else {
	    	return null;
	    }
    }

  //When datasets & their states are not cached, specific functions & inputs can store these for later reuse
    function updateCacheBlock($block){

    	$db = $GLOBALS['db'];
    	$user_id = $GLOBALS['user_id'];

    	if(isset($block['state']) && isset($block['cache-relations']) && isset($block['state'])){

    		$transaction = $block['transaction'];
	    	$relations = json_encode($block['cache-relations']);
	    	$state = json_encode($block['state']);

	    	$timestamp = time();

	    	$sql = "SELECT relations, state, time_unsynchronized FROM cache WHERE transaction = '{$transaction}')";

	    	$result = mysqli_query($db, $sql);
		    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		    	if($row['state'] != $state || $row['relations'] != $relations){
	              	$sql = "UPDATE cache SET transaction = '{$transaction}', ".
	              							"relations = '{$relations}', ".
	              							"state = '{$state}', ".
	              							"time_unsynchronized = 0, ".
	              							"time = {$timestamp} ".
	              						"WHERE id = '{$row['id']}'";
		    	} else {
	              	if($row['time_unsynchronized'] > 0){ 
	              		$sql = "UPDATE cache SET time_unsynchronized = 0, time = {$timestamp} WHERE id = '{$row['id']}'";
	              	} else {
	              		$sql = null;
	              	}
	            }
		    } else {

	          	$sql = "INSERT INTO cache (time, transaction, relations, state, time_unsynchronized) VALUES (".
	          							"'{$timestamp}', ".
	          							"'{$transaction}', ".
	          							"'{$relations}', ".
	          							"'{$state}', ".
	          							"0); ";
			}

			if($sql){
		    	mysqli_query($db, $sql);
			}
		}

	    return true;
    }

  //Data entries that have been modified are unsynchronized by matching dataview
    function unsyncCacheBlocks($block){

    	$db = $GLOBALS['db'];
      	$unsynchronize = $block['cache-relations'];

    	if(is_array($unsynchronize)){
			/*
			USAGE EXAMPLE:
			User was included in a circle:
				$block['relations']['circle_commoner.commoner_user_id'] = $route['user_id'];

			Entity was included in a circle:
				$block['relations']['circle_commoner.commoner_entity_id'] = $route['entity_id'];

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

		$newBlock = array_merge($merging['cache']['relations'], $merged['cache']['relations']);

		return $newBlock;
	}
?>