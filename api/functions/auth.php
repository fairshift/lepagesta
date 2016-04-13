<?php

//Automatic authentication & registration of anonymous account with every visit
  function authenticate($auth = false){

    $GLOBALS['newUser'] = false; //in case authentication code doesn't match, this triggers a new anonymous account (valid until cookie is available)
    $user_id = 0;

    if($auth){

      $user = getUser($db, $_GET['auth'], 'auth', array('auth'));

      if(isset($user['id'])){

        if($user['email_confirmation_time'] > 0 
          || $user['facebook_user_id'] > 0 
          || $user['twitter_user_id'] > 0
          /*|| $row['google_user_id'] > 0*/){
          $user['confirmed'] = true;
        } else {
          $user['confirmed'] = false;
        }

        mysqli_query($db, "UPDATE user SET last_visit = '".time()."' WHERE auth = '{$_GET['auth']}'");

      } else {
        $GLOBALS['newUser'] = true; //?
      }
    }

    if(!$auth || $GLOBALS['newUser'] == true)){

      $auth = md5("LOL%I=ISUP".microtime());
      mysqli_query($db, 'INSERT INTO user (auth, last_visit) VALUES ('.
                  "'$auth', ".
                  "'".time()."');" );

      $user = getUser($db, $auth, 'auth', array('auth'));
    }

    return $user;
  }

//Sign in / Register / Confirm router
  function passThrough($user_id){

    $continue = emailStatus($db, $user_id);

    if($continue == 'register'){
      $register = registerPerson($db);
    }

    if($continue == 'signin'){
      $user = signinPerson($db, $user_id);
    }

    if($user['id'] > 0){
      $response['user'] = $user;
      $response['status'] = 'welcome';
    } else {
      $response['status'] = emailStatus($db, $user_id);
    }

    return $response;
  }

  function emailStatus($user_id){

    if($user_id > 0 && filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL) == urldecode($_REQUEST['email'])){

      $row = getUser($db, urldecode($_REQUEST['email']), 'email', array('auth'));

      if($row['id'] && strlen($row['password']) == 32 && $row['email_confirmation_time'] > 0){
         return "signin";
      } elseif($row['id'] && strlen($row['password']) == 32 && $row['email_confirmation_time'] == 0){
        return "confirm";
      } else {
        return "register";
      }
    } else {
      return "invalidmail";
    }
  }

  function usernameExists(){
    $input = 
    if($user_id > 0 && input('username', 'string', '3')){

      $row = getUser($db, urldecode($_REQUEST['username']), 'username', array('auth'));
      if($row['id'] > 0){
        $response['status'] = "exists";
      } else {
        $response['status'] = "available";
      }

      return $response;
    }
  }

  function registerPerson($fields){

    if(getUser($db, filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL), 'email', array('auth')) == 0 
      && input('password', 'string', '6', '32') 
      && input('password_confirm', 'string', '6', '32') 
      && $_REQUEST['password'] == $_REQUEST['password_confirm'] 
      && input('username', 'string', '3', '32')
      && getUser($db, urldecode($_REQUEST['username']), 'username', array('auth')) == 0
    ){

      $email_confirmation_code = md5("LOL%I=ISUP".microtime());
      $sql = "INSERT INTO user (username, password, email, time_registered, email_confirmation_code, email_confirmation_time, last_visit) VALUES (".
                  "'".urldecode($_REQUEST['username'])."', ".
                  "'".md5($_REQUEST['password'])."', ".
                  "'{$_REQUEST['email']}', ".
                  "'".time()."', ".
                  "'{$email_confirmation_code}', ".
                  "'0',".
                  "'".time()."');";
      mysqli_query($db, $sql);

      mailer($_REQUEST['email'], array('email_confirmation_code' => $email_confirmation_code, 'username' => $_REQUEST['username']), 'confirmation');

      return true;
    }
  }

  function confirmEmail($user_id){

    if($user_id > 0 && input('code', 'string', '32', '32')){
      $user = getUser($db, $_REQUEST['code'], 'email_confirmation_code', array('auth'));
      if($user){
        $sql = "UPDATE user SET email_confirmation_time = '".time()."' WHERE id = {$user['id']}";
        mysqli_query($db, $sql);

        $user['email_confirmation_time'] = time();
        $response['status'] = 'welcome';
        $response['user'] = $user;

        return $response;
      }
    }
  }

  function resendConfirmation($user_id){

    $user = getUser($db, filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL), 'email', array('auth'));

    if($user['email_confirmation_code'] > 0 && $user['email_confirmation_time'] == 0){
      mailer($_REQUEST['email'], array('email_confirmation_code' => $user['email_confirmation_code'], 'username' => $user['username']), 'confirmation');
      return true;
    }
  }

  function signinPerson($user_id){

    $user = getUser($db, filter_var(urldecode($_REQUEST['email']), FILTER_VALIDATE_EMAIL), 'email', array('auth'));

    if($user_id > 0 && $user['id'] > 0 && md5($_REQUEST['password']) == $user['password']){
      $auth = md5("LOL%I=ISUP".microtime());
      $sql = "UPDATE user SET auth = '{$auth}' WHERE id = {$user['id']}";
      mysqli_query($db, $sql);

      $user['auth'] = $auth;

      return safeProfileData($user);
    } else {
      return "signin";
    }
  }

//Account created anonymously gets merged into a registered account with this function
  function mergeAccounts($merging_user_id, $user_id){

    if($merging_user_id != $user_id){
      $result = mysqli_query($db, "SELECT id, email_confirmed FROM user WHERE id = '$user_id'");

      if($row = mysqli_fetch_array($result) && $row['email_confirmed'] > 0){

        //auth code, last visit, email_confirmation, confirmation code
        $auth = md5("LOL%I=ISUP".microtime()); //change to random log data stringified
        $time = time();

        $sql = "UPDATE user WHERE id = '{$row['id']}' SET auth = '$auth', last_visit = '$time', email_confirmed = '$time', email_confirmation_code = email_confirmation_code";
        mysqli_query($db, $sql);

        $result = mysqli_query($db, "SELECT * FROM user WHERE id='{$row['id']}'");
        $row = mysqli_fetch_array($db, $result);

        return safeProfileData($row);
      }
    }

    return $response;
  }
?>