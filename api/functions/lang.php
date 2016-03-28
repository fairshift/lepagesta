<?php

  //Language functions
    function listLanguages($db, $user_id){
      if($user_id > 0){

        $sql = "SELECT * FROM language";
        $result = mysqli_query($db, $sql);

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
          $response[$row['code']] = $row;
          $response[$row['id']] = $row;
        }

        return $response;
      }
    }

  //Localized site text
    function siteText($db, $user_id){

      if(input('code', 'string', '1', '10') && $user_id > 0){

        $sql =  "SELECT sphere_site_language.field, sphere_site_language.content FROM sphere_site_language ".
                "INNER JOIN ( ".
                    "SELECT field, MAX(time) time ".
                    "FROM sphere_site_language ".
                    "GROUP BY field ".
                ") buffer ON sphere_site_language.field = buffer.field AND sphere_site_language.time = buffer.time";
        $result = mysqli_query($db, $sql);

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
          $response[$row['field']] = $row['content'];
        }

        return $response;
      }
    }

  include("Google/translate/TranslateClient.php");
    function translate($input, $from, $to){
      $google = new TranslateClient(); // Default is from 'auto' to 'en'
      $google->setSource($from); // Translate from English
      $google->setTarget($to); // Translate to Georgian
      
      if(is_array($input)){
        foreach($input AS $key => $value){
          $response[$key] = $google->translate($input);
        }
      } else {
        $response = $google->translate();
      }

      return $response;
    }
?>