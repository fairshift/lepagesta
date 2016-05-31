<?php

//Language functions
  function getLanguageList(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args()[0];

    //Function router
    $route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $dataset));

    $route['table'] = 'language';
    if($route['languages']){
      foreach($route['languages'] AS $language_id){
        $route['where']['language_id'][] = $language_id;
      }
    } else {
      $route['where'] = '*';
    }
    $query = getNode(array('route' => $route));

    transaction(array('transaction' => $transaction));
  }

//Localized site texts
  function getLocalization(){

    $db = $GLOBALS['db'];
    $user_id = $GLOBALS['user']['id'];
    $entity_id = ($GLOBALS['entity']['id']) ? $GLOBALS['entity']['id'] : null; //user acting on behalf of a circle of people (requires privilege_represent or privilege_manage)

    $input = func_get_args()[0];

    //Function router
    $route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route, 'dataset' => $dataset));

    if($route['site_id'] > 0){

        $route['table'] = 'site_language';

        $sql =  "SELECT site_language.id, site_language.field FROM site_language ".
                "INNER JOIN ( ".
                    "SELECT field, MAX(time) time ".
                    "FROM site_language ".
                    "GROUP BY field ".
                ") buffer ON site_language.field = buffer.field AND site_language.time = buffer.time WHERE site_language.site_id = '{$route['site_id']}'";
        $result = mysqli_query($db, $sql);

        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

          $route['id'] = $row['id'];
          getNode(array('route' => $route));

          $response[$row['field']] = $row['id'];
        }
      }

      return $response;
  }

  function site_languageNode(){

  }

  include("vendor/Google/Translate/TranslateClient.php");
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