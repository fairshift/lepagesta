<?php
  function getUser(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args();

    //Function router
    $route = ksort($input['route']);

    $dataset = (!$input['dataset']) ? '*' : ksort($input['dataset']);

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));
    $query['transaction'] = ($input['parent-transaction']) ? $input['parent-transaction'] : $transaction;

    if($route['auth']){
      $where = "auth = '{$route['auth']}'";
    } elseif($route['user_id']){
      $where = "id = '{$route['user_id']}'";
    } elseif($route['email']){
      $where = "email = '{$route['email']}'";
    } elseif($route['username']){
      $where = "username = '{$route['username']}'";
    } elseif($route['email_confirmation_code']){
      $where = "email_confirmation_code = '{$route['email_confirmation_code']}'";
    } elseif($route['facebook_user_id']){
      $where = "facebook_user_id = '{$route['facebook_user_id']}'";
    } elseif($route['twitter_user_id']){
      $where = "twitter_user_id = '{$route['twitter_user_id']}'";
    }

    if( ($user_id || $entity_id) && $where ){

      if(!$query = existingCache($transaction)){ //isset($input['parent-transaction'])... does this make sense in terms of optimization?

        if($where){
          $sql = "SELECT * FROM user WHERE $where";
       
          $result = mysqli_query($db, $sql);
          if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

            $query['response'] = safeProfileData($row);
            $query['response']['circles'] = getCirclesBy(array('route' => array('user_id' => $row['id'])));

            $query['cache-relations']['user.id'] = $row['id'];
          }

          //Update cache with state(s) of content - if calling function didn't set parent-cache and everything else went okay
          if(is_array($query['cache-relations']) && !in_array(array('status_code' => '400'), $query['response'])){
            updateCache($query);
          }
        } else {

          $query['response']['status_code'] = '400';
        }
      } else {
        $GLOBALS['nodes'] = array_merge($GLOBALS['nodes'], $query['nodes']);
      }
    }

    transaction(array('transaction' => $transaction));

    return $query;
  }
?>