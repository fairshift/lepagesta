<?php
	function dbWrapper(){

		include("local/config.php");

		$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //input function needs this

	 	return $db;
	}

  //Insert / update localized content
    function addContent($db, $user, $language_id, $table, $entry_id, $content){

      if(is_array($content)){

	      if($entry_id == 'new'){
	      	$entry_id = '%new_entry_id%';
	      }

      	//Construct multilingual entry SQL
	        foreach($content AS $field => $value){
	       		if($value != false && is_integer($value) === false && is_float($value) === false){

	       			$sql_insert[1][] = $field;
	       			$sql_insert[3][] = "'".$value."'";

	       			$sql_update[] = $field . " = '{$value}'";

		          	$sql_content = "INSERT INTO content (user_id, language_id, table, entry_id, field, time, content) VALUES ";	
		          	$sql_content_row[] = 		"('$user_id', ".
			                        			"'$language_id', ".
			                        			"'".time()."', ".
			                        			"'$table', ".
			                        			"'$entry_id', ".
			                        			"'$field', ".
			                        			"'$value')";
				} else {
					$response['status_code'] = '400';
					return $response[$field] = $value;
				}
	        }
	    	$sql_content.= implode(', ', $sql_content_row) . ";";

	    //Insert entry SQL
	      	if($entry_id == '%new_entry_id%'){

		      	//SQL - Add to table
			      	$sql.= "INSERT INTO $table (";
        			$sql.= implode(', ', $sql_insert[1]);
			      	$sql.= ") VALUES (";
        			$sql.= implode(', ', $sql_entry[3]);
			      	$sql.= ");";
				
					mysqli_query($db, $sql);
					$entry_id = $db->insert_id;

				$sql_content = str_replace("%new_entry_id%", $entry_id, $sql_content);
	      	} else {
		      	$sql = "UPDATE $table SET " . implode(', ', $sql_update) . " WHERE id = '$entry_id'";
				mysqli_query($db, $sql);
	      	}

	    mysqli_query($db, $sql_content);

	    if($language_id != $GLOBALS['default_language_id']){
	    	$GLOBALS['translation_queue'][] = array('table' => $table, 'entry_id' => $entry_id, 'content' => $content);
	    }

	    $response = getContent($db, $user_id, $language_id, $table, $entry_id, true);

	    //Set caching
	    $cache['route']['content']['entry_id'] = $entry_id;
	    $cache['route']['content']['table'] = $table;
	    $cache['structure']['table'] = '0';
	    $cache['structure']['translation'] = '*';
	    $cache['structure']['translation']['count'] = '*';
	    $cache['response'] = $response;
	    uncacheQueue($db, $cache);

	    return $response;
      } else {
      	return false;
      }
    }

  //Translate content from language to language with Google translate
    //function googleTranslateContent($db, $user, $language_id, )

    function getContent($db, $user, $table, $entry_id, $translation_only = false, $history = false){

	    if(!$history){
	    	$history = '0,12';
		    $add_sql = " ORDER BY time DESC LIMIT $history";
	    }

    	//Setup & check cache
	    	$cache['route']['content']['entry_id'] = $entry_id;
	    	$cache['route']['content']['table'] = $table;
		    if($translation_only == false){ 
		    	$cache['structure']['table'] = '*';
		    }
		    $cache['structure']['translation'] = '*';
		    $cache['structure']['translation']['count'] = '*';
		    $cache['structure']['translation']['history'] = $history;

			if($response = existingCache($db, $cache)){
				return $response;
			}

	  	//Get selected table
		$sql = "SELECT *, *.id AS {$table}_id FROM '{$table}' WHERE id = '{$entry_id}' AND removed = 0";
	    $result = mysqli_query($db, $sql);
	    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if($row){

			if($translation_only == false){
				$response = $row;
			}

	  	  	//Translations count
		    $sql = "SELECT COUNT(id) as count, language_id FROM content WHERE table = '$table' AND entry_id = '$entry_id' GROUP BY language_id";
		    $result = mysqli_query($db, $sql);
		    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		      //Count history count within a translation
		    	$response[$GLOBALS['languages'][$row['language_id']]['code']]['count'] = $row['count'];

		      //Get multilingual content & history
			    $sql = "SELECT id, user_id, time, content, googletranslate FROM content WHERE language_id = '{$row['language_id']}' AND table = '$table' AND entry_id = '$entry_id'".$add_sql;
			    $result_translation = mysqli_query($db, $sql);
			    $i = 0;
		        while($row_translation = mysqli_fetch_array($result_translation, MYSQLI_ASSOC)){
		          	if($i = 0){
		            	$response[$GLOBALS['languages'][$language_id]['code']][$row_translation['field']] = $row_translation['content'];
		          	}
		          	$response[$GLOBALS['languages'][$language_id]['code']][$row_translation['field']]['history'][$i] = $row_translation['content'];
		        }
		    }

		    $cache['object'] = $response;
		    updateCache($cache);

	   		return $response;
	   	} else {
	   		return false;
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
        $sql_update = "UPDATE $table SET ".implode(', ', $sql_update)." WHERE id = '{$array['table_id']}'";
      }
    }

  //Get existing cache record
    function existingCache($db, $cache){

    	$route = json_encode($cache['route']);
    	$structure = json_encode($cache['structure']);

    	$sql = "SELECT json_object FROM cache WHERE route = '{$route}' AND structure = '{$structure}' AND (outdated > ".time()." OR outdated = 0)";
    	echo $sql;
	    $result = mysqli_query($db, $sql);
	    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	    	return $row['json_object'];
	    } else {
	    	return false;
	    }
    }

  //New caching request & undo cache entries with this item
    function cacheUpdate($db, $cache){

    	//Check if route array pairs match any cached routes and objects and mark as outdated
    	foreach($cache['route'] AS $key => $array){
    		if(!is_array($array)){
    			$buffer[$key] = $array;
    		}
    		$condition['route'] = "route LIKE '%".json_encode($array)."%'";
    		$condition['object'] = "object LIKE '%".json_encode($array)."%'";
    	}
    	$sql = "UPDATE cache SET outdated = ".time()." WHERE ".implode(' OR ', $condition);
    	mysqli_query($db, $sql);

		$route = json_encode($cache['route']);
    	$structure = json_encode($cache['structure']);
    	$object = json_encode($cache['object']);

    	$sql = "SELECT json_object, outdated FROM cache WHERE route = '{$route}' AND structure = '{$structure}'";
    	$result = mysqli_query($db, $sql);
	    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

	    	if($row['json_object'] != $object){
              	$sql = "UPDATE cache SET route = '{$route}', ".
              							"structure = '{$structure}', ".
              							"json_object = '{$object}', ".
              							"outdated = 0 ".
              						"WHERE id = '{$row['id']}'";
	    	} else {
              	$sql = "UPDATE cache SET outdated = 0 WHERE id = '{$row['id']}'";
            }
	    } else {

          	$sql = "INSERT INTO cache (time, route, structure, json_object, outdated) VALUES (".
          							time().', '.
          							"'{$route}', ".
          							"'{$structure}', ".
          							"'{$object}', ".
          							"outdated = 0);";
	    }

	    mysqli_query($db, $sql);

	    return true;
    }

  //Logging function
  	function siteLog($db, $user, $site_id, $log = ''){
	  	$route = ($GLOBALS['f']) ? $GLOBALS['o'] . '-' . $GLOBALS['f'] : $GLOBALS['o'];
	    $sql = "INSERT INTO log (user_id, site_id, time, route, log) VALUES (".
	                "'{$user['id']}', ".
	                "'$site_id', ".
	                "'".time()."', ".
	                "'$route', ".
	                "'{$GLOBALS['log']}');";
	    mysqli_query($db, $sql);
  	}