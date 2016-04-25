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
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        $route = 					(!isset($input['route'])) ? null : $input['route'];
        //More than one way function flow goes
		$route['circle_id'] =		(!$route['circle_id']) ? null : ksort($route['circle_id']);
        $route['dataset'] = 		(!$route['dataset']) ? '*' : ksort($route['dataset']);

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

        $block = 					(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 	(!$block['transaction']) ? $transaction : $block['transaction'];

	    if($user_id || $entity_id){

		    if(!$buffer = existingCacheBlock($transaction){

		        $sql_from[] = 	'circle';
		        $sql_select[] = 'circle.*, circle.id AS circle_id, '.
	            				'user_id AS circle_user_id, entity_id AS circle_entity_id, '.
	            				'removed_by_user_id AS circle_removed_by_user_id, removed_by_entity_id AS circle_removed_by_entity_id, '.
	            				'time_removed AS circle_time_removed';

		    	if($route['circle_id']){ //Get circles by circle_id (can be an array)

		    		if(is_array($route['circle_id'])){
		    			foreach($route['circle_id'] AS $circle_id){
		    				$sql_circle[] = "id = '{$circle_id}'";
		    				$block['relations']['circle.circle_id'] = $route['circle_id'];
		    			}
		    		} else {
		    			$sql_circle[] = "id = '{$route['circle_id']}'";
		    			$block['relations']['circle.circle_id'] = $route['circle_id'];
		    		}
		    		$sql_where[] = '(' . implode(' OR ', $sql_circle) . ')';

		    	}
		    	if($route['branch_id']){ //... by content branch_id

		            $sql_select[] = 'content_circle.*, content_circle.id AS content_circle_id, '.
		            				'content_circle.user_id AS content_circle_user_id, content_circle.entity_id AS content_circle_entity_id, '.
		            				'content_circle.removed_by_user_id AS content_circle_removed_by_user_id, '.
		            				'content_circle.removed_by_entity_id AS content_circle_removed_by_entity_id, '.
		            				'content_circle.time_removed AS content_circle_time_removed';

		        	$sql_from[] = 	'content_circle, content_branch';
		           	
		            $sql_where[] =	"content_circle.branch_id = '{$route['branch_id']}' AND ".
		            				'content_branch.id = content_circle.branch_id';

				    $block['relations']['content_circle.branch_id'] = $route['content_id'];

		    	}
		    	if($route['site_id']){ //... by site_id

		            $sql_select[] = 'site_circle.*, site_circle.id AS site_circle_id, '.
		            				'site_circle.user_id AS site_circle_user_id, site_circle.entity_id AS site_circle_entity_id, '.
		            				'site_circle.removed_by_user_id AS site_circle_removed_by_user_id, '.
		            				'site_circle.removed_by_entity_id AS site_circle_removed_by_entity_id, '.
		            				'site_circle.time_removed AS site_circle_time_removed';

		        	$sql_from[] = 	'site_circle';
		                      			
		            $sql_where[] =	"site_circle.site_id = '{$route['site_id']}' AND ".
		                      		'site_circle.circle_id = circle.id';

		    		$block['relations']['site_circle.site_id'] = $route['site_id'];

		    	}
		    	if($route['user_id'] || $route['entity_id']){ //... either by user_id or by entity_id

		            $sql_select[] = 'circle_commoner.*, circle_commoner.id AS circle_commoner_id, '.
		            				'user_id AS circle_commoner_user_id, entity_id AS circle_commoner_entity_id, '.
		            				'removed_by_user_id AS circle_commoner_removed_by_user_id, removed_by_entity_id AS circle_commoner_removed_by_entity_id, '.
		            				'time_removed AS circle_commoner_time_removed';

		        	$sql_from[] = 	'circle_commoner';
		                      			
		            $sql_where[] =	'circle_commoner.circle_id = circle_id';

		    	}
		    	if($route['user_id']){ //... by user_id

		            $sql_where[] =	"circle_commoner.commoner_user_id = '{$route['user_id']}'";

		    		$block['relations']['circle_commoner.commoner_user_id'] = $route['user_id'];

		    	}
		    	if($route['entity_id']){ //... by entity_id

		    		$sql_where[] =	"circle_commoner.commoner_entity_id = '{$route['entity_id']}'";

		    		$block['relations']['circle_commoner.commoner_entity_id'] = $route['entity_id'];

		    	}
		    	
	        	$sql = 'SELECT ' . implode(', ', $sql_select) .' FROM ' . implode(', ', $sql_from) .' WHERE ' . implode(' AND ', $sql_where);
		      	$result = mysqli_query($db, $sql);

		      	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC) && isAvailable($row)){

		      		//Get circle info, commoners, privileges & translations
		      		$buffer['state'][$row['circle_id']] = 'getCircle';
					$buffer = getCircle(array('route' => $route, 'block' => $buffer));
		        }

				//Update cache with current block if calling function didn't pass state
				if(!$block['state'] && !in_array(array('status_code' => '400'), $buffer['state'])){
		    		updateCacheBlock($block);
		    	}
			}

	  		$block = mergeBlocks('getCircles', $block, $buffer); //Merge current block with one delivered by calling function
	        transaction(array('function' => __FUNCTION__)); //End current function's transaction

	    	return $block;
	    }
  	}

	function getCircle(){ //Shares block stream with getBranches and availablePrivileges

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        $route = 					(!isset($input['route'])) ? null : $input['route'];
        //More than one way function flow goes
        $route['dataset'] = 		(!$route['dataset']) ? '*' : ksort($route['dataset']);

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

        $block = 					(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 	(!$block['transaction']) ? $transaction : $block['transaction'];

		if($route['circle_id']){

		    if(!$buffer = existingCacheBlock($transaction)){

		    	//Circle's details
		    	$buffer['state'] = 'getContent';
		    	$buffer = getContent( array('route' => array('table_name' => 'circle', 'entry_id' => $route['circle_id']), 
		    								'block' => $buffer) );

				//Type
				$buffer['state']['type'] = 'getContent';
		    	$buffer = getContent( array('route' => array('table_name' => 'circle_type', 'entry_id' => $buffer['state']['type_id']),  
		    								'block' => $buffer) );

		    	if(in_array('commoners', $route['dataset']) || $route['dataset'] = '*'){
		    		$buffer['state']['commoners'] = 'getCommoners';
				    $buffer = getCommoners(	array('route' => $route, 
		    									  'block' => $buffer) );
			    }

				//Update cache with current block if calling function didn't pass state
				if(!$block['state'] && !in_array(array('status_code' => '400'), $buffer['state'])){
		    		updateCacheBlock($block);
		    	}
			}

	  		$block = mergeBlocks('getCircle', $block, $buffer); //Merge current block with one delivered by calling function
	        transaction(array('function' => __FUNCTION__)); //End current function's transaction

	    	return $block;
		}
	}

	function getCommoners($db, $circle_id, $check_privileges_user_id = false, $return_cache = false){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

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

        $input = func_get_args();

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