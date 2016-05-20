<?php

	$GLOBALS['actions'] = array('read', 'reflect', 'value', 'join', 'invite', 'encircle', 'line', 'edit', 'represent', 'manage', 'restore');

    function isAvailable(){ //returns if data state (table) is available, taking into account removals and read privilege set by author(s), circle(s) and possibly, encryption

    	//If this was a distributed blockchain database, data could be distributed among users running their nodes (in turn defining availability)
    	//Idea: if data was encrypted, engagement within a circle could unlock public keys for content decryption (perhaps through matches of enacted values?)

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

    	//Table, user_id / entity_id + contributors (cross-check)
    	if($row = $input['table']){
	      	if($row['time_removed'] == 0){ //entry isn't removed
	      		$available = true;
	      	} elseif($row['time_removed'] > 0){ //entry is removed
	      		if(strpos($user_id, $input['row']['user_id']) || $input['row']['entity_id'] == $entity_id){ //content is removed and available to initiators, content circles
		      		$available = true;
		      	} else {

		      		$available = false;
		      	}
	      	}
    	}

    	//Node - lines in node might be accessible even if node's main_line_id and table are removed
    	if(is_array($input['node'])){ //content is available 
    		availablePrivileges(array('circles' => $circles, 'privileges' => $input['privileges']));
    	}

    	return $available;
	}

	//Privileges hierarchy: Content author > Commoner > Circle (NULL defaults to whichever the greater influence from Content's & Circle's privileges is)
	function availablePrivileges(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args()[0];

        //Function router
    	$route = $input['route'];

		foreach($GLOBALS['actions'] AS $action){

			if($author && $action == 'read', 'join', ''){
				$response['privilege_'.$action] = 
			}
			foreach($content_circles AS $content_circle){

				if(isset($content_circle['privilege_'.$privilege]) && 
						 $content_circle['privilege_'.$privilege] != NULL){
				}

				//Privileges flow: Commoner as default when not NULL - otherwise to which brings more influence from Content's public & Circle's public privileges)

				/*if(is_array($content_circle['commoners'][$user_id]]){

					if($)
					$privileges['read'] = ($commoner_circle['circle_id']['privilege_read'] != NULL) ?
											$commoner_circle['circle_id']['privilege_read'] : 
											$commoner_
											(if($commoner['circle_id'] )
					$privileges['create'] = ($commoner_circle['circle_id'][] != NULL) ? 

					if($privileges['']['']){

					}
					$response[
					decypher: circles -> cira
				}*/
			}
		}
	}
?>