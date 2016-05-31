<?php

  //Create / update content node - lines, (localized) states, values, keywords, words (histogram), 
    function updateNode(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; 	//node_id || (table_name & entry_id)
    								//line_id, state_id
    	$route['language_id'] = (!$route['language_id']) ? $GLOBALS['language_id'] : $route['language_id'];
    	$route['time_now'] = time();
    	$route['nodeOff'] = (!$route['nodeOff']) ? false : true;
    	/*exceptions (with a different handling): block, cache, entity, portal_place, server_cronjob, site_namespace, user, user_language, user_message, value_system, word, word_synonim*/

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

       	//Data state to entangle within a content node
        $query['update'] = $input['update'];

	    if($route['node_id'] || ($route['table_name'] && $route['entry_id'])){

			//Get existing content node
			if( ($route['node_id'] || ($route['table_name'] && ($route['entry_id'] && is_array($route['entry']))) ){

				$query['route'] = $route;
				$query['route']['dataset'] = '*';
				$query['route']['history'] = 1;
				$query['route']['cascade'] = 2;

				$query = getNode(array('route' => $route)); //query original content node (to check for difference later on)

				if( isset(current($query['response'])['node_id']) ){

					$nodeOff = false;
					$action = 'update';

					$route['node_id'] = current($query['response'])['node_id'];
					$route['table_name'] = $query['response'][$route['node_id']]['table_name'];
					$route['entry_id'] = $query['response'][$route['node_id']]['entry_id'];
					$route['line_id'] = $query['response'][$route['node_id']]['line_id']; //chosen $route['line_id'] or 'main_line_id'

					$node = $GLOBALS['nodes'][$route['node_id']]; //store original content node (to check for difference later on)

				} elseif( isset($query['response'][$route['table_name']]) ){

					$nodeOff = true;
					$action = 'update';

					$node = $query['response'][$route['table_name']]; //store original content table (to check for difference later on)

				} else {

					$action = 'add';
				}
			} else {
				$action = 'add';
			}

			//Nest content state on a line
			if($route['line_id']){ //specific line_id
				$circles = getCirclesBy(array('route' => array('line_id' => $row_line['id'])));
			} else {
				if($nodeOff == false){ //create a new content line
					if($action == 'add'){

					} elseif($action == 'update'){

					}
				}
			}

			//Entangle new state of content (fields)
			foreach($query['update'] AS $field => $value){
	       		if($value != null && current($query['response'])['table'][$field] != $value){ //valid, changed fields create a new state of data node

		          	$sql_state_row[] = 			"('{$user_id}', ".
		          								"'{$entity_id}', ".
			                        			"'{$route['language_id']}', ".
			                        			"{$time}, ".
			                        			"'{$route['entry_id']}', ".
			                        			"'$field', ".
			                        			"'$value')";

	       			$sql_insert[1][] = $field;
	       			$sql_insert[3][] = "'".$value."'";

	       			$sql_update[] = $field . " = '{$value}'";

				} else {
					$success[] = false;
				}
	        }

			if($action == 'add'){

				if(!$route['entry_id']){

					//Add content to table
			      	$sql.= 	"INSERT INTO {$route['table_name']} (".
				      			'created_by_user_id, created_by_entity_id, '.
				      			'time_created, time_updated, '.
		    					implode(', ', $sql_insert[1]).
			      			') VALUES ('.
			      				"{$route['time_now']}, {$route['time_now']}, ".
				      			"'{$user_id}', '{$entity_id}'".
		    					implode(', ', $sql_insert[3]).
			      			');';
					
					$success[] = mysqli_query($db, $sql);
					if(!$route['entry_id'] = $db->insert_id){
						$success[] = false;
					} else {
						$query['cache-relations']["{$route['table_name']}.id"] = $route['entry_id'];
					}
				}
				if(!$route['node_id'] && $nodeOff == false){

					//Create a new node
					$sql = "INSERT INTO node (time_created, time_updated, table_name, entry_id) VALUES ".
										"{$route['time_now']}, ".
										"{$route['time_now']}, ".
		                    			"'{$route['$table_name']}, ".
		                    			"'{$route['$entry_id']}";
					$success[] = mysqli_query($db, $sql);

					if(!$route['node_id'] = $db->insert_id){
						$success[] = false;
					} else {
						$node['cache-relations']['node.id'] = $route['node_id'];
					}
				}
			}

			//Add new translation

			//Is line_id set? 

	      	//Construct multilingual entry SQL

			//If all database queries were okay...
		    if(!in_array(false, $success)){
		    	mysqli_commit($db);

			    if($language_id != $GLOBALS['default_language_id']){ //!!! is not yet translated by hand
			    	$GLOBALS['translation_queue'][] = array('node_id' => $route['node_id'], 'language_id' => $route['language_id'], 'node' => $content);
			    }

	       		//Entries that have been modified are unsynchronized
			    unsyncCacheBlocks($cache);

		        //Return changed data objects back to user
	        	$route['history'] =			1; //How many states from current line to get?
	        	$route['dataset'] =			(!$route['dataset']) ? '*' : $route['dataset']; //Which content datasets should be returned?

		   		$node = getContent(array('route' => $route));

		   		compare($)

		   		//get changes

		    	transaction('transaction' => $transaction, $node['statechanged']);

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
	

    //Attach content line to node
    function createLine(){
    	

    }

	//Change main line for content node 
    function changeMainLine(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; 	//state_id - to identify content that's being edited (necessary)
    								//title - new line title (necessary)
    								//namespace (optional)

    	if($route['node_id'] || ($route['table_name'] && $route['entry_id']) && $route['line_id']){

			$sql = "UPDATE node {$table_name} SET ".
							" WHERE table_name = '{$table_name}' AND entry_id = '{$entry_id}'";

			$content['relations']["{$route['table_name']}.{$route['table_name']}_id"] = $route['entry_id'];
			$content['relations']['node.table_name'] = $route['table_name'];
			$content['relations']['node.entry_id'] = $route['entry_id'];
    	} else {
    		$success[] = false;
    	}
    }
    function forkContent(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route']; 	//state_id - to identify content that's being edited (necessary)
    								//title - new line title (necessary)
    								//namespace (optional)
		
		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if($route['state_id']){
    		
			$buffer['state']['getLinees'] = '*';
	        $buffer = getStates(array('route' => $route, 'block' => $buffer));

	        $route['node_id'] = current($buffer['state']['getLinees'])['node_id'];
	        $route['line_id'] = current($buffer['state']['getLinees'])['line_id'];

	        
    	}

        //Get current line_id and node_id...


        $block['relations']['line_id'] = $route['line_id'];

       	//Content to add
        $content = $input['state'];

		transaction(array('transaction' => $transaction));
    }

  //Database text is translated to English for multilingual search capabilities
    function translateToDefault(){

      $db = $GLOBALS['db'];
      $input = func_get_args()[0];

      if($input['language_id'] != $GLOBALS['default_language_id']){

        foreach($array['content'] AS $key => $value){
          if($value !== false && is_numeric($value) === false){
            $sql_update[] = "$key = '".translate($value, $GLOBALS['languages'][$input['language_id']]['code'], $GLOBALS['languages'][$GLOBALS['default_language_id']]['code'])."'";
          }
        }
        $sql_update = "UPDATE $table SET ".implode(', ', $sql_update)." WHERE id = '{$array['table_id']}'";
      }
    }

?>