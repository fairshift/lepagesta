<?php
	function dbWrapper(){

		include("local/config.php");

		$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //input function needs this

	 	return $db;
	}

  //Insert / update localized content
    function addContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = func_get_args();

      //Function conditions
        $route = $input['route'];
        //Language of choice
        	$route['language_id'] =				(!$route['language_id']) ? null : $route['language_id'];
    	//Edit existing content
    		//By content_id
 			$route['content_id'] = 				(!$route['content_id']) ? null : $route['content_id'];

 			//By table_name & entry_id
 			$route['table_name'] = 				(!$route['table_name']) ? null : $route['table_name'];
 			$route['entry_id'] = 				(!$route['entry_id']) ? null : $route['entry_id'];

 			//Add to current branch
        	$route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id'];

        	//Fork the above branch_id
        	$route['state_id'] = 				(!$route['state_id']) ? null : $route['state_id']; //Fork starts here
        	$route['branch'] = 					(!$route['branch']) ? null : $route['branch']; //Branch name

        //Content fields
        	$route['content'] = 				(!$route['entry_id']) ? null : $route['content']; //Content array

        //Return changed data object back to user
	        $route['history'] = 				(!$route['history']) ? '0,12' : $route['history']; //How many states from a current block
	        $route['preset'] =					(!$route['preset']) ? '*' : $route['preset']; //How deep should this query go?

	    //Block of data
	        $block = 							(!$input['block']) ? null : $input['block'];
	    	$block['transaction'] = 			(!$block['transaction']) ? formatTransaction(__FUNCTION__, $route) : $block['transaction'];
	    	$block['transaction_time'] = 		(!$block['transaction_time']) ? microtime() : $block['transaction_time'];
	  		$block['dataview'];					(!$block['dataview']) ? null : $block['dataview'];
	    	$block['state'] = 					(!$block['state']) ? null : $block['state'];

	    //Caching enabled?
	    	$cache =							(!$input['cache']) ? null : $input['cache'];

    	//$language_id, $table_name, $entry_id, $branch_id, $content, $circles = null


			/*$buffer['state']['getContentTable'] = '%';
	    	if($input['table_name'] && $input['entry_id']){
				$buffer = getContentTable(array('table_name' => $input['table_name'], 'entry_id' => $input['entry_id'], 'block' => $buffer));
	    	} elseif($input['content_id']){
				$buffer = getContentTable(array('content_id' => $input['table_name'], 'entry_id' => $input['entry_id'], 'block' => $buffer));
	    	}*/

		//&& ($privileges['max_privilege_update'] || $privileges['max_privilege_create']))

			//Add new field, content, branch, state and translation
			if(!$route['branch'] && !($route['content_id'] && !($route['table_name'] && $route['entry_id']))){
		      	//SQL - Add to table
			      	$sql.= "INSERT INTO $table_name (";
	    			$sql.= implode(', ', $sql_insert[1]);
			      	$sql.= ") VALUES (";
	    			$sql.= implode(', ', $sql_entry[3]);
			      	$sql.= ");";
				
					mysqli_query($db, $sql);
					$entry_id = $db->insert_id;

				$sql_content = str_replace("%new_entry_id%", $entry_id, $sql_content);

				$sql_content = "INSERT INTO content (user_id, content_id, branch_id, time, language_id, circle_id, field, timestamp, content) VALUES ";
	                    			"'{$input['$table_name']}, ".
	                    			"'{$input['$entry_id']}, ".
			} else {
		    	if($input['table_name'] && $input['entry_id']){
					$buffer = getContentTable(array('route' => $route, 'block' => $buffer));
		    	} elseif($input['content_id']){
					$buffer = getContentTable(array('route' => $route, 'block' => $buffer));
		    	} else {
		    		$buffer 
		    	}
			}

			//Is branch_id set? 

	      	//Construct multilingual entry SQL
				$sql_content = "INSERT INTO content_translation (user_id, time, content_id, branch_id, language_id, circle_id, field, timestamp, content) VALUES ";	

		        foreach($content AS $field => $value){
		       		if($value != false){

		       			$time = time();

		       			if(is_integer($value) === false && is_float($value) === false){
				          	$sql_content_row[] = 		"('$user_id', ".
				          								time().', '.
				          								"'{$input['content_id']}, "
					                        			"'{$input['$language_id']}', ".
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
		    unsyncCache($db, $block);

		    if($entry_id == '%new_entry_id%'){
		    	$response = getContent($db, $user, $table_name, $entry_id);
		    }

		    return $response;

	      } else {
	      	return false;
	      }
    }

  //Translate content from language to language with Google translate - use latest entry by default, or chosen entry
    //function googleTranslateContent($db, $user, $language_id, )

    function getContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = func_get_args();

        $route = $input['route'];

        //Get content in current language
        $route['language_id'] = 			(!$route['language_id']) ? null : $route['language_id']; //mandatory route

        //One of these needs to be called
        $route['content_id']  = 			(!$route['content_id']) ? null : $route['content_id']; //optional content_id

        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id']; //optional call of content branch_id
        $route['branch'] = 					(!$route['branch']) ? null : $route['branch']; //optional call of content branch
        $route['state_id'] =				(!$route['state_id']) ? null : $route['state_id']; //optional call of content state_id

        $route['keywords'] =				(!$route['keywords']) ? null : $route['keywords']; //filter by keywords
        //If branch is 
        $route['circle_id'] = 				(!$route['circle_id']) ? null : $route['circle_id'];

        $route['history'] = 				(!$route['history']) ? '0,12' : $route['history'];
        $route['preset'] =					(!$route['preset']) ? '*' : $route['preset']; //which subsections of content to return?

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction_time'] = 		(!$block['transaction_time']) ? microtime() : $block['transaction_time'];
  		$block['dataview'];					(!$block['dataview']) ? null : $block['dataview'];
    	$block['state'] = 					(!$block['state']) ? null : $block['state'];

    	if($user_id && ($route['content_id'] || $route['branch_id'] || $route['branch'] || $route['state_id']) && $route['language_id']){

    	//A way to get to content_id from lower levels of content structure
			if($route['state_id'] && (!$route['branch_id'] && !$route['content_id'])){
				$buffer = getStates(array('state_id' => $route['state_id'], 'history' => '0,12'));
				$route['branch_id'] = $buffer['branch_id'];
				$route['content_id'] = $buffer['content_id'];
			}
			if($route['branch_id'] ||  && !$route['content_id']){
				$buffer = getBranches(array('state_id' => $route['state_id'], 'history' => '0,12'));
				$route['content_id'] = $buffer['content_id'];
			}
    		$block['transaction'] = 		(!$block['transaction']) ? formatTransaction(__FUNCTION__, $route) : $block['transaction'];

		    if(!$block = existingCacheBlock($block, 'route'){

				//Get content and initiator's user_id

				$sql = 	"SELECT content.id, content.id, AS content_id, content.table_name, content.entry_id FROM content WHERE ".
			    	   	"content.id = '{$content_id}'";

				$result = mysqli_query($db, $sql);
	 		    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC))){

					$table_name = $row['table_name'];
					$entry_id = $row['entry_id'];

	  			 	$sql = 	"SELECT *, id AS {$table_name}_id  FROM {$table_name} WHERE ".
	 		    	  		"id = '{$entry_id}'";

					$result_table = mysqli_query($db, $sql);
		 		    if($row_table = mysqli_fetch_array($result_table, MYSQLI_ASSOC))){

						if(isAvailable($user_id, $row_table)){

							$buffer['state'][$content_id] =  array_merge($row, $row_table);
					    	$buffer['state'][$content_id]['status_code'] = '200';

							$buffer['dataview']['{$input['table_name']}.{$input['table_name']}_id'] = $route['entry_id'];
					    	$rand = mt_rand();
					    	$buffer['dataview'][$rand]['content.table_name'] = $table_name;
					    	$buffer['dataview'][$rand]['content.entry_id'] = $entry_id;

				  			$route['author_id'] = $buffer['state'][$content_id]['user_id'];

				    		if(in_array('getBranches', $route['preset']) || $route['preset'] == '*'){

					  			if($buffer['state'][$content_id]['status_code'] == '200'){

							  		//Content branches & optional filter by branch_id
						 			$buffer['state']['getBranches'] = '%';
							  		$buffer = getContentBranches(array( 'route' => $route,
							  											'block' => $buffer));
					  			}
				  			}
			  			} else {
			  				$buffer['state'][$content_id]['status_code'] = '400';
	 		  			}

					} else {
			  			$buffer['state'][$content_id]['status_code'] = '400';
					}
				}

		  		//Merge current block with one delivered by calling function
		  		$block = mergeBlocks('getContentTable', $block, $buffer);
  				//Update cache with current block if calling function didn't pass state
  				if(!$block['state'] && !in_array(array('status_code' => '400'), $block['state'])){
		    		updateCacheBlock($block);
		    	}
  			}

			return $block;
		}
    }

    function getBranches(){ //Shares block  for content interpretation
 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = func_get_args();

        $route = $input['route'];
        //Either one of the following is required
        $route['content_id'] =				(!$route['content_id']) ? null : $route['content_id'];
        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id'];
        $route['branch'] = 					(!$route['branch']) ? null : $route['branch'];

        $route['state_id'] =				(!$route['state_id']) ? null : $route['state_id'];

        $route['history'] = 				(!$route['history']) ? '0,12' : $route['history'];
        $route['preset'] =					(!$route['preset']) ? '*' : $route['preset']; //which subsections of content to return?

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 			(!$block['transaction']) ? formatTransaction(__FUNCTION__, $route) : $block['transaction'];
    	$block['transaction_time'] = 		(!$block['transaction_time']) ? microtime() : $block['transaction_time'];
  		//$block['dataview'];
    	$block['state'] = 					(!$block['state']) ? null : $block['state'];

        if($route['content_id'] || $route['branch_id'] || $route['branch']){
        	if($buffer = existingCacheBlock($input)){
        		$buffer['state']['status_code'] = '200';
         	} else {

         		//Branches
     			$sql_where = ($route['branch']) ? "WHERE branch = '{$route['branch']}' OR branch_id = '{$route['branch_id']}'" : '';
     		 	$sql = "SELECT *, id AS branch_id FROM content_branch {$sql_where}";

				$result = mysqli_query($db, $sql);
	 		    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

	 		    	$buffer['state']['getCircles'] = '%';
		    		$buffer = getCirclesBy(array('branch_id' => $route['branch_id'], 'block' = $buffer));
		    		$userCircles = getCirclesBy(array('user_id' => $user_id)); //block isn't passed, it doesn't get buffered

	 		    	if(isAvailable($row['content_branch'], $user_id)){

		    			availablePrivileges($author_id, $user_id, $content_branch, $branch_id);
						
						$buffer['dataview']['content_branch.branch_id'] = $row['branch_id'];
						$buffer['dataview']['content_branch.branch'] = $row['branch'];

				      //States & translations within branch
						if($route['preset'] == '*' || in_array('getState', $route['preset'])){
							$buffer['state'][$row['branch_id']]['get'] = '%';
							$route['branch'] = $row['branch'];
							$buffer = getState(array('route' => $route, 'block' => $buffer));
						}
					}
				}
         	}
    	}

		//Dispatch to block
		if($block['state']){
			$block = mergeBlocks('getContentTable', $block, $buffer);
		} else {
			$block = $buffer;

			//Everything okay?
  			if(!in_array(array('status_code' => '400'), $block['state'])){
    			updateCacheBlock($block);
    		}
    	}

    	return $block;
    }

    function getStates(){ //Within a branch there's a historical record of data states

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = func_get_args();

    	$conditions = $input['conditions'];
        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id']; //optional call of content branch_id
        $route['branch'] = 					(!$route['branch']) ? null : $route['branch']; //optional call of content branch
        $conditions['state_id'] =			(!$input['state_id']) ? null : $input['state_id']; //optional
        $conditions['history'] = 			(!$input['history']) ? '0,12' : $input['history']; //optional

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 			(!$input['block']['transaction']) ? formatTransaction(__FUNCTION__, $input) : $input['block']['transaction'];
    	$block['transaction_time'] = 		(!$input['block']['transaction_time']) ? microtime() : $input['block']['transaction_time'];
  		//$block['dataview'];
    	$block['state'] = 					(!$input['block']['state']) ? null : $input['block']['state'];

    	if($route['branch'] || $route['branch_id']){
    		//$contentCircles = getCirclesBy($input, $buffer); //user specific caching is enabled in a seperate block to reduce cache load (think combinations)

    		$buffer['state']['getStates'] = '%';
			$buffer = getBranchStates(array('conditions' => $conditions, 'block' => $buffer); //

	      //Get multilingual content & history
			$where = " AND ORDER BY time LIMIT {$history}";
		    $sql = 	"SELECT id, branch_id, user_id, time, content, googletranslated FROM content WHERE ".
		    		"language_id = '{$row['language_id']}' AND table_name = '$table_name' AND entry_id = '$entry_id'".$where;
		    $result_translation = mysqli_query($db, $sql);
		    $i = 0;
	        while($row_translation = mysqli_fetch_array($result_translation, MYSQLI_ASSOC)){
	          	if($i = 0){
	            	$response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']] = $row_translation;
	          	}
	          	$response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']]['history'][$i] = $row_translation;
	        }

	        /*
				-keywords, values and reflections go into state here (time component: are they available in state's current time?)
				-
	        */
    	}

    }

    function addBranch(){
    	
    }

    function getBranchStates(){

    }

    function addBranchState(){

    }

    function getStateTranslations(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

 		if($)

 			//translate with google
    }

    function addStateTranslation(){
		$sql_content = "INSERT INTO content (user_id, time, content_id, branch_id, state_id, language_id, googletranslated, field, content) VALUES ";
                			"'{$input['$table_name']}, ".
                			"'{$input['$entry_id']}, ".
    }

    function isAvailable($content, $user_id){

		if($content['removed'] == 0){
			return true;
		}
		if($content['removed'] > 0 && ($content['removed_by_user_id'] == $user_id || ($content['user_id'] == $user_id)){
			return true
		);
		return false;
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