<?php
//Circle is common grounds, encircling purposes, storylines and rules of engagement
  /*
   *  Technically speaking, it's a pool of contextually related data shared among services - sites which use the API
   *  This allows multiple applications on top of existing data (as is going to be the case with services living on the blockchain)
   */

  /*
  	Use case:
		User requests a content
		a) s/he had a direct link
		b) it appeared in a stream
		c) s/he clicked on it in a circle
  */

	//Idea: Reflections from current circle could be complementing privileges in circles - showcasing implicit rules of engagement with appreciated gestures

 	function getCirclesBy(){

 		$db = $GLOBALS['db'];
		$user_id = $GLOBALS['user_id'];

        $input = renderInput(func_get_args());

        $input['by'] = 						(!isset($input['by'])) ? null : $input['by'];
        $input['get_privileges_user_id'] =  (!isset($input['get_privileges_user_id'])) ? null : $input['get_privileges_user_id'];

        $block = 							(!isset($input['block'])) ? null : $input['block'];
    	$block['time'] = 					(!$input['block']) ? microtime() : $input['block']['time'];
    	$block['transaction'] = 			(!$input['block']) ? formatTransaction(__FUNCTION__, $input) : $input['block']['transaction'];

	    if($user_id && $input['by']){

	    	if($input['by']['content_id']){ //Caching by content

			    $block['dataview']['circle_content.content_id'] = $by['content_id'];

	    	} elseif($input['by']['site_id']){ //... by site_id

	    		$block['dataview']['site_circle.site_id'] = $by['site_id'];

	    	} elseif($input['by']['user_id']){

	    		$block['dataview']['circle_commoner.user_id'] = $by['user_id'];
	    	}
	    	//... by circle_id's cache isn't set (as it's assumed to be too unfrequent)

		    if(!$response = existingCacheBlock($db, $cache)){

	    		if($by['content_id']){ //Get circles by content

		            $sql = 	  "SELECT content_circle.*, content_circle.id AS content_circle_id ".
		        			  "FROM content_circle, circle WHERE ".

		                      "content_circle.content_id = '{$by['content_id']}' AND ".
		                      "content_circle.circle_id = circle.id AND ".
		                      "circle.removed = 0 AND content_circle.removed = 0";

		        } elseif($by['site_id']){ //... by site_id

		            $sql = 	  "SELECT site_circle.*, site_circle.id AS site_circle_id ".
		        			  "FROM site_circle, circle WHERE ".

		                      "site_circle.site_id = '{$by['site_id']}' AND ".
		                      "site_circle.circle_id = circle.id AND ".		                
		                      "circle.removed = 0 AND site_circle.removed = 0";

		        } elseif($by['user_id']){ //... by user_id

		        	$sql = "SELECT circle_commoner.*, circle_commoner.id AS circle_commoner_id FROM circle, circle_commoner WHERE ".
			                      "circle_commoner.user_id = '{$by['user_id']}' AND ".
			                      "circle_commoner.removed = 0 AND circle.removed = 0";

		        }

		      	$result = mysqli_query($db, $sql);
		      	while($response = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		      		//get circle info, commoners, privileges & translations
					if($circle = getCircle($db, $row['circle_id'], $get_privileges_user_id)){
						if(!$get_privileges_user_id){
				    		$cache['dataview'] = array_merge($cache['dataview'], $circle['cache']['dataview']);
						}
				    	unset($circle['cache']);
				    	//$response[$circle[$circle_id]] = 
				    	$response[$circle['circle_id']]['circle'] = $circle;
				    	$response[$circle['circle_id']]['status_code'] = '200';
				    } else {
				    	$response[$circle['circle_id']]['status_code'] = '400';
				    }
		        }

		        if($return_cache == false){
				    $cache['object'] = $response;
				    updateCache($cache);
			    	unset($cache['object']);
		        }
		        unset($cache['route']);
			    $response['cache'] = $cache;
			}

	    	return $response;
	    }
  	}

	function getCircle($circle_id, $get_privileges_user_id = false, $return_cache = false){

 		$db = $GLOBALS['db'];
		$user_id = $GLOBALS['user_id'];

		if($user_id && $circle_id){

    		$cache['route'] = cacheSetRoute(__FUNCTION__, func_get_args());
	    	$cache['dataview']['circle.circle_id'] = $circle_id;

		    if(!$response = existingCache($db, $cache)){

		    	//Info
		    	$response = getContent($db, 'circle', $circle_id, false, true);
		    	$cache['dataview'] = array_merge($cache['dataview'], $response['cache']['dataview']);
		    	unset($response['cache']);

				//Type
		    	$response['type'] = getContent($db, 'circle_type', $row['type_id'], true);
		    	$cache['dataview'] = array_merge($cache['dataview'], $response['type']['cache']['dataview']);
				unset($response['commoners']['cache']);

		    	if($response['circle_id']){
			    	//Commoners
			    	if($privileges_user_id){
				    	$response['commoners'] = getCommoners($db, $circle_id, $get_privileges_user_id, true);
			    	} else {
				    	$response['commoners'] = getCommoners($db, $circle_id, false, true);

						$cache['dataview'] = array_merge($cache['dataview'], $response['commoners']['cache']['dataview']);
						unset($response['commoners']['cache']);

				        if($return_cache == false){
						    $cache['object'] = $response;
						    updateCache($cache);
					    	unset($cache['object']);
				        }
					    $response['cache'] = $cache;
			    	}
		    	}
			}
			return $response;
		}
	}

	function getCommoners($db, $circle_id, $check_privileges_user_id = false, $return_cache = false){

 		$db = $GLOBALS['db'];

		if($user_id && $circle_id){

	    	$cache['route'] = cacheSetRoute(__FUNCTION__, func_get_args());
	    	$cache['dataview']['circle_commoner.circle_id'] = $circle_id;

		    if(!$response = existingCache($db, $cache)){

				$sql = "SELECT *, circle_commoner.id AS circle_commoner_id FROM circle_commoner ".
					   "WHERE circle_id = '{$circle_id}' AND removed = 0";

				if($privileges_user_id == false){
					$sql.= "ORDER BY time_confirmed DESC";
				} else {
					$sql.= "AND user_id = '{$check_privileges_user_id}'";
				}

			    $result = mysqli_query($db, $sql);
		        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		        	$response[$row['user_id']] = $row;
		        	if(!$privileges_user_id){
			        	$response[$row['user_id']]['user'] = getUser($db, $row['user_id'], 'user_id', array('avatar'));
				    	$cache['dataview']['circle_commoner.user_id'] = $user_id;
		        	}
				}

		        if(!$return_cache && !$privileges_user_id){
				    $cache['object'] = $response;
				    updateCache($cache);
			    	unset($cache['object']);
		        }
			    $response['cache'] = $cache;
			}
			return $response;
		} 
	}

  	function addContentToCircles($table_name, $entry_id, $circles){
  		
 		$db = $GLOBALS['db'];
  		/*$user_id = $GLOBALS['user_id'];

  		if($user_id){

  			foreach($circles AS $circle_id){
				if($circle = getCircle($db, $user_id, 'circle', $row['circle_id'], true)){
					$response[$circle_id] = $circle;
				} else {
					$response[$circle_id]['status_code'] = '400';
				}
  			}
  		}*/
  	}
?>