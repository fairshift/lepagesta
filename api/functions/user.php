<?php
  function getUser($db, $id, $selector, $preset){

    if(in_array("public", $preset)){
      $select = "id, username, email, time_registered, last_visit, email_confirmation_time, facebook_user_id, twitter_user_id";
    } elseif(in_array("me", $preset)){
      $select = "id, auth, password, username, email, email_confirmation_code, email_confirmation_time, time_registered, last_visit, facebook_user_id, twitter_user_id";
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
    if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
      return $row;
    } else {
      return 0;
    }
  }
?>