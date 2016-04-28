<?php
  function nearbyPlaces($db, $user, $place, $language_id = $GLOBALS['language_id']){

    if($place['id']){
      if($row = getContent($db, $user['id'], $language_id, 'place', $place_id)){
        $place['id'] = $row['id'];
      } else {
        $place['id'] = 0;
      }
    }

    //Use the nearest place
    if(!$place['id']){
      if(input('bound_n', 'float', 1) && input('bound_s', 'float', 1) && input('bound_e', 'float', 1) && input('bound_w', 'float', 1)){
        $sql = "SELECT id, lat, lng FROM place WHERE lat < '{$_REQUEST['bound_n']}' AND lat > '{$_REQUEST['bound_s']}' AND lng < '{$_REQUEST['bound_e']}' AND lng > '{$_REQUEST['bound_w']}' AND removed = 0";
        $result = mysqli_query($db, $sql);
        while($row = mysqli_fetch_array($result)){
          //!!! fix this
          $c = sqrt($row['lat']^2 + $row['lng']^2);
          $results[$c] = $row['id'];
        }

        $smallest = "1337";
        $smallest_id = 0;
        foreach($results AS $key => $value){
          if($smallest == "1337" || $c < $smallest){
            $smallest = $key;
            $smallest_id = $value;
          }
        }
        if($smallest_id != 0){
          $place['id'] = $smallest_id;
        }
      }
    }
  }

  //Add new / update place
  function mapPlace($db, $user, $place, $language_id = $GLOBALS['language_id']){
    if(!$place['id']){
      if($place['title'] && $place['lat'] && $place['lng'] && $place['address']){
        $row = addContent($db, $user_id, $GLOBALS['language_id'], 'place', 'new', $place);
        return $row['status_code'] = 200;
      } else {
        $row = $place;
        return $row['status_code'] = 400;
      }
    } else {
      //A place is established
      $row = getContent($db, $user['id'], $language_id, 'place', $place_id))
      return $row['status_code'] = 200;
    }
  }
?>