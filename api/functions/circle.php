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
		$user_id = $GLOBALS['user_id'];

        $input = renderInput(func_get_args());

        $input['route'] = 					(!isset($input['route'])) ? null : $input['route'];

        //Either one of the following is required
        $route['circle_id'] =				(!$route['circle_id']) ? null : $route['circle_id'];

        //Branch is requested for 
        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id']; //optional call of content branch_id
        $route['branch'] = 					(!$route['branch']) ? null : $route['branch']; //optional call of content branch

        $route['user_id'] =  				(!isset($route['user_id'])) ? null : $route['user_id'];

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 			(!$block['transaction']) ? formatTransaction(__FUNCTION__, $route) : $block['transaction'];
    	$block['transaction_time'] = 		(!$block['transaction_time']) ? microtime() : $block['transaction_time'];
  		$block['dataview'];					(!$block['dataview']) ? null : $block['dataview'];
    	$block['state'] = 					(!$block['state']) ? null : $block['state'];

	    if($user_id){

	    	if($route['branch_id']){ //Caching by content

			    $block['dataview']['circle_state.content_id'] = $route['content_id'];

	    	} elseif($route['site_id']){ //... by site_id

	    		$block['dataview']['site_circle.site_id'] = $route['site_id'];

	    	} elseif($route['user_id']){

	    		$block['dataview']['circle_commoner.user_id'] = $route['user_id'];
	    	}
	    	//... by circle_id's (case isn't set yet)

		    if(!$buffer = existingCacheBlock($input){

	    		if($route['branch_id']){ //Get circles by content branch

		            $sql = 	  	"SELECT content_circle.*, content_circle.id AS content_circle_id ".
		        			  	"FROM content_circle, circle WHERE ".

		                      	"content_circle.content_id = '{$route['branch_id']}' AND ".
		                      	"content_circle.circle_id = circle.id AND ".
		                      	"circle.removed = 0 AND content_circle.removed = 0";

		        } elseif($route['site_id']){ //... by site_id

		            $sql = 	  	"SELECT site_circle.*, site_circle.id AS site_circle_id ".
		        			  	"FROM site_circle, circle WHERE ".

		                      	"site_circle.site_id = '{$route['site_id']}' AND ".
		                      	"site_circle.circle_id = circle.id AND ".		                
		                      	"circle.removed = 0 AND site_circle.removed = 0";

		        } elseif($route['user_id']){ //... by user_id

		        	$sql = 		"SELECT circle_commoner.*, circle_commoner.id AS circle_commoner_id FROM circle, circle_commoner WHERE ".
			                    "circle_commoner.user_id = '{$by['user_id']}' AND ".
			                    "circle_commoner.removed = 0 AND circle.removed = 0";
		        }

		      	$result = mysqli_query($db, $sql);
		      	while($buffer['state'] = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		      		//get circle info, commoners, privileges & translations
					$buffer = getCircle(array(	'circle_id' => $row['circle_id'], 
												'branch_id' => $route['branch_id'],  
												'user_id' => $route['user_id'], 
												'block' => $buffer))){


				 
		        }

				//Dispatch to block
				if($block['state']){
					$block = mergeBlocks('getContentTable', $block, $buffer);
				} else {
					$block = $buffer;

					//Everything okay?
		  			if(!in_array(array('status_code' => '400'), $block['state'])){
		    			updateCacheBlock($block);
		    		}
		    	}
			}

	    	return $response;
	    }
  	}

	function getCircle(){ //Shares block stream with getBranches and availablePrivileges

 		$db = $GLOBALS['db'];
 		$user_id = $GLOBALS['user_id'];

        $input = func_get_args();

        //$circle_id, $get_privileges_user_id = false, $return_cache = false

        $route = $input['route'];
        //Either one of the following is required
        $route['circle_id'] =				(!$route['circle_id']) ? null : $route['circle_id'];

      	//Get circle by branch_id
        $route['branch_id'] = 				(!$route['branch_id']) ? null : $route['branch_id']; //optional call of content branch_id
        $route['branch'] = 					(!$route['branch']) ? null : $route['branch']; //optional call of content branch

        $route['history'] = 				(!$route['history']) ? '0,12' : $route['history'];
        $route['preset'] =					(!$route['preset']) ? '*' : $route['preset']; //which subsections of content to return?

        $block = 							(!$input['block']) ? null : $input['block'];
    	$block['transaction'] = 			(!$block['transaction']) ? formatTransaction(__FUNCTION__, $route) : $block['transaction'];
    	$block['transaction_time'] = 		(!$block['transaction_time']) ? microtime() : $block['transaction_time'];
  		//$block['dataview'];
    	$block['state'] = 					(!$block['state']) ? null : $block['state'];

 		$db = $GLOBALS['db'];
		$user_id = $GLOBALS['user_id'];

		if($user_id && $route['circle_id']){

	    	$block['dataview']['circle.circle_id'] = $route['circle_id'];

		    if(!$buffer = existingCache($db, $block)){ //caching enabled when 

		    	//Circle's details
		    	$buffer = getContent(array('circle' => $route['circle_id'], 'branch_id' => $route['branch_id'], 'block' = $buffer));



				//Type
		    	$response['type'] = getContent(array('table_name' => 'circle_type', 'entry_id' => $buffer['state']['getCircle']['type']['id'], 'block' = $buffer));
		    	$cache['dataview'] = array_merge($cache['dataview'], $response['type']['cache']['dataview']);
				unset($response['commoners']['cache']);

		    	if($response['circle_id']){
			    	//Commoners
			    	if($privileges_user_id){
				    	$response['commoners'] = getCommoners($db, $circle_id, $get_privileges_user_id, true);
			    	} else {
				    	$block = getCommoners($db, $circle_id, false, true);

						$block['dataview'] = array_merge($cache['dataview'], $response['commoners']['cache']['dataview']);
						unset($response['commoners']['cache']);
			    	}
		    	}
			}

	  		//Dispatch to block
  			if($block['state']){
	  			$block = mergeBlocks('getCircle', $block, $buffer);
  			} else {
  				$block = $buffer;
  			}

			//Everything okay?
			if(!in_array(array('status_code' => '400'), $block['state'])){
	    		updateCacheBlock($block);
	    	}

			return $block;
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

  	function encircleContent(){ //Adding content to circle
  		
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
  	}
?>