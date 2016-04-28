<?php

	$GLOBALS['privileges'] = array('read', 'reflect', 'value', 'join', 'invite', 'encircle', 'branch', 'manage');

	//Privileges hierarchy: Author > Commoner (NULL defaults to whichever the greater influence from Content's & Circle's privileges is)
	function availablePrivileges(){

		$db, $user_id, $content_privileges, $content_circles, $author = null

 		$db = $GLOBALS['db'];

        $input = renderInput(func_get_args());

 		$user_id = $GLOBALS['user_id'];

        $input['branch_id'] = 				(!$input['branch_id']) ? null : $input['branch_id']; //optional call of content branch

        $input['history'] = 				(!$input['history']) ? '1' : $input['history'];

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['time'] = 					(!$input['block']) ? microtime() : $input['block']['time'];
    	$block['transaction'] = 			(!$input['block']) ? formatTransaction(__FUNCTION__, $input) : $input['block']['transaction'];
    	$block['state'] = 					(!$input['block']['state']) ? null : $input['block']['state'];

		//Clash: circle privileges & content privileges as set by author

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

	/*function getBranchPrivileges(){ //accepting spiral idea flow

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = renderInput(func_get_args());

        $input['branch_id'] = 				(!$input['branch_id']) ? null : $input['branch_id']; //optional

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['time'] = 					(!$input['block']) ? microtime() : $input['block']['time'];
    	$block['transaction'] = 			(!$input['block']) ? formatTransaction(__FUNCTION__, $input) : $input['block']['transaction'];
    	$block['state'] = 					(!$input['block']['state']) ? null : $input['block']['state'];

       	foreach($buffer['state']['getContentBranches'] AS $branch_id => $branch){

       	}
	}*/

?>