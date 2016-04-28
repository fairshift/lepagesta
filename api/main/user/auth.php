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

//Entity - circle privileges !!! to-do

//Automatic authentication & registration of anonymous account with every visit
  function authenticate(){

    $db = $GLOBALS['db'];
    $route['auth'] = input('auth', 'string', 32, 32);
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $auth = $route['auth'];
    $newUser = false; //in case authentication code doesn't match, this triggers a new anonymous account (valid as long as cookie is available)

    if($route['auth']){

      $user = getUser('route' => array('selector' => 'auth', 'id' => $route['auth'], 'dataset' => array('me')))['state'];

      if(isset($user['id'])){

        if($user['time_email_confirmed'] > 0 
          || $user['facebook_user_id'] > 0 
          || $user['twitter_user_id'] > 0
          /*|| $row['google_user_id'] > 0*/){
          $user['confirmed'] = true;
        } else {
          $user['confirmed'] = false;
        }

        mysqli_query($db, "UPDATE user SET time_visited = '".time()."' WHERE auth = '{$route['auth']}'");
        $statechanges['user.id']['time_visited'] = time();

      } else {
        $newUser = true;
      }
    }

    if(!$route['auth'] || $newUser == true)){

      $auth = md5("LOL%I=ISUP".microtime());
      mysqli_query($db, 'INSERT INTO user (auth, auth_time, time_visited) VALUES ('.
                  "'{$route['auth']}', ".
                  "'".time()."', "
                  "'".time()."'" );

      $user = getUser('route' => array('selector' => 'auth', 'id' => $auth, 'dataset' => array('me')))['state'];
      $statechanges = $user;
    }

    transaction(array('transaction' => $transaction, 'statechanges' => $statechanges));

    return $user;
  }

//Sign in / Register / Confirm router
  function passThrough(){

    $input = func_get_args();
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

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

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function emailStatus(){

    $user_id = $GLOBALS['user']['id'];

    $input = func_get_args();
    $route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if($user_id > 0 && filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL) == urldecode($_REQUEST['email'])){

      $row = getUser('route' => array('selector' => 'email', 'id' => $route['email'], 'dataset' => array('auth')))['state'];

      if($row['id'] && strlen($row['password']) == 32 && $row['time_email_confirmed'] > 0){
         $response = "signin";
      } elseif($row['id'] && strlen($row['password']) == 32 && $row['time_email_confirmed'] == 0){
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

  function usernameExists(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if($user_id && $route['username']){

      $row = getUser('route' => array('selector' => 'username', 'id' => $route['username'], 'dataset' => array('auth')))['state'];

      if($row['id'] > 0){
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
    $input = func_get_args();
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if(!getUser('route' => array('selector' => 'email', 'id' => $route['email']), 'dataset' => array('auth'))['state']['user_id'] 
      && $route['password'] == $route['password_confirm'] 
      && !getUser('route' => array('selector' => 'username', 'id' => $route['username']), 'dataset' => array('auth'))['state']['user_id'] 
    ){

      $email_confirmation_code = md5("LOL%I=ISUP".microtime());
      $sql = "INSERT INTO user (username, password, email, time_registered, email_confirmation_code, time_email_confirmed, time_visited) VALUES (".
                  "'".$route['username']."', ".
                  "'".md5($route['password'])."', ".
                  "'{$route['email']}', ".
                  "'".time()."', ".
                  "'{$email_confirmation_code}', ".
                  "'0',".
                  "'".time()."');";
      mysqli_query($db, $sql);

      mailer($route['email'], array('email_confirmation_code' => $email_confirmation_code, 'username' => $route['username']), 'confirmation');

      $user = getUser('route' => array('selector' => 'auth', 'id' => $auth), 'dataset' => array('me'))['state'];
    }

    transaction(array('transaction' => $transaction, 'statechanges' => $user));

    return $user;
  }

  function confirmEmail(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    if($user_id && $route['code']){
      $user = getUser('route' => array('selector' => 'email_confirmation_code', 'id' => $route['code']), 'dataset' => array('auth'))['state'];
      if($user){

        $sql = "UPDATE user SET time_email_confirmed = '".time()."' $sql_site WHERE id = {$user['id']}";
        mysqli_query($db, $sql);

        $time = time();

        $user['time_email_confirmed'] = $time;
        $response['status'] = 'welcome';
        $response['user'] = $user;

        $statechanges['time_email_confirmed'] = $time;
      }
    }

    transaction(array('transaction' => $transaction, 'statechanges' => $statechanges));

    return $response;
  }

  function resendConfirmation(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $user = getUser('route' => array('selector' => 'email', 'id' => $route['email']) 'dataset' => array('auth'))['state'];

    if($user['email_confirmation_code'] > 0 && $user['time_email_confirmed'] == 0){
      mailer($route['email'], array('email_confirmation_code' => $user['email_confirmation_code'], 'username' => $user['username']), 'confirmation');
      $response = true;
    }

    transaction(array('transaction' => $transaction));

    return $response;
  }

  function signinPerson(){

    $user_id = $GLOBALS['user']['id'];
    $input = func_get_args();
    $route = $input['route'];
    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

    $user = getUser('route' => array('selector' => 'email', 'id' => $route['email'], 'dataset' => array('auth')))['state'];

    if($user_id && md5($route['password']) == $user['password']){

      $route['auth'] = $user['auth'];
      $user['auth'] = $route['auth'];

      $time = time();

      $sql = "UPDATE user SET time_visited = '{$time}' WHERE id = {$user['id']}";
      mysqli_query($db, $sql);

      $statechanges['user.id'] = '{$time}';

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