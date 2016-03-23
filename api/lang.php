<?php
  function siteText($db, $user_id){
    if(input('code', 'string', '1', '10')){
      $sql =  "SELECT site_language.field, site_language.content FROM site_language ".
          "INNER JOIN ( ".
              "SELECT field, MAX(time) time ".
              "FROM site_language ".
              "GROUP BY field ".
          ") buffer ON site_language.field = buffer.field AND site_language.time = buffer.time";
        
      $result = mysqli_query($db, $sql);
      while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $response[$row['field']] = $row['content'];
      }

      return $response;
    }
  }

  function listLanguages($db, $user_id){
    if($user_id > 0){
      $sql = "SELECT * FROM language";
      $result = mysqli_query($db, $sql);
      while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $response[$row['code']] = $row;
      }
      return $response;
    }
  }
?>