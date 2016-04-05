<?php

	$GLOBALS['privileges'] = array('read', 'join', 'invite', 'encircle', 'reflect', 'value', 'edit', 'manage');

	//Privileges hierarchy: Author > Commoner (NULL defaults to whichever the greater influence from Content's & Circle's privileges is)
	function availablePrivileges($db, $user_id, $content_privileges, $content_circles, $author = null){
		//[firestarter - an influence sent through vibes of a song at one moment]

		foreach($GLOBALS['privileges'] AS $privilege){

			if($author && $privilege == 'read', 'join', ){
				$response['privilege_'.$privilege] = 
			}
			foreach($content_circles AS $content_circle){

				if(isset($content_circle['privilege_'.$privilege]) && 
						 $content_circle['privilege_'.$privilege] != NULL){
				}

				//Privileges hierarchy: Commoner as default when not NULL - otherwise to which brings more influence from Content's public & Circle's public privileges)

				if(is_array($content_circle['commoners'][$user_id]]){

					if($)
					$privileges['read'] = ($commoner_circle['circle_id']['privilege_read'] != NULL) ?
											$commoner_circle['circle_id']['privilege_read'] : 
											$commoner_
											(if($commoner['circle_id'] )
					$privileges['create'] = ($commoner_circle['circle_id'][] != NULL) ? 

					//relevancy supplied by linking:
						//function: 
						//relevant example: 
							//

					if($privileges['']['']){

					}
					$response[
					decypher: circles -> cira
				}
			}
		}

	}

?>