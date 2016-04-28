<?php
  function getUser(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args();

    //Function router $db, $id, $selector, $preset
    $route = ksort($input['route']);  //selector - necessary
                                      //id - necessary
    
    $selector = $route['selector'];
    $id = $route['id'];

    $dataset = (!$input['dataset']) ? '*' : ksort($input['dataset']); //necessary

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if(in_array("auth", $dataset)){
      $select = "id, id AS user_id, username, email, time_registered, time_visited, time_email_confirmed, facebook_user_id, twitter_user_id, interface_language_id, auth, auth_time";
    } elseif(in_array("me", $dataset)){
      $select = "id, id AS user_id, auth, password, username, email, email_confirmation_code, time_email_confirmed, time_registered, time_visited, facebook_user_id, twitter_user_id, interface_language_id";
    } elseif(in_array("avatar", $dataset)){
      $select = "id, id AS user_id, auth, password, username, time_email_confirmed, time_registered, time_visited, facebook_user_id, twitter_user_id, interface_language_id";
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
    if($node['state'] = mysqli_fetch_array($result, MYSQLI_ASSOC)){

      $node['relations']['user.user_id'] = $node['state']['user_id'];
      $node['state']['circles'] = getCirclesBy(array('route' => array('user_id' => $route['user_id']), 'block' = $buffer));

      $response = $node;
    } else {
      $response = false;
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }
?>