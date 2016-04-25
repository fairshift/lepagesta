<?php
	function dbWrapper(){

		include("local/config.php");

		$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
	 	mysqli_set_charset( $db , "utf8" );

	 	$GLOBALS['db'] = $db; //functions need this

	 	return $db;
	}

  //Insert / update localized content
    function addContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        //Function router
    	$route = $input['route']; 	//(content_id || table_name & entry_id), (branch_id || branch_title)
    	$route['language_id'] = (!$route['language_id']) ? $GLOBALS['language_id'] : $route['language_id'];

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

       	//Content to add
        $content = $input['state'];

		//if($privileges['privilege_update'] || $privileges['privilege_create']))
	    if($route['table_name']){

			mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);

			//Get existing content tables
			if( (($route['table_name'] && $route['entry_id']) || $route['content_id']) || $route['branch_id'] || $route['state_id'] ){

				$buffer['route'] = $route;
				$buffer['route']['dataset'] = '*';
				$buffer['route']['history'] = '1';
				$buffer['state'] = 'getContent';
				$buffer = getContent($buffer);

				$route['content_id'] = $buffer['state']['content_id'];
				$route['table_name'] = $buffer['state']['table_name'];
				$route['entry_id'] = $buffer['state']['entry_id'];

				//Get branch of current state
				if($route['state_id'] && !$route['branch_id']){
					$route['branch_id'] = (!$buffer['state']['branches']) ? null : $buffer['state']['branches']['branch_id'];
				}

				//Get main branch if no specific branch_id is chosen
				if(!$route['branch_id'] && !$route['state_id']){
					$route['branch_id'] = $buffer['state']['main_branch_id'];
				}
			}

			//Let's set up content
			foreach($content AS $field => $value){
	       		if($value != null){

		          	$sql_content_row[] = 		"('{$user_id}', ".
		          								"'{$entity_id}', ".
			                        			"'{$route['language_id']}', ".
			                        			time().', '.
			                        			"'{$route['table_name']}', ".
			                        			"'%entry_id%', ".
			                        			"'$field', ".
			                        			"'$value')";

	       			$sql_insert[1][] = $field;
	       			$sql_insert[3][] = "'".$value."'";

	       			if($buffer['state']['branches'][$route['branch_id']]['states']){ //I need to get last state of compiled table
	       				$sql_update[] = $field . " = '{$value}'";
	       			}

				} else {
					$success[] = false;
				}
	        }

			//Add new content
			if(!$route['entry_id'] || !$route['content_id']){
				if(!$route['entry_id']){

					//Add to table
			      	$sql.= "INSERT INTO {$route['table_name']} (";
			      	$sql.= 'created_by_user_id, created_by_entity_id';
	    			$sql.= implode(', ', $sql_insert[1]);
			      	$sql.= ') VALUES (';
			      	$sql.= "'{$user_id}', '{$entity_id}'";
	    			$sql.= implode(', ', $sql_insert[3]);
			      	$sql.= ');';
					
					$success[] = mysqli_query($db, $sql);
					if(!$route['entry_id'] = $db->insert_id){
						$success[] = false;
					}
				}
				if(!$route['content_id']){

					//Add to content table
					$sql = "INSERT INTO content (table_name, entry_id) VALUES ".
		                    			"'{$route['$table_name']}, ".
		                    			"'{$route['$entry_id']}";
					$success[] = mysqli_query($db, $sql);

					if(!$route['content_id'] = $db->insert_id){
						$success[] = false;
					}
				}
			}
			else //Update content
			{
				//If it's author (user_id or entity_id) updating, then main branch is updated
				if($buffer['state']['created_by_user_id'] == $user_id || $buffer['state']['created_by_entity_id'] == $entity_id){

				}


			}

			//
			if($route['entry_id']){

			}

			$sql_content = "INSERT INTO content_translation (created_by_user_id, created_by_entity_id, time_created, time_updated, content_id, branch_id, state_id, language_id, field, content) VALUES ";
		    $sql_content.= implode(', ', $sql_content_row) . ";";
			$success[] = mysqli_query($db, $sql_content);

			//Nest content on an existing branch_id
			if($route['branch_id']){

			}
			//Fork existing branch_id
			if($route['fork_branch_id'] && $route['fork_state_id']){

			}
			//Add new branch
			if($content['title']){
				if(!$content['namespace']){
					//Generate namespace from title
					contentNamespace($route['branch_title']);
				}
			}

			//Add new state
				//Revert to state


			//Add new translation

			//Is branch_id set? 

	      	//Construct multilingual entry SQL

			//If all database queries were okay...
		    if(!in_array(false, $success)){
		    	mysqli_commit($db);

			    if($language_id != $GLOBALS['default_language_id']){ //!!! is not yet translated by hand
			    	$GLOBALS['translation_queue'][] = array('content_id' => $route['content_id'], 'language_id' => $route['language_id'], 'content' => $content);
			    }

	       		//Entries that have been modified are unsynchronized
			    unsyncCacheBlocks($cache);

		        //Return changed data objects back to user
	        	$route['history'] =			1; //How many states from current branch to get?
	        	$route['dataset'] =			(!$route['dataset']) ? '*' : $route['dataset']; //Which content datasets should be returned?

		   		$block = getContent(array('route' => $route));

		    	transaction('transaction' => $transaction, $statechanges);

		    } else { //Otherwise rollback transaction
		    	
		    	mysqli_rollback($db);
		    	transaction('transaction' => $transaction, '400');
		    	$block['state']['status_code'] = '400';
		    }

	    } else {
		    $block['state']['status_code'] = '400';
	    }

	    return $block;
    }
	

    //Add content branch
    function addBranch(){
    	
    }

	//Change main branch for content    
    function changeMainBranch(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

    	if($table_name && $entry_id && is_array($content)){

			//Let's set up content
			foreach($content AS $field => $value){
	       		if($value != null){
	       			$sql_update[] = $field . " = '{$value}'";
				} else {
					$success[] = false;
				}
	        }

			$sql = "UPDATE {$table_name} SET " . implode(', ', $sql_update) . " WHERE id = '{$entry_id}'";
			$success[] = mysqli_query($db, $sql);

			return $success;
    	} else {
    		$success[] = false;
    	}
    }
    function forkContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        //Function router
    	$route = $input['route']; 	//state_id - to identify content that's being edited (necessary)
    								//title - new branch title (necessary)
    								//namespace (optional)
		
		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if($route['state_id']){
    		
			$buffer['state']['getBranches'] = '*';
	        $buffer = getStates(array('route' => $route, 'block' => $buffer));

	        $route['content_id'] = current($buffer['state']['getBranches'])['content_id'];
	        $route['branch_id'] = current($buffer['state']['getBranches'])['branch_id'];

	        
    	}

        //Get current branch_id and content_id...


        $block['relations']['branch_id'] = $route['branch_id'];

       	//Content to add
        $content = $input['state'];

		transaction(array('transaction' => $transaction));
    }

  //Database text is translated to English for multilingual search capabilities
    function translateToDefault(){

      $db = $GLOBALS['db'];
      $input = func_get_args();

      if($input['language_id'] != $GLOBALS['default_language_id']){

        foreach($array['content'] AS $key => $value){
          if($value !== false && is_numeric($value) === false){
            $sql_update[] = "$key = '".translate($value, $GLOBALS['languages'][$input['language_id']]['code'], $GLOBALS['languages'][$GLOBALS['default_language_id']]['code'])."'";
          }
        }
        $sql_update = "UPDATE $table SET ".implode(', ', $sql_update)." WHERE id = '{$array['table_id']}'";
      }
    }

  	//Checks for content namespace availability (within a given user/entity context). Free returns correctly formatted string, existing returns state_id
	function contentNamespace(){

 		$db = $GLOBALS['db'];

 		$input = func_get_args();
		$input['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $input['name']); //removes special characters

        if($input['name']){

			if($input['content_id']){
        		
        	}
        	if($input['user_id']){

        	}

        	//content_

			/*$sql = 	"SELECT id, content_id, branch_id, id AS state_id FROM content_state WHERE ".
		    	   	"namespace = '{$name}'";
			$result = mysqli_query($db, $sql);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC));*/

			$sql = 	"SELECT id, content_id, branch_id, id AS state_id FROM content_branch WHERE ".
		    	   	"namespace = '{$name}'";
		} else {
			return null;
		}
	}



  //Translate content from language to language with Google translate - use latest entry by default, or chosen entry
    //function googleTranslateContent($db, $user, $language_id, )

    function getContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        //Function router
    	$route = ksort($input['route']); 	//language_id - necessary
    										//(content_id || table_name & entry_id), (branch || branch_id), state_id - one of them needs to be called
    										//history - optional - how many states of each field show be returned? (default: 0,12)
  
    										//circle_id - optional 

        $route['dataset'] =					(!$route['dataset']) ? '*' : ksort($route['dataset']); //dataset - optional - which content datasets should be returned?

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

        $block = 							(!$input['block']) ? null : $input['block'];
		$block['transaction'] = 			(!$block['transaction']) ? $transaction : $block['transaction'];

    	if(($user_id || $entity_id) && ($route['content_id'] || $route['branch_id'])){

		    if($block = existingCacheBlock($transaction){
		    	$block['state']['status_code'] = '200';
		    } else {

	    		//Get content_id if only branch_id is set
	    		$buffer = null;
				if(!$route['content_id'] && $route['branch_id'] || $route['state_id'])){
					$buffer_branches = getBranches(array('route' => $route));
					$route['content_id'] = $buffer['state'][$route['branch_id']]['content_id'];
				}

				//Get content and initiator's user_id
				$sql = 	"SELECT content.id, content.id, AS content_id, content.table_name, content.entry_id FROM content WHERE ".
			    	   	"content.id = '{$route['content_id']}'";

				$result = mysqli_query($db, $sql);
	 		    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC))){

					$table_name = $row['table_name'];
					$entry_id = $row['entry_id'];

	  			 	$sql = 	"SELECT *, id AS {$row['table_name']}_id  FROM {$row['table_name']} WHERE ".
	 		    	  		"id = '{$row['entry_id']}'";

					$result_table = mysqli_query($db, $sql);
		 		    if($row_table = mysqli_fetch_array($result_table, MYSQLI_ASSOC) &&
		 		    	isAvailable(array('user_id' => $user_id, 'entity_id' => $entity_id, 'content' => $row_table))){

						if(in_array('getContent', $route['dataset']) || $route['dataset'] = '*'){

							$row_table['status_code'] = '200';
							$buffer['state'][$route['content_id']] = array_merge($row, $row_table);

							$buffer['relations']['{$row['table_name']}.{$row['table_name']}_id'] = $row['entry_id'];
					    	$rand = mt_rand();
					    	$buffer['relations'][$rand]['content.table_name'] = $table_name;
					    	$buffer['relations'][$rand]['content.entry_id'] = $entry_id;

					    	//Build up content table fields as template for state
					    	foreach($buffer['table'] AS $key => $value){
					    		if($key != ('id' || "{$row['table_name']}_id" || 
					    					'created_by_user_id' || 'created_by_entity_id' || 
					    					'time_created' || 'time_updated' || 
					    					'removed_by_user_id' || 'removed_by_entity_id' ||
					    					'time_removed')){

					    			$buffer['table'][] = $key; //
					    		}
					    	}

				    		if(in_array('getBranches', $route['dataset']) || $route['dataset'] == '*'){

					  			if($buffer['state'][$route['content_id']]['status_code'] == '200'){

									if(isset($buffer_branches)){ //If we got branches before already
										$buffer = mergeBlocks('getBranches', $buffer, $buffer_branches);
									} else {
								  		//Content branches & optional filter by branch_id
							 			$buffer['state'][$route['content_id']]['branches'] = 'getBranches';
								  		$buffer = getBranches(	array( 	'route' => $route,
								  										'block' => $buffer));
									}
					  			}
				  			}
						}

		  			} else {
		  				$buffer['state'][$route['content_id']]['status_code'] = '400';
 		  			}
				}

  				//Update cache with current block if calling function didn't pass state
  				if(!$block['state'] && !in_array(array('status_code' => '400'), $block['state'])){
		    		updateCacheBlock($block);
		    	}
  			}

	  		//Merge current block with one delivered by calling function
	  		$block = mergeBlocks('getContent', $block, $buffer);

  		    transaction(array('function' => __FUNCTION__));

			return $block;
		}
    }

    function getBranches(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        //Function router
    	$route = ksort($input['route']); 	//
    										//content_id, branch_id, state_id - one of them needs to be called
    										//history - optional - how many states to return from current branch?
    										//dataset - optional - which content datasets should be returned?

    										//circle_id - optional         $route['content_id'] =				(!$route['content_id']) ? null : $route['content_id'];

        $route['dataset'] =					(!$route['dataset']) ? '*' : ksort($route['dataset']); //optional - which content datasets should be returned?

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 			(!$block['transaction']) ? $transaction : $block['transaction'];;

        if($route['content_id'] || $route['branch_id'] || $route['state_id']){
        	if(!$buffer = existingCacheBlock($transaction)){

         		if($route['state_id'] && !$route['branch_id']){ //get by state_id
         			$buffer_states = getStates(array('route' => $route));
		 		    $route['content_id'] = $buffer['state']['states'][$route['state_id']]['content_id'];
		 		    $route['branch_id'] = $buffer['state']['states'][$route['state_id']]['branch_id'];
         		}

         		//Branches
     			$sql_where[] = ($route['content_id']) ? "content_id = '{$route['content_id']}'" : '';
     			$sql_where[] = ($route['branch_id']) ? "branch_id = '{$route['branch_id']}'" : '';
     		 	$sql = "SELECT *, id AS branch_id FROM content_branch WHERE ".implode(' AND ', $sql_where);

				$result = mysqli_query($db, $sql);
	 		    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

	 		    	$buffer['state'][$row['branch_id']] = 'getStates';

	 		    	if(isset($buffer_states)){
  						$buffer = mergeBlocks('getStates', $buffer, $buffer_states);
	 		    	} else {
	 		    		$buffer = getStates('route' => $route, 'block' => $buffer);
	 		    	}

	 		    	$buffer['state'][$row['branch_id']]['circles'] = 'getCircles';
		    		$buffer = getCirclesBy(array('route' => array('branch_id' => $route['branch_id']), 'block' = $buffer));

	 		    	if(isAvailable($buffer)){
						
						$buffer['relations']['content_branch.branch_id'] = $row['branch_id'];

				      //States & translations within branch
						if($route['dataset'] == '*' || in_array('getStates', $route['dataset'])){
							$buffer['state'][$row['branch_id']]['states'] = 'getStates';
							$route['branch'] = $row['branch'];
							$buffer = getStates(array('route' => $route, 'block' => $buffer));
						}

					}
				}
         	}
    	}

		//Update cache with current block if calling function didn't pass state
		if(!$block['state'] && !in_array(array('status_code' => '400'), $block['state'])){
    		updateCacheBlock($block);
    	}

  		//Merge current block with one delivered by calling function
  		$block = mergeBlocks('getBranches', $block, $buffer);

        $transaction = transaction(array('function' => __FUNCTION__));

    	return $block;
    }

    function getStates(){ //Within a branch there's a historical trail of content states

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();
    	$route = $input['route'];

    	$route['language_id'] = 	(!$route['language_id']) ? $GLOBALS['language_id'] : $route['language_id'];
        $route['dataset'] =			(!$route['dataset']) ? '*' : ksort($route['dataset']); //which subsections of content to return?

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

        $block = 					(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 	(!$block['transaction']) ? $transaction : $block['transaction'];

    	if($route['branch_id']){

        	if(!$buffer = existingCacheBlock($transaction)){

         		//Get content states trail
			    $sql = 	"SELECT * FROM content_state WHERE ".
			    		"language_id = '{$route['language_id']}' AND branch_id = '{$route['branch_id']}'";

			    //Get content 

		        $response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']] = $row_translation;
		        $response[$GLOBALS['languages'][$row['language_id']]['code']][$row_translation['field']]['history'][$i] = $row_translation;

	    		/*
					-keywords, values and reflections go into state here (time component: are they available in state's current time?)
					-
		        */
			}
    	}

		//Update cache with current block if calling function didn't pass state
		if(!$block['state'] && !in_array(array('status_code' => '400'), $block['state'])){
    		updateCacheBlock($block);
    	}

  		//Merge current block with one delivered by calling function
  		$block = mergeBlocks('getStates', $block, $buffer);

        $transaction = transaction(array('transaction' => $transaction));

    	return $block;
    }


    function isAvailable($row){ //Returns if content is available... Think ahead of a problem: branches in circles & deletion of original content

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

      	if($row['time_removed'] == 0){
      		return true
      	} else {
	      	foreach($row AS $key => $value){
	      		if(strpos('user_id', $key) && $user_id == $value){
	      			return true;
	      		}
	      		if(strpos('entity_id', $key) && $entity_id == $value){
	      			return true;
	      		}
	      	}
	      	return false;
      	}
	}

	//$block['state']['branches'] = 'getBranches';
	//$buffer['state'][12] = array();

    //As functions building data output are calling other functions to supply specific datasets, blocks are merged
	function mergeBlocks($needle, $block, $buffer){ //taking in $block && $buffer (where ['state'] contains data and needle )

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
	}

	//needle: getBranches
	//
?>