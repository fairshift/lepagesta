<?php

	/* How content gets returned

	Plain table response structure (for tables that don't use node structure)
	
		$query['response'][$table_name][$entry_id] = $table_row;

	Content node response structure
		
		$query['response'][$node_id] = $node_table_row;
		$query['response'][$node_id]['line_id'] = $chosen_line_id / $main_line_id;

		$GLOBALS['nodes'][$node_id] = $node_table_row;
		$GLOBALS['nodes'][$node_id]['table'] = $table_row;
		$GLOBALS['nodes'][$node_id]['status_code'] = '200';

		$GLOBALS['nodes'][$node_id]['line'][$line_id] = $line_table_row;
		$GLOBALS['nodes'][$node_id]['line'][$line_id]['rooted'] = $rooted_line_table_row; //rooted in current line - links to line/node
		$GLOBALS['nodes'][$node_id]['line'][$line_id]['tied'] = $tied_line_table_row; //tied to current line - links to line/node

		$GLOBALS['nodes'][$node_id]['line'][$line_id][$language_id][$state_row-time_created][$field] = $state_row_by_field;

	*/

	/*function nodeHasChanged(){
		//Frontend checks if data node stayed the same
	}*/

    function getNode(){ //compiling a specific state of data for a content node (with trail of states, various lines of content)

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        //Function router
    	$route = $input['route']; 	//language_id array - necessary
									//node_id || table_name & entry_id - necessary
									//line_id, time_state_pointer - optional
									//history - optional - (default is 12: 0,12)

        //Cascading - lines may be connecting various nodes - so we set limits to depth of one call
        $input['cascade'] = 	(!$input['cascade']) ? 0 : $input['cascade'] + 1;
        $input['cascade_max'] = (!$input['cascade_max']) ? 1 : $input['cascade_max'];

    	//Dataset - which content datasets should be returned? - optional, default try all available
        $input['dataset'] = (!$input['dataset']) ? '*' : $input['dataset'];

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $input['dataset']));
        $query['transaction'] = (!$input['parent-transaction']) ? $transaction : $input['parent-transaction'];

    	if( ($user_id || $entity_id) && ($route['node_id'] || ($route['table_name'] && ($route['entry_id'] || is_array($route['entry']))) && $route['cascade'] <= $route['cascade_max'] ){

		    if(!$buffer = existingCache($transaction)){

		    	$query = array_merge($query, $buffer);

		    	$buffer = getNodeTable($route);
			    if($table_row = $buffer['table_row']; && $node_row = $buffer['node_row'])){
					$route['table_name'] = $node_row['table_name'];
					$route['entry_id'] = $node_row['entry_id'];
					$route['node_id'] = $node_row['id'];
				}

				if( isAvailable('row' => $node_row) && isAvailable('row' => $table_row) ){ //establish node response

					$query['response'][$node_row['id']] = $node_row;

			  		$route['main_line_id'] = $node_row['main_line_id']; //set optional route 'main_line_id'
			    	$route['table'] = $table_row; //pass table as a layout for fetching data states
			  		$lines = getLines(array( 'route' => $route, 'parent-transaction' => $query['parent-transaction'] )); 

		 			$query['response'][$node_row['id']] = array_merge($query['nodes'][$node_row['id']], $lines['response']);

		 			//Establish caching
					$query['cache-relations']["{$route['table_name']}.id"] = $route['entry_id'];
			    	$query['cache-relations']['node.id'] = $node_row['id'];
		 			$query['cache-relations'] = array_merge($query['cache-relations'], $lines['cache-relations']);

					$GLOBALS['cache-nodes'][$query['transaction']][$node_row['id']] = $node_row;
					$GLOBALS['cache-nodes'][$query['transaction']][$node_row['id']]['table'] = $table_row;
					$GLOBALS['cache-nodes'][$query['transaction']][$node_row['id']]['status_code'] = '200';

				} elseif( isAvailable('row' => $table_row) && !$node_row ){ //this table isn't a content node

					$query['response'][$route['table_name']][$table_row['id']] = $table_row;
					if($input['parent-transaction']){
						$query['cache-relations']["{$route['table_name']}.id"] = $route['entry_id'];
					}
				} else {
					$query['response']['status_code'] = '400';
				}

   				//Update cache with state(s) of content - if calling function didn't set parent-cache and everything else went okay
  				if( is_array($query['cache-relations']) && !errors($query['response']) 
  														&& !errors($GLOBALS['cache-nodes'][$query['transaction']]) ){
		    		updateCache($query);
		    	}
  			} else {
  				$GLOBALS['nodes'] = array_merge($GLOBALS['nodes'], $query['nodes']);
  			}

  		    transaction(array('transaction' => $transaction));

			return $query;
		}
    }

    function getNodeTable(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        //Function router
    	$route = $input['route']; //node_id || table_name & entry_id - necessary

    	if( ($user_id || $entity_id) && ($route['node_id'] || ($route['table_name'] && ($route['entry_id'] || is_array($route['where']))) ){

    		//Search for entry_id by various parameters
    		if(is_array($route['where'])){
    			foreach($route['where'] AS $field => $value){
    				$sql_where[] = "{$field} = '{$value}'";
    			}
  		 		$sql = 	"SELECT *, id AS {$route['table_name']}_id  FROM {$route['table_name']} WHERE ".
	    	  			implode(' AND ', $sql_where);

				$table = mysqli_query($db, $sql);
			    $table_row = mysqli_fetch_array($table, MYSQLI_ASSOC));

    			$route['entry_id'] = $table_row['id'];
    		}

			//Load content node
			$sql = 	"SELECT *, id, AS node_id FROM node WHERE ";
		   	$sql.= 	($route['node_id']) ? "id = '{$route['node_id']}'" : "table_name = '{$route['table_name']} AND entry_id = '{$route['entry_id']}'";
			$node_result = mysqli_query($db, $sql);
		    if($node_row = mysqli_fetch_array($node_result, MYSQLI_ASSOC))){
				$route['table_name'] = $node_row['table_name'];
				$route['entry_id'] = $node_row['entry_id'];
				$route['node_id'] = $node_row['id'];
			} else {
				$node_row['status_code'] = '400';
			}

			//Get main content table (if it wasn't found already)
			if(!$table_row && $route['table_name'] && $route['entry_id']){
			 	$sql = 	"SELECT *, id AS {$route['table_name']}_id  FROM {$route['table_name']} WHERE ".
		    	  		"id = '{$route['entry_id']}'";

				$table = mysqli_query($db, $sql);
			    if(!$table_row = mysqli_fetch_array($table, MYSQLI_ASSOC))){
					$table_row['status_code'] = '400';
				}
			}

			$query['table_row'] = $table_row;
			$query['node_row'] = $node_row;
		}

		return $query;
    }

    function getLines(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        //Function router
    	$route = ksort($input['route']);

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));
        $query['transaction'] = ($input['parent-transaction']) ? $input['parent-transaction'] : $transaction;

        if(($user_id || $entity_id) && ($route['node_id'] || $route['line_id'] || $route['main_line_id'])){

        	//Line - root node and node tied to (can be used to list lines in a node)
        	/*if($route['node_id']){
	 			$sql_where[] =  "root_node_id = '{$route['node_id']}'";
	 			$sql_where[] = "tie_node_id = '{$route['node_id']}'";
        	}*/

        	//Line - current line, root line and line tied to
        	$line_id = ($route['line_id']) ? $route['line_id'] : $route['main_line_id'];
        	if($line_id){
	 			$sql_where[] = "id = '{$line_id}'"; //current line
	 			$sql_where[] = "root_line_id = '{$line_id}'"; //line that is rooted in current line
	 			$sql_where[] = "tie_line_id = '{$line_id}'"; //line that is tied to current line
 			}

 		 	$sql = "SELECT *, id AS line_id FROM node_line WHERE ".implode(' OR ', $sql_where)." ORDER BY id DESC";

			$result_lines = mysqli_query($db, $sql);
 		    while($row_line = mysqli_fetch_array($result_lines, MYSQLI_ASSOC)){

	    		$circles = getCirclesBy(array('route' => array('line_id' => $row_line['id'])))['response'];

	    		if(isAvailable(array('row' => $row_line, 'circles' => $circles))){

					if($line_id == $row_line['id']){ //current line id

						$query['response']['line_id'] = $line_id;
						$query['cache-relations']['node_line.id'] = $row_line['id'];

						//Add to current line
							$row_line['circles'] = $circles['response']; //circle nodes
							$row_line = array_merge($row_line, getStates(array('route' => $route, 'parent-transaction' => $query['parent-transaction']))['response']); //states in current line

//Dataset hook !!!

							$GLOBALS['cache-nodes'][$query['transaction']][$route['node_id']]['line'][$line_id] = array_merge( $GLOBALS['cache-nodes'][$query['transaction']][$route['node_id']]['line'][$line_id], $row_line );

						//Current line is rooted in
							if($row_line['root_line_id']){ 
								$cascade_route['node_id'] = $row_line['root_node_id'];
								$cascade_route['line_id'] = $row_line['root_line_id'];
								$cascade_route['cascade'] = $route['cascade'];
							}
							$cascade_query = getNode(array('route' => $cascade_route, 'parent-transaction' => $query['transaction']));
							$query['cache-relations'] = array_merge($query['cache-relations'], $cascade_query['cache-relations']);

						//Current line is tied to
							if($row_line['tie_line_id']){ 
								$cascade_route['node_id'] = $row_line['tie_node_id'];
								$cascade_route['line_id'] = $row_line['tie_line_id'];
								$cascade_route['cascade'] = $route['cascade'];
							}
							$cascade_query = getNode(array('route' => $cascade_route, 'parent-transaction' => $query['transaction']));
							$query['cache-relations'] = array_merge($query['cache-relations'], $cascade_query['cache-relations']);

					} elseif($line_id == $row_line['root_line_id']){ //a line is rooted to current line

						$GLOBALS['cache-nodes'][$query['transaction']][$route['node_id']]['line'][$line_id]['rooted'][$row_line['id']] = $row_line;

						$cascade_route['node_id'] = $row_line['node_id'];
						$cascade_route['line_id'] = $row_line['id'];
						$cascade_route['cascade'] = $route['cascade'];

						$cascade_query = getNode(array('route' => $cascade_route, 'parent-transaction' => $query['transaction']));
						$query['cache-relations'] = array_merge($query['cache-relations'], $cascade_query['cache-relations']);

					} elseif($line_id == $row_line['tie_line_id']){ //a line is tied to current line

						$GLOBALS['cache-nodes'][$query['transaction']][$route['node_id']]['line'][$line_id]['tied'][$row_line['id']] = $row_line;

						$cascade_route['node_id'] = $row_line['node_id'];
						$cascade_route['line_id'] = $row_line['id'];
						$cascade_route['cascade'] = $route['cascade'];

						$cascade_query = getNode(array('route' => $cascade_route, 'parent-transaction' => $query['transaction']));
						$query['cache-relations'] = array_merge($query['cache-relations'], $cascade_query['cache-relations']);
					}
				}
			}

			if(!$query['response']['line_id']) {
				$query['response']['status_code'] = '400';
			}
    	}

        transaction(array('transaction' => $transaction));

    	return $query;
    }

    function getStates(){ //within a content line there's a trail of content states, history of changes

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

    	$route = $input['route'];
    	$route['language_id'] = 		(!$route['language_id']) ? array($GLOBALS['language_id']) : $route['language_id']; //languages array
        $route['history'] =				(!$route['history']) ? '0,12' : $route['history'];
        $route['time_state_pointer'] =	(!$route['time_state_pointer']) ? null : $route['time_state_pointer'];

		foreach($route['language_id'] as $key => $language_id){ //get states for each language listed

			$buffer_route = $route;
			$buffer_route['language_id'] = $language_id;

			$sql_language = "language_id = '{$route['language_id']}'";
		
			$transaction = transaction(array('function' => __FUNCTION__, 'route' => $buffer_route));

			if( ($user_id || $entity_id) && $route['node_id'] && $route['line_id'] ){

					if($route['node_id'] && !$route['table']){
						$buffer = getNodeTable($route);
						$route['table'] = $buffer['table_row'];
					}

					$sql_where[] = "node_id = '{$route['node_id']}'";
					$sql_where[] = "line_id = '{$route['line_id']}'";
					if($route['time_state_pointer']){
						$sql_where[] = "time_created <= '{$time_state_pointer}'";
					}

					//Get entries for selected language, site's default_language_id and user's spoken languages
					$language = $route['language_id'];
					//array_merge($language, list of users spoken languages) !!!
					if(!in_array($GLOBALS['default_language_id'], $route['language_id'])){
						$language[] = $GLOBALS['default_language_id'];
					}
			    		if(!$query = existingCache($transaction)){ //isset($input['parent-cache'])... does this make sense in terms of optimization?

			    		foreach($route['table'] AS $field => $content){ //get states for each table field

				    		//$query['response'][$field][$language_id]['count'] = 0; //count states

						 	$sql = 	'SELECT * FROM content_state WHERE '.implode(' AND ', $sql_where)." AND language_id = '{$sql_language}' AND field = '{$field}' ORDER BY id DESC LIMIT {$history}";
							$result = mysqli_query($db, $sql);
						    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))){

								$query['response'][$language_id][$row['time_created']][$field] = $field;
								$GLOBALS['cache-nodes'][$query['transaction']][$route['node_id']]['line'][$route['line_id']][$language_id][$row['time_created']][$field] = $row;
							}
				    	}
				    }
	  			} else {
	  				$GLOBALS['nodes'] = array_merge($GLOBALS['nodes'], $query['nodes']);
	  			}
			}

		if(!isset($input['parent-transaction']){
       		transaction(array('transaction' => $transaction));
    	}

    	return $query;
    }

    function getCurrentState(){

    }

    function getTranslationRequest(){
    	
    }

?>