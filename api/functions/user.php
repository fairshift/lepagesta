<?php
  function getUser(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_manage)

    $input = func_get_args();

    //Function router $db, $id, $selector, $preset
    $route = ksort($input['route']);  //selector - necessary
                                      //id - necessary
    
    $route['dataset'] = (!$route['dataset']) ? '*' : ksort($route['dataset']); //necessary
    $selector = $route['selector'];
    $id = $route['id'];

    transaction(__FUNCTION__, $route);

    if(in_array("auth", $route['dataset'])){
      $select = "id, id AS user_id, username, email, time_registered, last_visit, email_confirmation_time, facebook_user_id, twitter_user_id, site_language_id, auth, auth_site_id";
    } elseif(in_array("me", $route['dataset'])){
      $select = "id, id AS user_id, auth, password, username, email, email_confirmation_code, email_confirmation_time, time_registered, last_visit, facebook_user_id, twitter_user_id, site_language_id, profile_picture";
    } elseif(in_array("avatar", $route['dataset'])){
      $select = "id, id AS user_id, auth, password, username, email_confirmation_time, time_registered, last_visit, facebook_user_id, twitter_user_id, site_language_id, profile_picture";
    }

    if($selector == 'auth'){
      $where = "auth = '{$id}'";
    } elseif($selector == 'user_id'){
      $where = "id = '{$id}'";
    } elseif($selector == 'email'){
      $where = "email = '{$id}'";
    } elseif($selector == 'username'){
      $where = "username = '{$id}'";
    } elseif($selector == 'email_confirmation_code'){
      $where = "email_confirmation_code = '{$id}'";
    } elseif($selector == 'facebook_user_id'){
      $where = "facebook_user_id = '{$id}'";
   	} elseif($selector == 'twitter_user_id'){
      $where = "twitter_user_id = '{$id}'";
   	}

    $sql = "SELECT $select FROM user WHERE $where";
 
    $result = mysqli_query($db, $sql);
    if($buffer['state'] = mysqli_fetch_array($result, MYSQLI_ASSOC)){

      $buffer['relations']['user.user_id'] = $buffer['state']['user_id'];
      $buffer['state'] = 'getCircles';
      $buffer = getCirclesBy(array('route' => array('user_id' => $route['user_id']), 'block' = $buffer));

      $block = $buffer; //Merge current block with one delivered by calling function

      //Update cache with current block if calling function didn't pass state
      /*if(!$block['state'] && !in_array(array('status_code' => '400'), $block['state'])){
        updateCacheBlock($block);
      }*/

      $response = $block;
    } else {
      $response = false;
    }

    transaction(__FUNCTION__);

    return $response;
  }
?>