<?php
	function dbWrapper(){

		include("local/config.php");

		$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //input function needs this

	 	return $db;
	}

  //Insert / update localized content
    function addContent($language_id, $table_name, $entry_id, $content, $circles = null){

 	  $db = $GLOBALS['db'];
      $user_id = $GLOBALS['user_id'];

      if(is_array($content) && is_array($circle)){

	    /*if($entry_id == 'new'){
	    	$entry_id = '%new_entry_id%';

		    //Content circles, commoners and privileges
		    $contentCircles = getCirclesBy($db, array('content_id' => $content_id), $user_id);
		    $block['dataview'] = array_merge($block['dataview'], $contentCircles['block']['dataview']);
	    } else {
	    	$content_id = getContentId($db, $table_name, $entry_id);

		    //Content privileges
		    $privilegesContent = getContentPrivileges($db, $content_id);
		    $block['dataview'] = array_merge($block['dataview'], $privilegesContent['block']['dataview']);

		    //Content circles, commoners and privileges
		    $contentCircles = getCirclesBy($db, array('content_id' => $content_id), $user_id);
		    $block['dataview'] = array_merge($block['dataview'], $contentCircles['block']['dataview']);
	    }*/

	    if(($entry_id == '%new_entry_id%') || (is_numeric($entry_id) && ($privileges['max_privilege_update'] || $privileges['max_privilege_create']))){

	      	//Construct multilingual entry SQL		
				$sql_content = "INSERT INTO content_field (user_id, language_id, circle_id, field, time_updated, content) VALUES ";	

		        foreach($content AS $field => $value){
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
		    	$GLOBALS['translation_queue'][] = array('table_name' => $table, 'entry_id' => $entry_id, 'content' => $content);
		    }

		    //unsync block setup - all changed tables and corresponding ids
	 		$block["{$table_name}.{$table_name}_id"] = $entry_id;
		    $rand = mt_rand();
	 		$block[$rand]['content.table_name'] = $table_name;
	 		$block[$rand]['content.entry_id'] = $entry_id;
		    outdateCache($db, $block);

		    if($entry_id == '%new_entry_id%'){
		    	$response = getContent($db, $user, $table_name, $entry_id);
		    }

		    return $response;
		}
      } else {
      	return false;
      }
    }

  //Translate content from language to language with Google translate - use latest entry by default, or chosen entry
    //function googleTranslateContent($db, $user, $language_id, )

    function getContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = renderInput(func_get_args());

        $input['table_name'] = 				(!isset($input['table_name'])) ? null : $input['table_name'];
        $input['entry_id'] = 				(!isset($input['entry_id'])) ? null : $input['entry_id'];

        //$input['availablePrivileges'] = 	(!isset($input['availablePrivileges'])) ? null : $input['availablePrivileges'];
        $input['history'] = 				(!isset($input['history'])) ? '0,12' : $input['history'];

        $block = 							(!isset($input['block'])) ? null : $input['block'];
    	$block['time'] = 					(!$input['block']) ? microtime() : $input['block']['time'];
    	$block['transaction'] = 			(!$input['block']) ? formatTransaction(__FUNCTION__, $input) : $input['bock']['transaction'];

    //Start code
    	if($user_id && $input['table_name'] && $input['entry_id']){
	  		$content = getContentTable($input, array('block', $block);
	 		$input['content_id'] = $content['id'];
			$block = $content['block'];
			unset($content['block']);

		    //Content privileges
		    $privilegesContent = getContentPrivileges($input, array('block' => $block));
		    $block = $privilegesContent['block'];
		    unset($privilegesContent['block']);

		    //Content circles, commoners and privileges
		    $input['user_id'] = $user_id;
		    $contentCircles = getCirclesBy($input); //user specific content caching is disabled at level of this block

		    //User's privileges with this content
	 		$author = null;
	 		if($content['user_id'] = $user_id){
	 			$author = $user_id;
	 		}
		    $privileges = availablePrivileges($db, $user_id, $privilegesContent, $contentCircles, $author);

		    if($privileges['privilege_read']){

	 			if(!$response = existingCache($db, $block)){

	 			    	$response = $content;

				    	$response['reflections'] = getReflections($input, array('block' => $block));
		    			$block['dataview'] = mergeBlocks($block['dataview'], $response['reflections']);

				    	$response['values'] = getValues($input, array('block' => $block));
		    			$block['dataview'] = mergeBlocks($block['dataview'], $response['values']);

				    	$response['keywords'] = getKeywords($input, array('block' => $block));
		    			$block['dataview'] = mergeBlocks($block['dataview'], $privilegesContent['block']['dataview']);

				  	  	//Iterations count
					    $sql = "SELECT COUNT(id) as count, language_id FROM content_branch WHERE content_id = '{$content_id}' GROUP BY language_id"; //branch?
					    $result = mysqli_query($db, $sql);
					    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

					      //Count history count within a translation
					    	$response[$GLOBALS['languages'][$row['language_id']]['code']]['count'] = $row['count'];

					      //Get multilingual content & history
						    $sql = "SELECT id, user_id, time, content, googletranslated FROM content WHERE language_id = '{$row['language_id']}' AND table_name = '$table_name' AND entry_id = '$entry_id'".$add_sql;
						    $result_translation = mysqli_query($db, $sql);
						    $i = 0;
					        while($row_translation = mysqli_fetch_array($result_translation, MYSQLI_ASSOC)){
					          	if($i = 0){
					            	$response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']] = $row_translation;
					          	}
					          	$response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']]['history'][$i] = $row_translation;
					        }
					    }

				   	} else {
				   		return false;
				   	}
				}
			}

		    if($merging_block == null){
			    $block['state'] = $response;
			    newCacheBlock($block);
			    unset($block['state']);
		    }
		    $response['block'] = $block;
			$block['timer'] = microtime() - $block['timer'];

			return $response;
		} else {
			return array('privilege_read' => false);
		}
    }

    function getContentTable(){

    	$db = $GLOBALS['db'];
    	$user_id = $GLOBALS['user_id'];

    	//table_name, entry_id
      	$input = renderInput(func_get_args());

  		if($user_id && $table_name && $entry_id){

     		$block['transaction'] = blockFormatTransaction(__FUNCTION__, array_values(func_get_args()));
	    	$block['dataview']['{$table_name}.{table_name}_id'] = $entry_id;
	    	$rand = mt_rand();
	    	$block['dataview'][$rand]['content.table_name'] = $table_name;
	    	$block['dataview'][$rand]['content.entry_id'] = $entry_id;

 		    $sql = "SELECT content.id, content.id, AS content_id, {$table}.user_id FROM content, {$table_name} WHERE ".
 		    	   "content.table_name = '{$table_name}' AND content.entry_id = '{$entry_id}' AND ".
 		    	   "{$table}.id = '{$entry_id}' AND {$table}.removed = 0";
			$result = mysqli_query($db, $sql);
 		    $response = mysqli_fetch_array($result, MYSQLI_ASSOC));

			$response['block'] = $block;
  		}
  		return $response;
    }

  	function getContentPrivileges(){

      	$db = $GLOBALS['db'];
      	$input = renderInput(func_get_args());

  		if($input['content_id']){
 			$block['dataview']['content_privilege.content_id'] = $input['content_id'];

  			$sql = "SELECT * FROM content_privilege WHERE content_id = '{$input['content_id']}'";
			$result = mysqli_query($db, $sql);
 		    $response = mysqli_fetch_array($result, MYSQLI_ASSOC));

			$response['block'] = $block;
  		}
  		return $response;
  	}

  //Database text is translated to English for multilingual search capabilities
    function translateToDefault(){

      $db = $GLOBALS['db'];
      $input = renderInput(func_get_args());

      if($input['language_id'] != $GLOBALS['default_language_id']){

        foreach($array['content'] AS $key => $value){
          if($value !== false && is_numeric($value) === false){
            $sql_update[] = "$key = '".translate($value, $GLOBALS['languages'][$input['language_id']]['code'], $GLOBALS['languages'][$GLOBALS['default_language_id']]['code'])."'";
          }
        }
        $sql_update = "UPDATE $table SET ".implode(', ', $sql_update)." WHERE id = '{$array['table_id']}'";
      }
    }

  //Logging function
  	/*function siteLog($user, $site_id, $log = ''){

      	$db = $GLOBALS['db'];
      	$input = renderInput(func_get_args());

      	if($input['site_id'] && $input['user_id'])
	  	$transaction = ($GLOBALS['f']) ? $GLOBALS['o'] . '-' . $GLOBALS['f'] : $GLOBALS['o'];
	    $sql = "INSERT INTO log (user_id, site_id, time, transaction, log) VALUES (".
	                "'{$input['user_id']}', ".
	                "'{$input['site_id'], ".
	                "'".time()."', ".
	                "'{$input['transaction']}, ".
	                "'{$GLOBALS['log']}');";
	    mysqli_query($db, $sql);
  	}*/