<?php
	function dbWrapper(){

		include("local/config.php");

		$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //input function needs this

	 	return $db;
	}

  //Insert / update localized block
    function addBlock($db, $language_id, $table_name, $entry_id, $block, $circles = null){

      $user_id = $GLOBALS['user_id'];

      if(is_array($block) && is_array($circle)){

	    /*if($entry_id == 'new'){
	    	$entry_id = '%new_entry_id%';

		    //block circles, commoners and privileges
		    $contentCircles = getCirclesBy($db, array('content_id' => $content_id), $user_id);
		    $cache['dataview'] = array_merge($cache['dataview'], $contentCircles['cache']['dataview']);
	    } else {
	    	$content_id = getContentId($db, $table_name, $entry_id);

		    //block privileges
		    $privilegesContent = getContentPrivileges($db, $content_id);
		    $cache['dataview'] = array_merge($cache['dataview'], $privilegesContent['cache']['dataview']);

		    //block circles, commoners and privileges
		    $contentCircles = getCirclesBy($db, array('content_id' => $content_id), $user_id);
		    $cache['dataview'] = array_merge($cache['dataview'], $contentCircles['cache']['dataview']);
	    }*/

	    if(($entry_id == '%new_entry_id%') || (is_numeric($entry_id) && ($privileges['max_privilege_update'] || $privileges['max_privilege_create']))){

	      	//Construct multilingual entry SQL		
				$sql_content = "INSERT INTO content_field (user_id, language_id, circle_id, field, time_updated, block) VALUES ";	

		        foreach($block AS $field => $value){
		       		if($value != false){

		       			$time = time();

		       			if(is_integer($value) === false && is_float($value) === false){
				          	$sql_content_row[] = 		"('$user_id', ".
					                        			"'$language_id', ".
					                        			"'$time', ".
					                        			"'$table_name', ".
					                        			"'$entry_id', ".
					                        			"'$field', ".
					                        			"'$value')";
		       			}

		       			$sql_insert[1][] = $field;
		       			$sql_insert[3][] = "'".$value."'";

		       			$sql_update[] = $field . " = '{$value}'";

					} else {
						$response['status_code'] = '400';
						return $response[$field] = $value;
					}
		        }
		    	$sql_content.= implode(', ', $sql_content_row) . ";";

		    //Insert entry SQL
		      	if($entry_id == '%new_entry_id%'){

			      	//SQL - Add to table
				      	$sql.= "INSERT INTO $table_name (";
	        			$sql.= implode(', ', $sql_insert[1]);
				      	$sql.= ") VALUES (";
	        			$sql.= implode(', ', $sql_entry[3]);
				      	$sql.= ");";
					
						mysqli_query($db, $sql);
						$entry_id = $db->insert_id;

					$sql_content = str_replace("%new_entry_id%", $entry_id, $sql_content);
		      	} else {
			      	$sql = "UPDATE $table_name SET " . implode(', ', $sql_update) . " WHERE id = '$entry_id'";
					mysqli_query($db, $sql);
		      	}

		    mysqli_query($db, $sql_content);

		    if($language_id != $GLOBALS['default_language_id']){ //!!! is not yet translated by hand
		    	$GLOBALS['translation_queue'][] = array('table_name' => $table, 'entry_id' => $entry_id, 'block' => $block);
		    }

		    //unsync cache setup - all changed tables and corresponding ids
	 		$cache["{$table_name}.{$table_name}_id"] = $entry_id;
		    $rand = mt_rand();
	 		$cache[$rand]['block.table_name'] = $table_name;
	 		$cache[$rand]['block.entry_id'] = $entry_id;
		    outdateCache($db, $cache);

		    if($entry_id == '%new_entry_id%'){
		    	$response = getBlock($db, $user, $table_name, $entry_id);
		    }

		    return $response;
		}
      } else {
      	return false;
      }
    }

  //Translate block from language to language with Google translate - use latest entry by default, or chosen entry
    //function googleTranslateContent($db, $user, $language_id, )

    function getBlock($db, $table_name, $entry_id, $availablePrivileges = null, $history = false, $parent_cache = false){

    	$user_id = $GLOBALS['user_id'];
	    if(!$history){
	    	$history = '0,12';
		    $add_sql = " ORDER BY time DESC LIMIT $history";
	    }
 		
 		$cache['route'] == __FUNCTION__;
  		$block = getContentTable($db, $table_name, $entry_id, true);
 		$content_id = $block['id'];
 			$cache = $block['cache'];
 			unset($block['cache']);

	    //block privileges
	    $privilegesContent = getContentPrivileges($db, $content_id);
	    $cache['dataview'] = array_merge($cache['dataview'], $privilegesContent['cache']['dataview']);

	    //block circles, commoners and privileges
	    $contentCircles = getCirclesBy($db, array('content_id' => $content_id), $user_id); //user specific block caching disabled

	    //User's privileges with this block
 		$author = null;
 		if($block['user_id'] = $user_id){
 			$author = $user_id;
 		}
	    $privileges = availablePrivileges($db, $user_id, $privilegesContent, $contentCircles, $author);

	    if($privileges['privilege_read']){

 			if(!$response = existingCache($db, $cache)){

 			    	$response = $block;

			    	$response['reflections'] = getReflections($db, array('content_id' => $content_id), true);
	    			$cache['dataview'] = array_merge($cache['dataview'], $privilegesContent['cache']['dataview']);
			    	$response['values'] = getValues($db, array('content_id' => $content_id), true);
	    			$cache['dataview'] = array_merge($cache['dataview'], $privilegesContent['cache']['dataview']);
			    	$response['keywords'] = getKeywords($db, array('content_id' => $content_id), true);
	    			$cache['dataview'] = array_merge($cache['dataview'], $privilegesContent['cache']['dataview']);

			  	  	//Iterations in a branch / language
				    $sql = "SELECT COUNT(id) as count, language_id FROM content_branch WHERE content_id = '{$content_id}' GROUP BY language_id"; //branch?
				    $result = mysqli_query($db, $sql);
				    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

				      //Count history count within a translation
				    	$response[$GLOBALS['languages'][$row['language_id']]['code']]['count'] = $row['count'];

				      //Get multilingual block & history
					    $sql = "SELECT id, user_id, time, block, googletranslate FROM block WHERE language_id = '{$row['language_id']}' AND table_name = '$table_name' AND entry_id = '$entry_id'".$add_sql;
					    $result_translation = mysqli_query($db, $sql);
					    $i = 0;
				        while($row_translation = mysqli_fetch_array($result_translation, MYSQLI_ASSOC)){
				          	if($i = 0){
				            	$response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']] = $row_translation;
				          	}
				          	$response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']]['history'][$i] = $row_translation;
				        }
				    }

				    if($parent_cache == false){
					    $cache['object'] = $response;
					    updateCache($cache);
					    unset($cache['object']);
				    }
				    $response['cache'] = $cache;

			   	} else {
			   		return false;
			   	}
			}

			return $response;
		} else {
			return array('privilege_read' => false);
		}
    }

    function getContentTable($db, $table_name, $entry_id){

  		if($user_id && $table_name && $entry_id){

     		$cache['route'] = cacheSetRoute(__FUNCTION__, func_get_args());
	    	$cache['dataview']['{$table_name}.{table_name}_id'] = $entry_id;
	    	$rand = mt_rand();
	    	$cache['dataview'][$rand]['block.table_name'] = $table_name;
	    	$cache['dataview'][$rand]['block.entry_id'] = $entry_id;

 		    $sql = "SELECT block.id, block.id, AS content_id, {$table}.user_id FROM block, {$table_name} WHERE ".
 		    	   "block.table_name = '{$table_name}' AND block.entry_id = '{$entry_id}' AND ".
 		    	   "{$table}.id = '{$entry_id}' AND {$table}.removed = 0";
			$result = mysqli_query($db, $sql);
 		    $response = mysqli_fetch_array($result, MYSQLI_ASSOC));

			$response['cache'] = $cache;
  		}
  		return $response;
    }

  	function getContentPrivileges($db, $content_id){

  		if($content_id){
 			$cache['dataview']['content_privileges.content_id'] = $content_id;

  			$sql = "SELECT * FROM content_privilege WHERE content_id = '{$content_id}'";
			$result = mysqli_query($db, $sql);
 		    $response = mysqli_fetch_array($result, MYSQLI_ASSOC));

			$response['cache'] = $cache;
  		}
  		return $response;
  	}

  //Database text is translated to English for multilingual search capabilities
    function translateToDefault($array){
      $db = $GLOBALS['db'];

      if($array['language_id'] != $GLOBALS['default_language_id']){

        foreach($array['block'] AS $key => $value){
          if($value !== false && is_numeric($value) === false){
            $sql_update[] = "$key = '".translate($value, $GLOBALS['languages'][$array['language_id']]['code'], $GLOBALS['languages'][$GLOBALS['default_language_id']]['code'])."'";
          }
        }
        $sql_update = "UPDATE $table SET ".implode(', ', $sql_update)." WHERE id = '{$array['table_id']}'";
      }
    }

    function getKeywords($db, $content_id){

    }

  //Get existing cache record
    function existingCache($db, $cache){

    	$route = $cache['route'];

    	$sql = "SELECT json_object FROM cache WHERE route = '{$route}' AND (unsynchronized > ".time()." OR unsynchronized = 0)";
	    $result = mysqli_query($db, $sql);
	    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	    	return json_decode($row['json_object']);
	    } else {
	    	return false;
	    }
    }

  //New caching request
    function updateCache($db, $cache){

    	if(isset($cache['route']) && isset($cache['dataview']) && isset($cache['object'])){

    		$route = $cache['route'];
	    	$dataview = json_encode($cache['dataview']);
	    	$object = json_encode($cache['object']);

	    	$sql = "SELECT json_object, unsynchronized FROM cache WHERE route = '{$route}')";

	    	$result = mysqli_query($db, $sql);
		    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		    	if($row['json_object'] != $object || $row['dataview'] != $dataview){
	              	$sql = "UPDATE cache SET dataview = '{$dataview}', ".
	              							"json_object = '{$object}', ".
	              							"unsynchronized = 0, ".
	              							"time = ".time()." ".
	              						"WHERE id = '{$row['id']}'";
		    	} else {
	              	$sql = "UPDATE cache SET unsynchronized = 0, time = ".time()." WHERE id = '{$row['id']}'";
	            }
		    } else {

	          	$sql = "INSERT INTO cache (time, route, dataview, json_object, unsynchronized) VALUES (".
	          							time().', '.
	          							"'{$route}', ".
	          							"'{$dataview}', ".
	          							"'{$object}', ".
	          							"0);";
		    }

		    mysqli_query($db, $sql);
		}

	    return true;
    }

    function unsyncCache($db, $unsynchronized){

    	if(is_array($unsynchronized)){
			/*
			USAGE EXAMPLE:
			User circles updated:
				$updated['user_circle.user_id'] = $user['id'];
				$updated['circle_content_0']['circle_content.table_name'] = 'reflection';
				$updated['circle_content_0']['circle_content.entry_i'] = '11';
			*/

	    	foreach($unsynchronized AS $table_field => $key){

	    		if(!is_array($key)){
		    		$array = '"'.$table_field.'":"'.$key.'"'; //...user_id = $user['id'] in an array

		    		//Any and all caches with ("user_sphere" in "route") AND (user_id = $user['id'] in "object") should be unsynchronized
		    		$condition[] = "(dataview LIKE '%{$array}%')";
	    		} else {
	    			unset($condition_and);
	    			foreach($key AS $table_field_and => $key_and){
	    			    $array_and = '"'.$table_field_and.'":"'.$key_and.'"'; //tableentry_id = $user['id'] in an array

	    				$condition_and[] = "(dataview LIKE '%{$array_and}%')";
	    			}
	    			$condition[] = '('.implode(' AND ', $condition_and).')';
	    		}
	    	}
	    	$sql = "UPDATE cache SET unsynchronized = ".time()." WHERE ".implode(' OR ', $condition); //Outdating one row at a time, with OR in between
	    	mysqli_query($db, $sql);
    	}
    }

  	function cacheSetRoute($function, $args){
    	array_pop($args);
    	return $function.'('.implode($args).')';
  	}

  	function mergeCache($merging, $merged){
  		$merging = array
  		return $merging;
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