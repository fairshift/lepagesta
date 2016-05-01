<?php

	$GLOBALS['privileges'] = array('read', 'reflect', 'value', 'join', 'invite', 'encircle', 'line', 'edit', 'represent', 'manage');

    function isAvailable(){ //returns if data state (table) is available, taking into account removals and read privilege set by author(s), circle(s) and possibly, encryption

    	//If this was a distributed blockchain database, data could be distributed among users running their nodes (in turn defining availability)
    	//Idea: if data was encrypted, engagement within a circle could unlock public keys for content decryption (perhaps through enacted values?)

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        $access = false;

    	//Data node !!! this part  by (d)encryptions of data by users / entities (private-public key pairs, partial public keys)
    	if($row = $input['row']){
	      	if($row['time_removed'] == 0){ //privileges for removed data are different (sufficient?)
	      		$access = true;
	      	} else {
	      		if($input['row']['user_id'] == $user_id || $input['row']['entity_id'] == $entity_id){
		      		$access = true;
		      	}
	      	}
    	}
    	if(is_array($input['circles']) && $input['privileges']){
    		availablePrivileges(array('circles' => $circles, 'privileges' => $input['privileges']));
    	}

    	return $access;
	}

	//Privileges hierarchy: Content author > Commoner > Circle (NULL defaults to whichever the greater influence from Content's & Circle's privileges is)
	function availablePrivileges(){

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user']['id'];
 		$entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null;

        $input = func_get_args();

        //Function router
    	$route = ksort($input['route']);

    	if($input['circles'])
		foreach($GLOBALS['privileges'] AS $privilege){

			if($author && $privilege == 'read', 'join', ''){
				$response['privilege_'.$privilege] = 
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