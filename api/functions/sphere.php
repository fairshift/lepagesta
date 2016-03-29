<?php
  function siteSpheres($db, $user, $site, $language_id){

    //Cache (what goes in & what comes out)
      $cache['route']['site']['url'] = $site;
      $cache['structure']['site'] = '*';
      $cache['structure']['site_sphere'] = '*';
      $cache['structure']['sphere'] = '*';
      $cache['structure']['sphere']['translation'] = '*';

    if(!$response = existingCache($db, $cache)){

      $sql = "SELECT site.*, site.id AS site_id, site_sphere.*, sphere.*, sphere.id AS sphere_id FROM site, site_sphere, sphere WHERE ".
                    "site.url = '$site' AND ".
                    "site.id = site_sphere.site_id AND ".
                    "site_sphere.sphere_id = sphere.id AND ".
                    "site.removed = 0 AND site_sphere.removed = 0 AND sphere.removed = 0";

      $result = mysqli_query($db, $sql);
      while($row = mysqli_fetch_array($result)){
        if($content = getContent($db, $user, $language_id, 'sphere', $row['sphere_id'], true)){
          $response[$row['sphere_id']] = array_merge($row, $content);
        } else {
          $response[$row['sphere_id']] = $row;
        }
      }
      $cache['object'] = $response;
      cacheUpdate($db, $cache);
    }
    return $response;
  }
?>