<?php

//Social media based authentication & more (not currently)
  //Facebook login
    if(!empty($_GET['code']) && !empty($_GET['state']) 
      && !empty($_SESSION['social_login_user_id'])){
        loginFacebook();
    }
  //Twitter login
    if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])
      && !empty($_SESSION['social_login_user_id'])){
      loginTwitter();
    }

//Authentication & user profile data
  $GLOBALS['user'] = authenticate(input( 'auth', 'md5', 32, 32 ), input( 'entity_id', 'integer', 1, 11 ));
  if(!isset($_REQUEST['call'])){
    $GLOBALS['user']['profile'] = array_merge($GLOBALS['user'], getUserProfile($GLOBALS['user']['id']));
  }
  $response['user'] = $GLOBALS['user'];

  if($GLOBALS['user']['email_confirmation_time'] > 0 || 
     $GLOBALS['user']['facebook_user_id'] > 0 || 
     $$GLOBALS['user']['twitter_user_id']){
    $response['status'] = 'welcome'; //user is on a path to building a (transparent?) identity
  }

//Entity - circle privileges !!! to-do

//Automatic authentication & registration of anonymous account with every visit
  function authenticate(){

    $db = $GLOBALS['db'];
    $route['auth'] = $_GET['auth'];
    transaction(__FUNCTION__, $route);

    $auth = $route['auth'];
    $newUser = false; //in case authentication code doesn't match, this triggers a new anonymous account (valid as long as cookie is available)

    if($auth){

      $user = getUser('route' => array('selector' => 'auth', 'id' => $auth, 'dataset' => array('auth')))['state'];

      if(isset($user['id'])){

        if($user['email_confirmation_time'] > 0 
          || $user['facebook_user_id'] > 0 
          || $user['twitter_user_id'] > 0
          /*|| $row['google_user_id'] > 0*/){
          $user['confirmed'] = true;
        } else {
          $user['confirmed'] = false;
        }

        if($GLOBALS['site']['id']){
          $sql_site = ", auth_site_id = '{$GLOBALS['site']['id']}'";
        }

        mysqli_query($db, "UPDATE user SET last_visit = '".time()."' $sql_site WHERE auth = '{$auth}'");
        transaction(array('function' => __FUNCTION__));

      } else {
        $newUser = true;
      }
    }

    if(!$auth || $newUser == true)){

      $auth = md5("LOL%I=ISUP".microtime());
      mysqli_query($db, 'INSERT INTO user (auth, last_visit, auth_site_id) VALUES ('.
                  "'{$route['auth']}', ".
                  "'".time()."', "
                  "'{$GLOBALS['site']['id']})" );

      $user = getUser('route' => array('selector' => 'auth', 'id' => $auth, 'dataset' => array('auth')))['state'];

      transaction(__FUNCTION__, $user);
    }

    return $user;
  }

//Sign in / Register / Confirm router
  function passThrough(){

    $input = func_get_args();
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    $continue = emailStatus('route' => $route);

    if($continue == 'register'){
      $register = registerPerson('route' => $route);
    }
    if($continue == 'signin'){
      $user = signinPerson('route' => $route);
    }

    if($user['id'] > 0){
      $response['user'] = $user;
      $response['status'] = 'welcome';
    } else {
      $response['status'] = emailStatus($db, $user_id);
    }

    transaction(array('function' => __FUNCTION__));

    return $response;
  }

  function emailStatus(){

    $user_id = $GLOBALS['user']['id'];

    $input = func_get_args();
    $route = $input['route'];

    transaction(__FUNCTION__, $route);

    if($user_id > 0 && filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL) == urldecode($_REQUEST['email'])){

      $row = getUser('route' => array('selector' => 'email', 'id' => $route['email'], 'dataset' => array('auth')))['state'];

      if($row['id'] && strlen($row['password']) == 32 && $row['email_confirmation_time'] > 0){
         $response = "signin";
      } elseif($row['id'] && strlen($row['password']) == 32 && $row['email_confirmation_time'] == 0){
        $response = "confirm";
      } else {
        $response = "register";
      }
    } else {
      $response = "invalidmail";
    }

    transaction(__FUNCTION__, $response);

    return $response;
  }

  function usernameExists(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    if($user_id && $route['username']){

      $row = getUser('route' => array('selector' => 'username', 'id' => $route['username'], 'dataset' => array('auth')))['state'];

      if($row['id'] > 0){
        $response['status'] = "exists";
      } else {
        $response['status'] = "available";
      }

      return $response;
    }
  }

  function registerPerson(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    if(!getUser('route' => array('selector' => 'email', 'id' => $route['email'], 'dataset' => array('auth')))['state'] 
      && $route['password'] == $route['password_confirm'] 
      && !getUser('route' => array('selector' => 'username', 'id' => $route['username'], 'dataset' => array('auth')))['state'] 
    ){

      if($GLOBALS['site']['id']){
        $sql_site_field = ', auth_site_id';
        $sql_site_value = ", '{$GLOBALS['site']['id']}'";
      }

      $email_confirmation_code = md5("LOL%I=ISUP".microtime());
      $sql = "INSERT INTO user (username, password, email, time_registered, email_confirmation_code, email_confirmation_time, last_visit {$sql_site_field}) VALUES (".
                  "'".$route['username']."', ".
                  "'".md5($route['password'])."', ".
                  "'{$route['email']}', ".
                  "'".time()."', ".
                  "'{$email_confirmation_code}', ".
                  "'0',".
                  "'".time()."'".
                  "$sql_site_value);";
      mysqli_query($db, $sql);

      mailer($route['email'], array('email_confirmation_code' => $email_confirmation_code, 'username' => $route['username']), 'confirmation');

      $user = getUser('route' => array('selector' => 'auth', 'id' => $auth, 'dataset' => array('auth')))['state'];
      transaction(__FUNCTION__, $user);

      return true;
    }
  }

  function confirmEmail(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    if($user_id && $route['code']){
      $user = getUser('route' => array('selector' => 'email_confirmation_code', 'id' => $route['code'], 'dataset' => array('auth')))['state'];
      if($user){

        if($GLOBALS['site']['id']){
          $sql_site = ", auth_site_id = '{$GLOBALS['site']['id']}'";
        }

        $sql = "UPDATE user SET email_confirmation_time = '".time()."' $sql_site WHERE id = {$user['id']}";
        mysqli_query($db, $sql);

        $user['email_confirmation_time'] = time();
        $response['status'] = 'welcome';
        $response['user'] = $user;
      }
    }

    transaction(array('function' => __FUNCTION__));

    return $response;
  }

  function resendConfirmation(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    $user = getUser('route' => array('selector' => 'email', 'id' => $route['email'], 'dataset' => array('auth')))['state'];

    if($user['email_confirmation_code'] > 0 && $user['email_confirmation_time'] == 0){
      mailer($route['email'], array('email_confirmation_code' => $user['email_confirmation_code'], 'username' => $user['username']), 'confirmation');
      $response = true;
    }

    transaction(array('function' => __FUNCTION__));

    return $response;
  }

  function signinPerson($user_id){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    transaction(__FUNCTION__, $route);

    $user = getUser('route' => array('selector' => 'email', 'id' => $route['email'], 'dataset' => array('auth')))['state'];

    if($user_id && $user['id'] > 0 && md5($route['password']) == $user['password']){
      $auth = md5("LOL%I=ISUP".microtime());

      if($GLOBALS['site']['id']){
        $sql_site = ", auth_site_id = '{$GLOBALS['site']['id']}'";
      }

      $sql = "UPDATE user SET auth = '{$auth}' $sql_site WHERE id = {$user['id']}";
      mysqli_query($db, $sql);

      $user['auth'] = $auth;

      $response = $user;
    } else {
      $response = "signin";
    }

    transaction(array('function' => __FUNCTION__));

    return $response;
  }

//Account created anonymously gets merged into a registered account with this function
  /*function mergeAccounts(){ //$merging_user_id, $user_id

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
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

        $user = getUser('route' => array('selector' => 'auth', 'id' => $auth, 'dataset' => array('auth')))['state'];

        $response = $user;
      }
    }

    transaction(array('function' => __FUNCTION__));

    return $response;
  }*/
?>