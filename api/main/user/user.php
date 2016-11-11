<?php
  function getUser(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args()[0];

    //Function router
    $route = $input['route'];
    $dataset = (!$input['dataset']) ? '*' : $input['dataset'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $dataset));

    $route['table'] = 'user';

    if($route['user_id']){
      $route['id'] = $route['user_id'];

    } elseif($route['auth']){
      $route['where']['auth'] = $route['auth'];

    } elseif($route['email']){
      $route['where']['email'] = $route['email'];

    } elseif($route['username']){
      $route['where']['username'] = $route['username'];

    } elseif($route['email_confirmation_code']){
      $route['where']['email_confirmation_code'] = $route['email_confirmation_code'];

    } elseif($route['facebook_user_id']){
      $route['where']['facebook_user_id'] = $route['facebook_user_id'];

    } elseif($route['twitter_user_id']){
      $route['where']['twitter_user_id'] = $route['twitter_user_id'];
    }

    $query = getNode(array('route' => $route));

    transaction(array('transaction' => $transaction));

    return $query;
  }

  function userNode(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args()[0];
    $route = $input['route'];
    $dataset = $input['dataset'];

    $user = $input['node'];

    if($user['time_email_confirmed'] > 0 
      || $user['facebook_user_id'] > 0 
      || $user['twitter_user_id'] > 0
      /*|| $row['google_user_id'] > 0*/){
      $user['accountConfirmed'] = true;

      $user['facebook_user_id'] = ($user['facebook_user_id']) ? true : 0;
      $user['twitter_user_id'] = ($user['twitter_user_id']) ? true : 0;
    } else {
      $user['accountConfirmed'] = false;
    }

    if($route['checkPassword']){
      if($route['checkPassword'] == $user['password']){
        $user['passwordMatch'] = true;
      } else {
        $user['passwordMatch'] = false;
      }
    }
    unset($user['password']);

    if(!$route['auth'] || $user_id != $user['id']){
      unset($user['auth']);
    }

    return $user;
  }

  //This part is called within getNode/getLine, to plug in user-specific datasets
  function userLine(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args()[0];
    $route = $input['route'];
    $dataset = $input['dataset'];

    /*if($dataset == '*' || $dataset == 'avatar'){
    } elseif($dataset == 'public'){
    } elseif($dataset == 'me'){
    }*/

    if($route['table'] == 'user' && in_array('user_circles', $dataset)){

        $dataset['circles'] = getCirclesBy( array('route' => array( 'user_id' => $route['id'] )) );
    }

    return $dataset;
  }

?>