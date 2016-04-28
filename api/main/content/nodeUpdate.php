<?php
//https://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Plant_nodes_c.jpg/200px-Plant_nodes_c.jpg

  //Create / update content node - branches, (localized) states, values, keywords, words (histogram), 
    function updateNode(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        //Function router
    	$route = $input['route']; 	//node_id || (table_name & entry_id)
    								//branch_id, state_id
    	$route['language_id'] = (!$route['language_id']) ? $GLOBALS['language_id'] : $route['language_id'];

		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

       	//Data state to entangle within a content node
        $node['update'] = $input['update'];

	    if($route['node_id'] || ($route['table_name'] && $route['entry_id'])){

			mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);

			//Get existing content tables
			if( ($route['node_id'] || ($route['table_name'] && $route['entry_id'])) ){

				$node['route'] = $route;
				$node['route']['dataset'] = '*';
				$node['route']['history'] = '1';
				$node = getNode($node); //save this variable to get difference (blockchain needs change of state)

				$route['node_id'] = $node['state']['node_id'];
				$route['table_name'] = $node['state']['table_name'];
				$route['entry_id'] = $node['state']['entry_id'];

				//Get branch of current content state
				if($route['state_id'] && !$route['branch_id']){
					$route['branch_id'] = $node['state']['branch_id']);
				}

				//Get main branch if no specific branch_id is chosen
				if(!$route['branch_id'] && !$route['state_id']){
					$route['branch_id'] = $node['state']['main_branch_id'];
				}
			}

			//Do this content and it's node exist? Create new 
			$action = (!$route['entry_id'] && !$route['node_id']) ? 'add' : 'edit';

			if($action == 'add'){

				if(!$route['entry_id']){

					//Add content to table
			      	$sql.= 	"INSERT INTO {$route['table_name']} (".
				      			'created_by_user_id, created_by_entity_id, '.
		    					implode(', ', $sql_insert[1]).
			      			') VALUES ('.
				      			"'{$user_id}', '{$entity_id}'".
		    					implode(', ', $sql_insert[3]).
			      			');';
					
					$success[] = mysqli_query($db, $sql);
					if(!$route['entry_id'] = $db->insert_id){
						$success[] = false;
					} else {
						$node['cache-relations']["{$route['table_name']}.id"] = $route['entry_id'];
					}
				}
				if(!$route['node_id']){

					//Create a new node
					$sql = "INSERT INTO node (table_name, entry_id) VALUES ".
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

			//Nest content state on a branch
			if($route['branch_id']){ //existing branch_id

			} else { //create a new branch

			}

			$time = time();
			//Entangle new state of content with node (fields)
			foreach($content AS $field => $value){
	       		if($value != null && $node['state'][$key][$route['language_id']] != $value){ //valid, changed fields create a new state of data node

		          	$sql_state_row[] = 			"('{$user_id}', ".
		          								"'{$entity_id}', ".
			                        			"'{$route['language_id']}', ".
			                        			"{$time}, ".
			                        			"'{$route['table_name']}', ".
			                        			"'{$route['entry_id']}', ".
			                        			"'$field', ".
			                        			"'$value')";

	       			$sql_insert[1][] = $field;
	       			$sql_insert[3][] = "'".$value."'";

	       			$sql_update[] = $field . " = '{$value}'";

					$node['statechanged'][$field] = $value; //for blockchain synthesis

				} else {
					$success[] = false;
				}
	        }

			//Add new translation

			//Is branch_id set? 

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
	        	$route['history'] =			1; //How many states from current branch to get?
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
	

    //Attach content branch to node
    function createBranch(){
    	

    }

	//Change main branch for content node 
    function changeMainBranch(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

        $input = func_get_args();

        //Function router
    	$route = $input['route']; 	//state_id - to identify content that's being edited (necessary)
    								//title - new branch title (necessary)
    								//namespace (optional)

    	if($route['node_id'] || ($route['table_name'] && $route['entry_id']) && $route['branch_id']){

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

        $input = func_get_args();

        //Function router
    	$route = $input['route']; 	//state_id - to identify content that's being edited (necessary)
    								//title - new branch title (necessary)
    								//namespace (optional)
		
		$transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    	if($route['state_id']){
    		
			$buffer['state']['getBranches'] = '*';
	        $buffer = getStates(array('route' => $route, 'block' => $buffer));

	        $route['node_id'] = current($buffer['state']['getBranches'])['node_id'];
	        $route['branch_id'] = current($buffer['state']['getBranches'])['branch_id'];

	        
    	}

        //Get current branch_id and node_id...


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

?>