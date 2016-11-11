<?php

//Authentication or registration of anonymous visitors with every visit
  function authenticate(){

    $db = $GLOBALS['db'];
    $route['auth'] = input($GLOBALS['call_inprocess'], 'auth', 'string', 32, 32);
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $newUser = false; //in case authentication code doesn't match, this triggers a new anonymous account (auth cookie validation)

    if($route['auth']){

      $user = getUser(array('route' => array('auth' => $route['auth'])));

      if(isset($user['id'])){

        mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
        mysqli_query($db, "UPDATE user SET time_visited = '".time()."' WHERE auth = '{$route['auth']}'");
        mysqli_commit($db);

        $user['auth'] = $route['auth'];

      } else {
        $newUser = true;
      }
    }

    if(!$route['auth'] || $newUser == true){

      $auth = newKey();

      mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
      mysqli_query($db, 'INSERT INTO user (auth, auth_time, time_visited) VALUES ('.
                  "'{$route['auth']}', ".
                  "'".time()."', ".
                  "'".time()."'" );
      mysqli_commit($db);

      $user['auth'] = $auth;
    }

    transaction(array('transaction' => $transaction, 'statechanged' => $statechanged));

    return $user;
  }

//Sign in / Register / Confirm router
  function passThrough(){

    $input = func_get_args()[0];
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $continue = emailStatus(array('route' => $route));

    if($continue == 'register'){
      $register = registerPerson(array('route' => $route));
    }
    if($continue == 'signin'){
      $user = signinPerson(array('route' => $route));
    }

    if($user['id'] > 0){
      $response = $user;
      $response['status'] = 'welcome';
    } else {
      $response['status'] = emailStatus(array('route' => $route));
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function emailStatus(){

    $user_id = $GLOBALS['user']['id'];

    $input = func_get_args()[0];
    $route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if($user_id > 0 && filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL) == urldecode($_REQUEST['email'])){

      $user = getUser(array('route' => array('email' => $route['email'])))['response'];

      if($user['id'] && strlen($user['password']) == 32 && $user['accountConfirmed'] == true){
         $response = "signin";
      } elseif($user['id'] && strlen($user['password']) == 32 && $user['accountConfirmed'] == false){
        $response = "confirm";
      } else {
        $response = "register";
      }
    } else {
      $response = "invalidmail";
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function displayNameExists(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args()[0];
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if($user_id && $route['display_name']){

      $user = getUser(array('route' => array('where' => array('display_name' => $route['display_name']))))['response'];

      if($user['response']['id'] > 0){
        $response['status'] = "exists";
      } else {
        $response['status'] = "available";
      }
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function registerPerson(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args()[0];
    $route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if(!getUser(array('route' => array('email' => $route['email'])))
      && $route['password'] == $route['password_confirm'] 
      && !getUser(array('route' => array('display_name' => $route['display_name'])))
    ){

      $email_confirmation_code = newKey();
    
      $sql = "INSERT INTO user (display_name, password, email, time_created, email_confirmation_code, time_email_confirmed, time_visited) VALUES (".
                  "'".$route['display_name']."', ".
                  "'".md5($route['password'])."', ".
                  "'{$route['email']}', ".
                  "'".time()."', ".
                  "'{$email_confirmation_code}', ".
                  "'0',".
                  "'".time()."');";
      mysqli_query($db, $sql);

      mailer($route['email'], array('email_confirmation_code' => $email_confirmation_code, 'display_name' => $route['display_name']), 'confirmation');

      $user = getUser(array('route' => array('auth' => $auth)));
    }

    transaction(array('transaction' => $transaction));

    return $user;
  }

  function confirmEmail(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args()[0];
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if( $user_id && $route['code'] && 
        getUser(array('route' => array('email_confirmation_code' => $route['code']))) ){

      $time = time();

      $sql = "UPDATE user SET time_email_confirmed = '{$time}' WHERE id = '{$user_id}'";
      mysqli_query($db, $sql);

      $GLOBALS['user'][$user_id]['time_email_confirmed'] = $time;
      $response['status'] = 'welcome';
      $response['user'] = $user;
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function sendConfirmation(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args()[0];
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $user = getUser(array('route' => array('email' => $route['email']), 'dataset' => array('auth')))['response'];
    $user = $GLOBALS['user'][$user['id']];

    if($user['email_confirmation_code'] > 0 && $user['time_email_confirmed'] == 0){
      mailer($user['email'], array('email_confirmation_code' => $user['email_confirmation_code'], 'display_name' => $user['display_name']), 'confirmation');
      $response = 'sent';
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function signinPerson(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args()[0];
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $user = getUser(array('route' => array('email' => $route['email'], 'checkPassword' => md5($route['password']), 'no-cache' => true)));
    $user = $GLOBALS['user'][$user['id']];

    if($user_id && md5($route['password']) == $user['password']){

      $route['auth'] = $user['auth'];
      $user['auth'] = $route['auth'];

      $time = time();

      $sql = "UPDATE user SET time_visited = '{$time}' WHERE id = {$user['id']}";
      mysqli_query($db, $sql);

      $response = $user;
    } else {
      $response = "signin";
    }

    transaction(array('transaction' => $transaction, 'statechanges' => $statechanges));

    return $response;
  }

//Account created anonymously gets merged into a registered account with this function
  /*function mergeAccounts(){ //$merging_user_id, $user_id

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args()[0];
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    if($merging_user_id != $user_id){
      $result = mysqli_query($db, "SELECT id, email_confirmed FROM user WHERE id = '$user_id'");

      if($row = mysqli_fetch_array($result) && $row['email_confirmed'] > 0){

        //auth code, last visit, email_confirmation, confirmation code
        $auth = md5("LOL%I=ISUP".microtime()); //change to random log data stringified
        $time = time();

        $sql = "UPDATE user WHERE id = '{$row['id']}' SET auth = '$auth', last_visit = '$time', email_confirmed = '$time', email_confirmation_code = email_confirmation_code";
        mysqli_query($db, $sql);

        $user = getUser('route' => array('selector' => 'auth', 'id' => $auth, 'dataset' => array('auth')))['response'];

        $response = $user;
      }
    }

    transaction(array('function' => __FUNCTION__));

    return $response;
  }*/
?>