<?php

    function isAvailable(){ //returns if data state (table) is available, taking into account removals and read privilege set by author(s), circle(s) and possibly, encryption

    	//If this was a distributed blockchain database, data could be distributed among users running their nodes (in turn defining availability)
    	//Idea: if data was encrypted, engagement within a circle could unlock public keys for content decryption (perhaps through enacted values?)

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

    	//Data node !!! this part is marked to be affected by (d)encryptions of data by users / entities (private-public key pairs, partial public keys)
    	if($row = $input['row']){
	      	if($node['time_removed'] == 0 && $input['circles']){ //privileges for removed data are different (sufficient?)
	      		return true;
	      	} else {
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
	}

    function getNode(){ //compiling a specific state of data for a content node (possibly also seeking a historical trail of states)

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        //Function router
    	$route = ksort($input['route']); 	//language_id - necessary
    										//(node_id || table_name & entry_id), (branch || branch_id), state_id - one of them needs to be called
    										//history - optional - how many changes show be returned? (default is 12: 0,12)
  
    										//circle_id - optional 

    	//Dataset - which content datasets should be returned? - optional, default try all available
        $input['dataset'] = (!$input['dataset']) ? '*' : ksort($input['dataset']); 

        $node['transaction'] = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if(($user_id || $entity_id) && ($route['node_id'] || ($route['table_name'] && $route['entry_id'])){

		    if(!$node = existingCacheBlock($node['transaction'])){ //isset($input['parent-cache'])... does this make sense in terms of optimization?

				//Load content node
				$sql = 	"SELECT *, id, AS node_id FROM node WHERE ";
			   	$sql.= 	($route['node_id']) ? "id = '{$route['node_id']}'" : "table_name = '{$route['table_name']} AND entry_id = '{$route['entry_id']}'";

				$node_result = mysqli_query($db, $sql);
	 		    if($node_row = mysqli_fetch_array($node_result, MYSQLI_ASSOC))){

					//Get main content table, and recognize initiating user_id / entity_id (as well as closing - a new word to replace "removing")
	  			 	$sql = 	"SELECT *, id AS {$node['table_name']}_id  FROM {$node['table_name']} WHERE ".
	 		    	  		"id = '{$node['entry_id']}'";

					$table = mysqli_query($db, $sql);
		 		    if($table_row = mysqli_fetch_array($table, MYSQLI_ASSOC)){ //pass this table as a layout for fetching states

						if(isAvailable('row' => $table_row)){

							$node['state'][$node_row['id']] = $node_row;
							$node['state'][$node_row['id']]['table'] = $table_row;
							$node['state'][$node_row['id']]['status_code'] = '200';

							$node['cache-relations']['{$route['table_name']}.id'] = $node['entry_id'];
					    	$node['cache-relations']['node.id'] = $node_row['id'];

					    	$route['table'] = $table_row;

					  		//Get content branches & optional filter by branch_id
					  		$branches = getBranches(array( 'route' => $route, 'parent-cache' ));
				 			$node = mergeCache($node, $branches);
				 			$node['state'][$node_row['id']]['branches'] = $branches['state'];

						} else {
							$node['state'][$node_row['id']]['status_code'] = '400';
						}
		  			} else {
		  				$node['state'][$route['node_id']]['status_code'] = '400';
 		  			}
				}

  				//Update cache with state(s) of content - if calling function didn't set parent-cache and everything else went okay
  				if(!isset($input['parent-cache']) && is_array($node['cache-relations']) && !in_array(array('status_code' => '400'), $node['state'])){
		    		updateCacheBlock($node);
		    	}
  			}

  		    transaction(array('transaction' => $node['transaction']));

			return $node;
		}
    }

    function getBranches(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        //Function router
    	$route = ksort($input['route']); 	//
    										//node_id, branch_id, state_id - one of them needs to be called
    										//history - optional - how many states to return from current branch?
    										//dataset - optional - which content datasets should be returned?

    										//circle_id - optional         $route['node_id'] =				(!$route['node_id']) ? null : $route['node_id'];

        $route['dataset'] =					(!$route['dataset']) ? '*' : ksort($route['dataset']); //optional - which content datasets should be returned?

        $node['transaction'] = transaction(array('function' => __FUNCTION__, 'route' => $route));

        if(($user_id || $entity_id) && ($route['node_id'] || $route['branch_id'])){

     		//Branch - rooted in and tied to
 			$sql_where_root[] = ($route['node_id']) ? "root_node_id = '{$route['node_id']}'" : '1 = 1';
 			$sql_where_root[] = ($route['branch_id']) ? "root_branch_id = '{$route['branch_id']}'" : '1 = 1';

 			$sql_where_tie[] = ($route['node_id']) ? "tie_node_id = '{$route['node_id']}'" : '1 = 1';
 			$sql_where_tie[] = ($route['branch_id']) ? "tie_branch_id = '{$route['branch_id']}'" : '1 = 1';
 		 	$sql = "SELECT *, id AS branch_id FROM content_branch WHERE ".implode(' AND ', $sql_where);

			$result_branches = mysqli_query($db, $sql);
 		    while($row_branch = mysqli_fetch_array($result_branches, MYSQLI_ASSOC)){

 		    	$buffer_circles = getCirclesBy(array('route' => array('branch_id' => $route['branch_id'])));

 		    	if(isAvailable(array('row' => $row_branch, 'circles' => $buffer_circles))){
					
					$node['state'][$row_branch['id']] = $row_branch;
					$node['state'][$row_branch['id']]['circles'] = $buffer_circles;
					$node['cache-relations']['node_branch.id'] = $row_branch['id'];

			      //States & translations within branch
					if($route['dataset'] == '*' || in_array('getStates', $route['dataset'])){
						$route['branch_id'] = $row_branch['id'];
						$node['state'][$row_branch['id']]['states'] = getStates(array('route' => $route));
					}

				} else {
					$node['state'][$row_branch['id']]['circles']['status_code'] = '400';
				}
			}
    	}

        $transaction = transaction(array('function' => __FUNCTION__));

    	return $node;
    }

    function getStates(){ //Within a branch there's a historical trail of content states

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

        $input = func_get_args();
    	$route = $input['route'];

    	$route['language_id'] = 	(!$route['language_id']) ? $GLOBALS['language_id'] : $route['language_id'];
        $route['history_id'] =		(!$route['history_id']) ? '0,12' : $route['history_id'];

        $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if($route['state_id']){

        		if($route['table']){
        			foreach($route['table'] AS $field => $value){
		         		//Get content states trail for each field
					    $sql = 	"SELECT * FROM content_state WHERE ".
					    		"language_id = '{$route['language_id']}' AND branch_id = '{$route['branch_id']}' AND ".
					    		"field = '{$field}' AND id <= '{$route['state_id']}' ".
					    		"ORDER BY id DESC LIMIT {$history}";


        			}
        		}

        		foreach($node['state'] AS $field => $value){

        			if($field)
        		}



			    //Get localized content state(s)
		        $node['state'][ $row_translation['field'] ][ $GLOBALS['languages'][$row['language_id'] ]['code']] = $row_translation;

	    		/*
					-keywords, values and reflections go into state here (time component: are they available in state's current time?)
					-
		        */
			}
    	}

        $transaction = transaction(array('transaction' => $transaction));

    	return $block;
    }

  //Translate content by automation (if main branch edit isn't in )


?>