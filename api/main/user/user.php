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

    $user_route['table'] = 'user';

    if($route['user_id']){
      $user_route['id'] = $route['user_id'];

    } elseif($route['auth']){
      $user_route['where']['auth'] = $route['auth'];

    } elseif($route['email']){
      $user_route['where']['email'] = $route['email'];

    } elseif($route['username']){
      $user_route['where']['username'] = $route['username'];

    } elseif($route['email_confirmation_code']){
      $user_route['where']['email_confirmation_code'] = $route['email_confirmation_code'];

    } elseif($route['facebook_user_id']){
      $user_route['where']['facebook_user_id'] = $route['facebook_user_id'];

    } elseif($route['twitter_user_id']){
      $user_route['where']['twitter_user_id'] = $route['twitter_user_id'];
    }

    if( ($user_id || $entity_id) && is_array($user_route) ){

      $query = getNode(array('route' => $user_route));
    }

    transaction(array('transaction' => $transaction));

    return $query;
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