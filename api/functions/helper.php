<?php
  function log_action($db, $user_id, $action, $log = ""){
    $sql = "INSERT INTO log (user_id, time, request, log) VALUES (".
                "'$user_id', ".
                "'".time()."', ".
                "'$action', ".
                "'$log');";
    mysqli_query($db, $sql);
  }
?>