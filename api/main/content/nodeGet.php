<?php
//To-do - support nesting new content on an existing node line that's most relevant

	if(!isset($GLOBALS['nodes'])){
		$GLOBALS['nodes'] = array(); //is a cache for data for this call (so that a single data node is queried as few times as possible from DB)

		//TODO - $GLOBALS['sent-nodes'] would be a cache for data sent out in current session, so that a single data node is only sent out once per session (file cache with auth as key, changing per each session)
	}

	/* How content gets returned

	* Table response structure (used by data nodes that don't use node structure)
	
		$GLOBALS['nodes'][$table][$id] = $table;
		$GLOBALS['nodes'][$table][$id]['node_id'] = $node_id;
		$GLOBALS['nodes'][$table][$id]['relations'] = $relations_array;

	* Node response structure addition

		$GLOBALS['nodes'][$table][$id] = $node;
		$GLOBALS['nodes'][$table][$id]['relations'] = $relations_array; //relations by node and node_* tables

		$GLOBALS['nodes'][$table][$id][$dataset_table][$dataset_entry_id] = array('table' => $table_name, 'id' => $entry_id, 'node_id' => $node_id, 'line_id' => $line_id) //datasets related to node_id

		$GLOBALS['nodes'][$table][$id]['line'][$line_id] = $line_table;
		$GLOBALS['nodes'][$table][$id]['line'][$line_id][$dataset_table][$dataset_entry_id] = array('table' => $table_name, 'id' => $entry_id, 'node_id' => $node_id, 'line_id' => $line_id) //datasets related to line_id

		$GLOBALS['nodes'][$table][$id]['line'][$line_id]['root'][$root_line_id] = array('table' => $table_name, 'id' => $entry_id, 'node_id' => $node_id, 'line_id' => $line_id); //rooted in current line - links to line/node
		$GLOBALS['nodes'][$table][$id]['line'][$line_id]['tie'][$tie_line_id] = array('table' => $table_name, 'id' => $entry_id, 'node_id' => $node_id, 'line_id' => $line_id); //tied to current line - links to line/node

		$GLOBALS['nodes'][$table][$id]['line'][$line_id]['state'][$language_id][$field]['content'] = $state_row_by_field; //latest content state

		TO-DO
		$GLOBALS['nodes'][$table][$id]['line'][$line_id]['state'][$language_id][$field]['trail'][$time_state_pointer]['content'] = $state_row_by_field //past states

	* Node_id alias table

		$GLOBALS['nodes'][$node_id]['table'] = $table; 
		$GLOBALS['nodes'][$node_id]['id'] = $id;
	*/

    function getNode(){ //A call to load a node and N(=horizon-cascade) levels of related nodes comes...

		/* When compiled node and related nodes come from db cache...
			<- build a list of current node's unavailable by traversing node array
				-node/table
				-dataset
				-line
					-node_circle
					-state
					-dataset

		 * -> Save to $GLOBALS['nodes'] cache
		 	 - response array of current node (table_name, entry_id & if node: node_id, line_id)
			 - table/node array of current and related nodes
			 - build cache table relations list of all nodes

		 * -> When updateNodeCache ($db) - seperates each supplied node_line per available language_id's

		What do to with unavailable nodes?
		- remove them from array (would they be needed, later on?)
		- keep them there and remove them later on
		*/

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; 				//node_id || table & id || where - necessary
    											//language_id (number or array) - necessary
												//line_id - optional (or main_line_id)
    											//time_state_pointer - optional
												//history - optional - (default: up to 12 state changes)

        //Related lines may be connecting various nodes - so we set limit to cross-node calls
        $route['cascade'] = 	(!$route['cascade']) ? 0 : $route['cascade'] + 1; //how deep has the script traversed?
        $route['horizon'] = 	(!$route['horizon']) ? 2 : $route['horizon']; //when to stop?
        $horizon = $route['horizon'] - $route['cascade'];

    	//Dataset - which content datasets should be returned? default (*): try all available
        $input['dataset'] = 	(!$input['dataset']) ? '*' : $input['dataset'];

		$transaction = 			transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $input['dataset']));

    	if( $route['node_id'] || ($route['table'] && ($route['id'] || is_array($route['where']))) && $horizon >= 0 ){

		/* Is it cached in one of the sources: getLocalNode ($GLOBALS), getCachedNode ($db)? */
			if(!isset($route['where'])){

	    		$node = getLocalNode($route); //check $GLOBALS['nodes'] cache
	    		if($node){

		  			$query = arrayAddDistinct($node, $query);
	    		} else {

		    		$node = getCachedNode($route); //check DB node_cache
					if($node){ //load parts available in other user's languages that aren't cached

						if($node['uncached-languages']){

							$route['node_languages'] =	$node['uncached-languages'];
							$buffer = $GLOBALS['nodes'][$node['response']['table']][$node['response']['node_id']];

							$query[] = arrayMergeDistinct($node, compileNode(array('route' => $route, 'node' => $buffer)));
						} else {

							$query[] = $node;
						}
					}
	    		}
	    		unset($node);
	    	}

		/* Not available in above sources? Compile node(s) from database storage...
		$GLOBALS <- table and node array (structured as described above)
				  	- contains database table relations of current node only (for cache)
					- contains list of directly related nodes (table_name, entry_id & if node: node_id, line_id, related) */
			
			if(!$query){

				//Get content
				if(isset($route['where'])){

			    	$query = array();
					$nodes = findNodes(array('route' => $route)); //this function could be a bit more optimized (read from $GLOBALS cache)
			    	foreach( $nodes AS $node ){

		    			$buffer_route['id'] = $node['id'];
		    			$buffer_route['table'] = $node['table'];
			    		if($node['node_id'] && $node['line_id']){

			    			$buffer_route['node_id'] = $node['node_id'];
			    			$buffer_route['line_id'] = $node['line_id'];
			    		}

			    		$node = getNode(array('route' => $buffer_route));
			    		$query[] = arrayAddDistinct( $node , $query );
			    	}
				} else {

					$query[] = compileNode(array('route' => $route));
				}
			}
		}

		/*
		$node array(	'table' => $table_name, 
						'id' => $entry_id,
						'languages' => array($language_ids)
						//if node
							,'node_id' => $node_id,
						 	 'line_id' => $line_id );
		*/

		transaction(array('transaction' => $transaction));
		return $query;
    }


  	//Node's local $GLOBALS['nodes'] cache - line_id sensitive (if it exists in this cache it exists in atleast one of user's spoken languages)
  	function getLocalNode($route){

		$transaction = transaction(array('function' => __FUNCTION__));

		if( isset($route['id']) && isset($GLOBALS['nodes'][$route['table']][$route['id']]) ){ //... by table_name && entry_id

			if( isset($GLOBALS['nodes'][$route['table']][$route['id']]['node_id']) ){ //if data uses node structure

				$route['node_id'] = $GLOBALS['nodes'][$route['table']][$route['id']]['node_id'];
			} else {

				//If data doesn't use node structure and is already cached

				$query['response'] = array(	'table' => $route['table'], 
											'id' => $route['id'] );

				$query['related_nodes'] = $GLOBALS['nodes'][$route['table']][$route['id']]['related_nodes'];
			}
		}

		if( isset($route['node_id']) && isset($GLOBALS['nodes'][$route['node_id']]) ){ //... by node_id

			$route['table'] = $GLOBALS['nodes'][$route['node_id']]['table'];
			$route['id'] = $GLOBALS['nodes'][$route['node_id']]['id'];
			$route['line_id'] = (!$route['line_id']) ? $GLOBALS['nodes'][$route['table']][$route['id']]['main_line_id'] : $route['line_id'];
		}

		if( isset($route['node_id']) && isset($route['line_id']) && $GLOBALS['nodes'][$route['table']][$route['id']]['line'][$route['line_id']] ){

			//If data node line is already cached...

			$query['response'] = array(	'table' => $route['table'], 
										'id' => $route['id'],
										'node_id' => $route['node_id'],
										'line_id' => $route['line_id'] );

			$query['related_nodes'] = merge_related_nodes( 	$GLOBALS['nodes'][$route['table']][$route['id']]['related_nodes'], 
															$GLOBALS['nodes'][$route['table']][$route['id']]['line'][$route['line_id']]['related_nodes'] );

			$main_line_id = $GLOBALS['nodes'][$route['table']][$route['id']]['main_line_id'];

			if($route['line_id'] != $main_line_id){

				$query['related_nodes'] = merge_related_nodes( 	$GLOBALS['nodes'][$route['table']][$route['id']]['line'][$main_line_id]['related_nodes'],
																$query['related_nodes'] );
			}
		}

		transaction(array('transaction' => $transaction));
		return $query;
	}

    //Node's DB cache
  	function getCachedNode($route, $unsynchronized = ' = 0'){

    	$db = $GLOBALS['db'];
    	$languageList = $GLOBALS['node_languages'];

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

		foreach($languageList AS $language_id){

	   		if( $route['node_id'] && $route['line_id'] && $language_id && $route['cascade'] && $route['horizon'] ){

	   			$horizon = $route['horizon'] - $route['cascade'];
		    	$sql = "SELECT response, nodes FROM node_cache WHERE node_id = '{$route['node_id']}' ".
		    													"AND line_id = '{$route['line_id']}' ".
		    													"AND language_id = '{$language_id}' ".
		    													"AND horizon >= '{$horizon}' ".
		    													"AND time_unsynchronized {$unsynchronized} ".
		    													"ORDER BY ABS(horizon - {$horizon}) ASC LIMIT 1";
			    $result = mysqli_query($db, $sql);
			    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

			    	if(!$nodes){
			    		$nodes = array();
			    	}
			    	$nodes = merge( $nodes, json_decode($nodes) );

			    	mysqli_query($db, "UPDATE node_cache SET time_called = ".time().", usage_count = '".($row['usage_count'] + 1)."' WHERE id = '{$row['id']}'");

			    } else {
			    	
			    	$sql = "SELECT language_id FROM node_state WHERE node_id = '{$route['node_id']}' ".
			    												"AND line_id = '{$route['line_id']}' ".
			    												"AND language_id = '{$language_id}' ".
			    												"LIMIT 1";
				    $result = mysqli_query($db, $sql);
				    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

				    	$query['uncached-language'][] = $language_id;
				    }
				}
			}
		}

		if(count($query)){
			$query['response'][] = array(	'table' => $route['table'], 
											'id' => $route['id'],
											'node_id' => $route['node_id'],
											'line_id' => $route['line_id'] );
		}

		transaction(array('transaction' => $transaction));
		return $query;
  	}

    function compileNode(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

    	$languageList = $GLOBALS['node_languages'];

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; 				//node_id || table & id || where - necessary
    											//language_id (number or array) - necessary
												//line_id - optional (or main_line_id)
    											//time_state_pointer - optional
												//history - optional - (default: up to 12 state changes)

		$node = $input['node']; //table row and node counter-part are sometimes supplied to reduce load
		if(!$node){
			$node = getNodeTables(array('route' => $route));
		}

		$route['table'] = ($route['table']) ? $route['table'] : $node['table'];
		$route['id'] = ($route['id']) ? $route['id'] : $node['id'];
		$route['node_id'] = $node['node_id'];
		$route['line_id'] = ($route['line_id']) ? $route['line_id'] : $node['line_id'];

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $input['dataset']));

    	if($node['node_id'] && $route['line_id']){ //this table is using node database pattern

	  		$line = getLine(array( 'route' => $route, 'template' => $node )); //pass node tables as a template for fetching data states

	  		if($line[$route['line_id']]){ //if line exists in current language(s)

	    		if($nodes = undersigned($node)){ //store undersigned users and entities 
	    			$node['related_nodes'] = $nodes;
	    		}
				$node['node_line'] = $line;

				$node['languages'] = array();
				if($GLOBALS['nodes'][$route['table']][$route['id']]['languages']){
					$buffer_languages = $GLOBALS['nodes'][$route['table']][$route['id']]['languages'];
				}
				foreach($line[$route['line_id']]['node_state'] AS $language_id => $state){
					$node['languages'] = arrayAddDistinct($language_id, $node['languages']);
				}

				$query = array(	'table' => $route['table'], 
								'id' => $route['id'],
								'languages' => $node['languages'],
								'node_id' => $route['node_id'],
								'line_id' => $route['line_id'] );

				$GLOBALS['updateNodeCache'] = arrayAddDistinct($query);

				if($buffer_languages){
					$node['languages'] = arrayMergeDistinct($node['languages'], $buffer_languages);
					$query = array(	'table' => $route['table'], 
									'id' => $route['id'],
									'languages' => $node['languages'],
									'node_id' => $route['node_id'],
									'line_id' => $route['line_id'] );
				}

				print_r($node);

				//Dispatch node to local $GLOBALS['nodes'] cache
				if($GLOBALS['nodes'][$route['table']][$route['id']]){
					$GLOBALS['nodes'][$route['table']][$route['id']] = arrayMergeDistinct($node, $GLOBALS['nodes'][$route['table']][$route['id']]);
				} else {
					$GLOBALS['nodes'][$route['table']][$route['id']] = $node;
				}
				if(!isset($GLOBALS['nodes'][$route['node_id']])){ //alias
		    		$GLOBALS['nodes'][$route['node_id']]['table'] = $route['table'];
		    		$GLOBALS['nodes'][$route['node_id']]['id'] = $route['id'];
				}
	    	}

    	} elseif( $node['id'] && !$node['node_id'] ){ //this table item isn't using node database pattern

			if(!$node['language_id'] || ($node['language_id'] && in_array($node['language_id'], $languageList))){

	    		if($nodes = undersigned($table)){ //store undersigned users and entities 
	    			$node['related_nodes'] = $nodes;
	    		}

				if($node['language_id']){
					$query = array(	'table' => $route['table'], 
									'id' => $table['id'],
									'languages' => array($node['language_id']) );
					$node['languages'] = array($node['language_id']);
				} else {
					$query = array(	'table' => $route['table'], 
									'id' => $table['id'] );
				}

				//Dispatch node to local $GLOBALS['nodes'] cache
				if(!$GLOBALS['nodes'][$route['table']][$route['id']]){
					$GLOBALS['nodes'][$route['table']][$route['id']] = $node;
				}
			}
		}

		transaction(array('transaction' => $transaction));
		return $query;
    }

    function getNodeTables(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; //node_id || table_name & entry_id || where - necessary

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if( ($route['table'] && $route['id']) ){
    		
    		if($route['node_id']){ //load content node

				$sql = 	"SELECT id AS node_id, table_name, entry_id, main_line_id FROM node WHERE id = '{$route['node_id']}'";

    		} else {

	    		$sql_where[] = "id = '{$route['id']}'";
	 			$sql = 	"SELECT *, id AS {$route['table']}_id  FROM {$route['table']} WHERE ".
	  					implode(' AND ', $sql_where);
	    	}

			$result = mysqli_query($db, $sql);
			$i = 0;
    		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

    			if($route['node_id']){

    				$node = $row;
    				$node['table'] = $node['table_name'];
    				unset($node['table_name']);

				 	$sql = 	"SELECT *, id AS {$node['table']}_id  FROM {$node['table']} WHERE ".
			    	  		"id = '{$node['entry_id']}'";

					$table = mysqli_query($db, $sql);
				    if($table = mysqli_fetch_array($table, MYSQLI_ASSOC)){

						$node = arrayMergeDistinct($node, $table);
					}

    			} else {

    				$node = $row;
    			}
			}
		}

		transaction(array('transaction' => $transaction));
		return $node;
    }

    function getLine(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        //Function router
    	$route = 										$input['route'];
        $input['dataset'] = $dataset =					(!$input['dataset']) ? '*' : $input['dataset'];

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $input['dataset']));

        if( ($route['line_id']) ){

        	//Line - current line, root line and line tied to
        	$line_id = ($route['line_id']) ? $route['line_id'] : $route['main_line_id'];

	 		$sql_where[] = "id = '{$line_id}'"; //current line
	 		if($dataset = '*' or $dataset = 'rooted'){
	 			$sql_where[] = "root_line_id = '{$line_id}'"; //line that is rooted in current line
	 		}
	 		if($dataset = '*' or $dataset = 'tied'){
	 			$sql_where[] = "tie_line_id = '{$line_id}'"; //line that is tied to current line
	 		} 			
 		 	$sql = "SELECT *, id AS line_id FROM node_line WHERE ".implode(' OR ', $sql_where)." ORDER BY id = {$line_id} DESC, id DESC";

			$result_lines = mysqli_query($db, $sql);
			$c = 0; //cascade route ID (linked nodes)
 		    while($row_line = mysqli_fetch_array($result_lines, MYSQLI_ASSOC)){

				if($line_id == $row_line['id']){ //current line id
					$query[$line_id] = $row_line;

				} elseif($line_id == $row_line['root_line_id']){ //Cascading - a line is rooted in current line
					$query[$line_id]['root'][] = array('node_id' => $row_line['node_id'], 'line_id' => $row_line['id']);

				} elseif($line_id == $row_line['tie_line_id']){ //Cascading - a line is tied to current line
					$query[$line_id]['tie'][] = array('node_id' => $row_line['node_id'], 'line_id' => $row_line['id']);
				}
			}

			if($query[$line_id]['id']){

				$response['state'] = getState( array('route' => $route, 'template' => $input['template']) );
				if( count($response['state'])  ){ //if line is unavailable in user's languages...

					$query[$line_id]['related_nodes'] = array();

		    		if($undersigned = undersigned($row_line)){ //load undersigned users and entities to $GLOBALS['nodes']
		    			$query[$line_id]['related_nodes'] = $undersigned['related_nodes']; //store a list of related users
		    		}

		    		//Load these datasets with all nodes
	    			/*$response['node_circle'] = getCirclesBy( array('route' => array( 	'node_id' => $route['node_id'], 
	    																				'line_id' => $row_line['id'])) );

	    			$response['node_reflection'] = getReflections( array('route' => array( 	'node_id' => $route['node_id'], 
	    																						'line_id' => $row_line['id'])) );*/

					//Load table-specific datasets - $dataset = ('*' || array('something', ...));
	    			$function = $route['table'].'Line';
		    		if(function_exists($function)){
		    			$buffer = $function(array('route' => $route, 'dataset' => $dataset)); //call {$table}Line() function
		    			$response = arrayMergeDistinct($response, $buffer);
		    		}

					foreach($response AS $name => $set){
						if($set){
							$query[$line_id][$name] = $set;
							if($set['related_nodes']){
		    					$query[$line_id]['related_nodes'] = arrayMergeDistinct($query[$line_id]['related_nodes'], $set['related_nodes']);
		    				}
						}
					}

					//Nodes related to current line
					if($related_nodes = relatedToLine( $query[$line_id], $route )){
						$query[$line_id]['related_nodes'] = arrayMergeDistinct($query[$line_id]['related_nodes'], $related_nodes);
					}

	    		} else {
	    			unset($query);
	    		}
			} else {
				unset($query);
			}
		}

		transaction(array('transaction' => $transaction));
    	return $query;
    }

    function relatedToLine($line_row, $row){

		$transaction = transaction(array('function' => __FUNCTION__));

		if($line_row['root_node_id']){ //line rooted to
			$nodes[] = array( 'node_id' => $line_row['root_node_id'], 'line_id' => $line_row['root_line_id'] );
		}

		if($line_row['tie_node_id']){ //line tied to
			$nodes[] = array( 'node_id' => $line_row['tie_node_id'], 'line_id' => $line_row['tie_line_id'] );
		}

		foreach($line_row['rooted'] AS $rooted){ //lines rooted in current line
			$nodes[] = array( 'node_id' => $rooted['node_id'], 'line_id' => $rooted['line_id'] );
		}

		foreach($line_row['tied'] AS $tied){ //lines tied to current line
			$nodes[] = array( 'node_id' => $tied['node_id'], 'line_id' => $tied['line_id'] );
		}

		if( count($routes) > 0 ){
			$related_nodes = array();
			foreach( $nodes AS $node ){

				$related_route['node_id'] = $node['node_id'];
				$related_route['line_id'] = $node['line_id'];

				if(isset($route['cascade'])){
					$related_route['cascade'] = $route['cascade'];
				}
				if(isset($route['horizon'])){
					$related_route['horizon'] = $route['horizon'];
				}

				$node = getNode(array('route' => $related_route));
				$related_nodes = arrayAddDistinct($node, $related_nodes);
			}
		}

		transaction(array('transaction' => $transaction));
		return $related_nodes;
    }

    function getState(){ //within a content line there's a trail of content states, history of changes

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

    	$route = $input['route'];
    	$route['node_languages'] = (!$route['node_languages']) ? $GLOBALS['node_languages'] : $route['node_languages']; //language_id

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

		if( $route['node_id'] && $route['line_id'] && $input['template'] && is_array($route['node_languages']) ){

			$sql_where[] = "node_id = '{$route['node_id']}'";
			$sql_where[] = "line_id = '{$route['line_id']}'";

			foreach($route['node_languages'] AS $language_id){

				$sql_where['language_id'] = "language_id = '{$language_id}'";

				//Get recent content state for each field
	    		foreach($input['template'] AS $field => $content){

	    			if(!in_array($field, array('id', 'node_id', 'main_line_id', 'table', 'entry_id', 'created_by_user_id', 'created_by_entity_id', 'time_created', 'time_updated', 'closed_by_user_id', 'closed_by_entity_id', 'time_closed', 'removed_by_user_id', 'removed_by_entity_id', 'removed_time'))){

	    				$sql_where['field'] = "field = '{$field}'";

					 	$sql = 	'SELECT * FROM node_state WHERE '.implode(' AND ', $sql_where).' ORDER BY current DESC, id DESC LIMIT 1';

						$result = mysqli_query($db, $sql);
						if($content = mysqli_fetch_array($result, MYSQLI_ASSOC)){

				    		$query[$language_id][$field] = $content;

				    		if($nodes = undersigned($content)){
				    			$query[$language_id]['related_nodes'] = arrayAddDistinct($query[$language_id]['related_nodes'], $nodes);
				    		}
					    }
					}
		    	}
		    }
		}

		print_r($query);

		transaction(array('transaction' => $transaction));
    	return $query;
    }

    function getHistory(){
    	//TO-DO: Node line trail of changes
    }

    function getTranslationRequest(){

    }

  	function undersigned($row){

		$transaction = transaction(array('function' => __FUNCTION__));

  		//Fields containing user_id, entity_id are parsed with this

	    $nodes = array();

	    //Load only table and node (without datasets)
        $route['cascade'] = 0;
        $route['horizon'] = 0;

  		foreach($row as $field => $id){
	    	if($id){

		      	if( strpos('user_id', $field) ){
		          	/*$node = getUser( array('route' => array('user_id' => $id)) );
					$nodes = arrayAddDistinct($node, $nodes);*/
					//echo $id;
		      	} //+entity
	    	}
	    }

		transaction(array('transaction' => $transaction));
	    return $nodes;
  	}

  	function updateNodeCache($route, $query){

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

  		if( $route['node_id'] && $route['line_id'] && $route['language_id'] &&
  			$route['cascade'] && $route['horizon'] && 
  			$query['response'] && $query['nodes'] && $query['relations'] ){

   			$max_horizon = $route['horizon'] - $route['cascade'];

   			if(is_array($route['language_id']))

   			$response = array();
	    	$nodes = json_encode($partial['nodes']);
	    	$relations = json_encode($partial['relations']);

	    	$timestamp = time();

	    	if($node = getCompiledNode($route, ' > 0')){ //update unsynchronized node cache

		    	if($node['relations'] != $relations || $node['nodes'] != $nodes){
	              	$sql = "UPDATE node_cache SET    relations = '{$relations}', ".
			              							"nodes = '{$nodes}', ".
			              							"time_unsynchronized = 0, ".
			              							"time_updated = {$timestamp} ".
			              							"WHERE id = '{$route['node_id']}'";
		    	} else {
	              	$sql = "UPDATE node_cache SET time_unsynchronized = 0, time_updated = {$timestamp} WHERE id = '{$route['id']}'";
	            }
		    } else {

	          	$sql = "INSERT INTO node_cache (time_created, time_updated, node_id, line_id, language_id, horizon, response, nodes, relations, time_unsynchronized) VALUES (".
			          							"'{$timestamp}', ".
			          							"'{$timestamp}', ".
			          							"'{$route['node_id']}', ".
			          							"'{$route['line_id']}', ".
			          							"'{$route['language_id']}', ".
			          							"'{$horizon}', ".

			          							"'{$response}', ".
			          							"'{$nodes}', ".
			          							"'{$relations}', ".
			          							"0); ";
			}

			if($sql){
		    	mysqli_query($db, $sql);
			}
  		}

		transaction(array('transaction' => $transaction));
		return $query;
  	}

    function findNodes(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; //node_id || table_name & entry_id || where - necessary

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if( $route['table'] && is_array($route['where']) ){ //search for entry_id by various parameters

			foreach($route['where'] AS $key => $array){
				if(is_array($array)){
					foreach($array AS $field => $value){
						$sql_or[] = "{$field} = '{$value}'";
					}
					$sql_where[] = '('.implode(' OR ', $sql_or).')';
				} else {
					$sql_where[] = "{$key} = '{$array}'";
				}
			}

 			$sql = 	"SELECT *, id AS {$route['table']}_id  FROM {$route['table']} WHERE ".
  					implode(' AND ', $sql_where);

			/*
			Output style:
			$node array(	'table' => $table_name, 
							'id' => $entry_id,
							//if node
								,'node_id' => $node_id,
							 	 'line_id' => $line_id );
			*/

			$result = mysqli_query($db, $sql);
			$i = 0;
    		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

				$query[$i]['table'] = $route['table'];
				$query[$i]['id'] = $row['id'];

				$sql = 	"SELECT id, main_line_id FROM node WHERE table_name = '{$route['table']}' AND entry_id = '{$row['id']}'";

				$node_result = mysqli_query($db, $sql);
			    if($node = mysqli_fetch_array($node_result, MYSQLI_ASSOC)){

					$query[$i]['node_id'] = $node['id'];
					$query[$i]['line_id'] = $node['main_line_id'];
				}

				$i++;
			}
		}

		transaction(array('transaction' => $transaction));
		return $query;
    }

/*
//Sample script

getNearby
	find nearby data
	foreach row

		getNode (example: 'user')
		(
			getGlobalsNode? getCompiledNode? no?
			cascading: 0
			horizon: 1

			languageList check? language_id?
			table (+undersigned)
			node (+undersigned)
				foreach languageList as language_id
					cascading < horizon? dataset
					line (+undersigned)
						state (+undersigned)
						cascading < horizon? dataset
						relations
			relations

			cascading 0? !globals['nodes']? !node db cache? compileNode (by language_id)
		)

			dataset
				getNode (example: 'user_circle')
				(
					getGlobalsNode? getCompiledNode? no?
					cascading: 1

					table (+undersigned)
					node (+undersigned)
						foreach languageList as language_id
							cascading < horizon? dataset
							line (+undersigned)
								state (+undersigned)
								cascading < horizon? dataset
				)

*/
?>