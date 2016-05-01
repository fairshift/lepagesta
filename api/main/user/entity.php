<?php
//Entity is a circle, which is registered as an entity (allowing privileged users to take actions on its behalf)

  function getEntity(){

    $db = $GLOBALS['db'];
 	  $site_id = $GLOBALS['site']['id'];
 	  $user_id = $GLOBALS['user_id'];

    if(in_array("auth", $preset)){
      $select = "id, id AS user_id, username, email, time_registered, last_visit, email_confirmation_time, facebook_user_id, twitter_user_id, site_language_id";
    } elseif(in_array("me", $preset)){
      $select = "id, id AS user_id, auth, password, username, email, email_confirmation_code, email_confirmation_time, time_registered, last_visit, facebook_user_id, twitter_user_id, site_language_id, profile_picture";
    } elseif(in_array("avatar", $preset)){
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
    $response = mysqli_fetch_array($result, MYSQLI_ASSOC));

    transaction(array('function' => __FUNCTION__));

	  return $response;
  }
?>