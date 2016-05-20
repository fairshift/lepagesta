<?php
//Circle is common grounds, encircling purposes, storylines and rules of engagement
  /*
   *  Technically speaking, it's a pool of contextually related data shared among services - sites which use the API
   *  This allows multiple applications on top of existing data (as is going to be the case with services living on the blockchain)
   */

  /*
	User requests a content...
	a) s/he came through a direct link
	c) s/he clicked on it in circles stream
	d) s/he found it through other content
  */

	//Idea: Reflections from current circle could be complementing privileges in circles - showcasing implicit rules of engagement with appreciated gestures

 	function getCirclesBy(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

        $input = func_get_args()[0];

        $route = 		(!isset($input['route'])) ? null : $input['route'];
        $dataset = 		(!$input['dataset']) ? '*' : $input['dataset'];

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $dataset));

	    if($user_id || $entity_id){

	    	if($route['circle_id']){ //Get circles by circle_id (can be an array)

	    		if(is_array($route['circle_id'])){
	    			foreach($route['circle_id'] AS $buffer_route['circle_id']){

			      		//Get circle details, commoners, privileges
			      		$buffer = getCircle(array('route' => $buffer_route));
						$query[$buffer['response']['id']] = $buffer;
		      		}
	    		} else {

		      		//Get circle details, commoners, privileges
		      		$buffer_route['circle_id'] = $route['circle_id'];
		      		$buffer = getCircle(array('route' => $buffer_route));
					$query[$buffer['response']['id']] = $buffer;
		      	}
	    	} else {
		    	if($route['line_id']){ //... by content line_id

		            $sql_select[] = 'node_circle.*, node_circle.id AS node_circle_id';
		        	$sql_from = 	'node_circle';
		            if($route['node_id']){
		           		$sql_where[] =	"node_circle.node_id = '{$route['node_id']}'";
		            }
		            $sql_where[] =	"node_circle.line_id = '{$route['line_id']}'";
				    $query['relations']['node_circle.line_id'] = $route['line_id'];
		    	}
		    	if($route['site_id']){ //... by site_id

		            $sql_select[] = 'site_circle.*, site_circle.id AS site_circle_id';
		        	$sql_from = 	'site_circle';
		            $sql_where[] =	"site_circle.site_id = '{$route['site_id']}'";
		    		$query['relations']['site_circle.site_id'] = $route['site_id'];
		    	}
		    	if($route['user_id'] || $route['entity_id']){ //... either by user_id or by entity_id

		            $sql_select[] = 'circle_commoner.*, circle_commoner.id AS circle_commoner_id';
		        	$sql_from = 	'circle_commoner';
		    	}
		    	if($route['user_id']){ //... by user_id

		            $sql_where[] =	"circle_commoner.commoner_user_id = '{$route['user_id']}'";
		    		$query['relations']['circle_commoner.commoner_user_id'] = $route['user_id'];
		    	}
		    	if($route['entity_id']){ //... by entity_id

		    		$sql_where[] =	"circle_commoner.commoner_entity_id = '{$route['entity_id']}'";
		    		$query['relations']['circle_commoner.commoner_entity_id'] = $route['entity_id'];
		    	}

	        	$sql = 'SELECT ' . implode(', ', $sql_select) .' FROM ' . implode(', ', $sql_from) .' WHERE ' . implode(' AND ', $sql_where);
		      	$result = mysqli_query($db, $sql);
		      	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		      		//Get circle details, commoners, privileges
		      		$buffer_route['circle_id'] = $row['circle_id'];
		      		$query = array_merge($query, getCircle(array('route' => $buffer_route, 'parent-transaction' => $query['transaction'])));
		        }
	    	}

				//Update cache with state(s) of content - if calling function didn't set parent-cache and everything else went okay
				if(!isset($input['parent-transaction']) && is_array($query['cache-relations']) && !in_array(array('status_code' => '400'), $query['response'])){
	    		updateCache($query);
	    	}
	    }

        transaction(array('transaction' => $transaction)); //End current function's transaction

    	return $query;
  	}

	function getCircle(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        $route = 		(!isset($input['route'])) ? null : $input['route'];
        $dataset = 		(!$input['dataset']) ? '*' : $input['dataset'];

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $dataset));

		if(($user_id || $entity_id) && $route['circle_id']){

	    	//Circle's details
	    	$query = array_merge($query, getNode(array('route' => array('table' => 'circle', 'id' => $route['circle_id'])) ));
			$node_id = current($query['response']);
			$line_id = $query['response'][$node_id]['line_id'];

			//Circle type
			$type_id = $GLOBALS['nodes'][$node_id]['line'][$line_id][]; // !!!
			$query = array_merge($query, getNode(array('route' => array('table' => 'circle_type', 'id' => $query['response']['type_id']));

	    	if(in_array('commoners', $route['dataset']) || $route['dataset'] = '*'){
	    		$query = array_merge( $query, getCommoners(array( 'route' => $route )) );
		    }

		}

        transaction(array('transaction' => $transaction));
    	return $block;
	}

	function getCommoners(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        $route = 					(!isset($input['route'])) ? null : $input['route'];

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

        $block = 					(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 	(!$block['transaction']) ? $transaction : $block['transaction'];

		if(($user_id || $entity_id) && $route['circle_id']){

		    if(!$response = existingCache($db, $cache)){

				$sql = "SELECT *, circle_commoner.id AS circle_commoner_id FROM circle_commoner ".
					   "WHERE circle_id = '{$route['circle_id']}'";

				if($route['user_id']){
					$sql.= " AND commoner_user_id = '{$route['user_id']}'";
				} else if($route['entity_id']){
					$sql.= " AND commoner_entity_id = '{$route['entity_id']}'";
				} else {
					$sql.= " ORDER BY time_confirmed DESC";
				}

			    $result = mysqli_query($db, $sql);
		        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC) && isAvailable($row)){

	    			if($row['commoner_user_id']){
	    				$buffer['relations']['circle_commoner.commoner_user_id'] = $row['commoner_user_id'];
		        		$buffer['state']['users'][$row['commoner_user_id']] = $row;
	    			}
	    			if($row['commoner_entity_id']){
	    				$buffer['relations']['circle_commoner.commoner_entity_id'] = $row['commoner_entity_id'];
		        		$buffer['state']['entities'][$row['commoner_entity_id']] = $row;
	    			}

		        	if(!$privileges_user_id){
			        	$response[$row['user_id']]['user'] = getUser($db, $row['user_id'], 'user_id', array('avatar'));
				    	$cache['dataview']['circle_commoner.user_id'] = $user_id;
		        	}
				}

				//Update cache with current block if calling function didn't pass state
				if(!$block['state'] && !in_array(array('status_code' => '400'), $buffer['state'])){
		    		updateCacheBlock($block);
		    	}
			}

	  		$block = mergeBlocks('getCommoners', $block, $buffer); //Merge current block with one delivered by calling function
	        transaction(array('function' => __FUNCTION__)); //End current function's transaction

			return $block;
		} 
	}

  	/*function encircleContent(){ //Adding content to circle
  		
		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = func_get_args()[0];

        $route = $input['route'];
        //Content to encircle
        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id']; //optional call of content branch_id
        $route['branch'] = 					(!$route['branch']) ? null : $route['branch']; //optional call of content branch
        //Circle id is required
        $route['circle_id'] =				(!$route['circle_id']) ? null : $route['circle_id'];

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 			(!$block['transaction']) ? formatTransaction(__FUNCTION__, $route) : $block['transaction'];
    	$block['transaction_time'] = 		(!$block['transaction_time']) ? microtime() : $block['transaction_time'];
  		//$block['dataview'];
    	$block['state'] = 					(!$block['state']) ? null : $block['state'];

  		if($user_id && $route['circle_id']){


	        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id'];
	        $route['branch'] = 					(!$route['branch']) ? null : $route['branch'];
			if(getBranches(array('route' => $route), row_table)){

			getBranch()


  			foreach($circles AS $circle_id){
				if($circle = getCircle($db, $user_id, 'circle', $row['circle_id'], true)){
					$response[$circle_id] = $circle;
				} else {
					$response[$circle_id]['status_code'] = '400';
				}
  			}
  		}
  	}*/
?>