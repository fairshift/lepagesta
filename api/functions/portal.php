<?php
  function openPortal($db, $user, $place, $portal){

    if($user['confirmed'] && $place['id'] 
        && $portal['time_open'] < time() - 86400);
    }
    $sql = "INSERT INTO portal (user_id, place_id, time_open, time_closed, purpose) VALUES (".
                  "'{$user['id']}".
                  "'{$place['id']}".
                  "'$time_open', ".
                  "'$time_closed', ".
                  "'$purpose');";
    mysqli_query($db, $sql);

    $sql = "SELECT * FROM portal WHERE user_id = '$user_id' AND place_id = '$place_id' AND time_open = '$time_open' AND time_closed = '$time_closed'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    $row['content'] = addContent($db, $user_id, $GLOBALS['language_code'], 'portal', $row['id'], $purpose);

    return $row;
  }
?>