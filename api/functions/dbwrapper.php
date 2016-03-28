<?php
	function dbWrapper($sphere){

		include("local/config.php");

		$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //input function needs this

	 	return $db;
	}

  //Localized content
    function addContent($db, $user, $language_id, $table, $entry_id, $content){

      if(is_array($content)){

      	//Construct multilingual entry SQL
	        foreach($field AS $key => $value){
	       		if($value != false && is_integer($value) === false && is_float($value) === false){
	       			$sql_entry[1][] = $key;
	       			$sql_entry[3][] = "'".$value."'";
		          	$sql_content.= "INSERT INTO content (user_id, language_id, table, entry_id, field, time, content) VALUES (".
			                        			"'$user_id', ".
			                        			"'$language_id', ".
			                        			"'$table', ".
			                        			"'$entry_id', ".
			                        			"'$field', ".
			                        			"'".time()."', ".
			                        			"'$content'); ";
				}
	        }

	    //Insert entry SQL
	      	if($entry_id == 'new'){
	      		$entry_id = "%entry_id%";

		      	//SQL - Add to table
			      	$sql_entry.= "INSERT INTO $table (";
        			$sql_entry.= implode(', ', $sql_entry[1]);
			      	$sql_entry.= ") VALUES (";
        			$sql_entry.= implode(', ', $sql_entry[3]);
			      	$sql_entry.= ");";
				
					mysqli_query($db, $sql_entry);
					$entry_id = $db->insert_id;

				$sql_content = str_replace("new", $entry_id, $sql_content);
	      	}

	    mysqli_query($db, $sql_content);

	    if($language_id != $GLOBALS['default_language_id']){
	    	$GLOBALS['translation_queue'][] = array('table' => $table, 'entry_id' => $entry_id, 'content' => $content);
	    }

	    $GLOBALS['cache_outdated_queue'][time()] = array('table' => $table, 'entry_id' => $entry_id);

	    return getContent($db, $user_id, $language_id, $table, $entry_id);
      } else {
      	return false;
      }
    }

    function getContent($db, $user, $language_id, $table, $entry_id, $history = false){

    	//Caching first...
    	$call[] = "$table=$entry_id";
    	$call[] = 
		if($response = existingCache($db, "$table=$entry_id")){
			return $response;
		} else {
    	//From original table
      		$sql = 

		    if(!$history){
		    	$history = '0,12';
		    }
		    $add_sql = " ORDER BY time DESC LIMIT $history";

		    $sql = "SELECT user_id, time, content FROM content WHERE language_id = '$language_id' AND table = '$table' AND entry_id = '$entry_id'".$add_sql;
		    $result = mysqli_query($db, $sql);
		    $i = 0;
		    if($history == true){
		        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		          	if($i = 0){
		            	$response = $row;
		          	}
		          	$response['history'][$i] = $row;
		        }
		    }

		    $sql = "SELECT COUNT(id) as count, language_id FROM content WHERE table = '$table' AND entry_id = '$entry_id' GROUP BY language_id";
		      $result = mysqli_query($db, $sql);
		      while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		      }

		      	$response['translations'] = 
		    	return $response;
		}
    }

  //Database text is translated to English for multilingual search capabilities
    function translateToDefault($array){
      $db = $GLOBALS['db'];

      if($array['language_id'] != $GLOBALS['default_language_id']){

        foreach($array['content'] AS $key => $value){
          if($value !== false && is_numeric($value) === false){
            $sql_update[] = "$key = '".translate($value, $GLOBALS['languages'][$array['language_id']]['code'], $GLOBALS['languages'][$GLOBALS['default_language_id']]['code'])."'";
          }
        }
        $sql_update = "UPDATE $table SET ".implode(', ', $sql_update[])." WHERE id = '{$array['table_id']}'";
      }
    }

  //Fetch existing cache
    function existingCache($db, $call){
    	$sql = "SELECT * FROM cache WHERE call = '$call' AND outdated > ".time();
	    $result = mysqli_query($db, $sql);
	    return $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

  //Outdated cache - a signal to renew entries on matching call beyond a timeframe
    function outdateCache($db){

    	if(count($GLOBALS['cache_queue']) > 0){
    		foreach($GLOBALS['cache_queue'] AS $outdated => $array){
    			$conditions[$outdated][] = 'call LIKE "%'.$array['table'].'='.$array['entry_id'].'%"';
    		}

    		foreach($conditions AS $outdated => $condition)
	    	    $sql = 'UPDATE cache SET outdated = '$outdated' WHERE '.implode(' OR ', $condition);
	    		mysqli_query($db, $sql);
	    	}

    		return true;
    	} else {
    		return false;
    	}
	}