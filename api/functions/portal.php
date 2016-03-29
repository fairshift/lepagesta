<?php
  function openPortal($db, $user, $portal, $language_id = $GLOBALS['language_id']){

    if($user['confirmed'] && $place['id']){
        //&& $portal['time_open'] < time() - 86400){
      $row = addContent($db, $user, $language_id, 'portal', 'new', $portal);
      if($row){
        $row['status_code'] = '200';
      } else {
        $row['status_code'] = '400';
      }
    }
  }
?>