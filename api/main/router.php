<?php
//Call to API is following routes...
function router(){
  switch($GLOBALS['o']){ //route call: object/function   <---   data intake

  //Stream of fresh data
    case 'fresh':
      if(input('circle_id', 'integer', 0, 11)){

      } else {

      }
      break;

  //Stream of nearby data
    case 'nearby':
      break;

  //Intentions initiated, enacted in gestures - a fine blend of giving and receiving (offering, looking for)
    case 'gesture':
      if($GLOBALS['f'] == 'offer'){

      }
      break;

    case 'blog':
      /*if($GLOBALS['f'] == 'add'){
        if(!input('branch_id', 'integer', 1, 11)){

          //Add new content
          $blog['time']        = (input('time', 1, 11)) ? input('time', 1, 11) : time();
        } else {

          //Add content to current branch
          $blog['branch_id']   = input('branch_id', 'integer', 1, 11);
        }

        $blog['user_id']       = $GLOBALS['user_id'];
        $blog['title']         = input('title', 1, 64);
        $blog['content']       = input('content', 'string', 1);
        $blog['time_updated']  = (input('time', 1, 11)) ? input('time', 1, 11) : time();
      }
      if($GLOBALS['f'] == 'remove'){
        if(input('content_id', 'integer', 1, 11)){
          
        }
        if(input('branch_id', 'integer', 1, 11)){
          
        }
        if(input('state_id', 'integer', 1, 11)){
          
        }
      }
      if($GLOBALS['f'] == 'fork'){
        
      }
      if($GLOBALS['f'] == 'get'){
        //get what's visible in current circle?
        //getContent();
      }*/
      break;

    case 'namespace': //user/content_state OR circle/content_branch (combinations among user & circle and content_branch & content_state) 
      if($GLOBALS['f'] == 'add'){
        
      }
      if($GLOBALS['f'] == 'get'){
        
      }
      break;

    case 'media':
      break;

    case 'post':
      break;

    case 'profile':
      /*$structure = array('languages','messages','projects','spheres');
      $response = getProfile($db, $user, $structure);*/
      break;

    case 'place':
      /*if($GLOBALS['f'] == 'map'){

        if(!input('place_id', 'integer', 1, 11)){
          $place['user_id']       = $GLOBALS['user']['id'];
          $place['title']         = input('title', 1, 64);
          $place['description']   = input('description', 'string', 0, 256);
          $place['address']       = input('address', 'string', 1, 128);
          $place['url']           = input('url', 'string', 0, 128);
          $place['lat']           = input('lat', 'number', 1);
          $place['lng']           = input('lng', 'number', 1);
          $place['time']          = time();
          $place['time_updated']  = time();
        } else {
          $place['id']            = input('place_id', 'integer', 1, 11);
          $place['title']         = input('title', 1, 64);
          $place['description']   = input('description', 'string', 1, 256);
          $place['time_updated']  = time();
        }
        $response = mapPlace($db, $GLOBALS['user']['id'], $place, $GLOBALS['language_id']);
      }*/
      break;

  //Event horizon
    case 'portal':
      /*if($GLOBALS['f'] == 'open'){

        if(!input('place_id', 'integer', 1, 11)){
          $place['user_id']       = $GLOBALS['user']['id'];
          $place['title']         = input('title', 1, 64);
          $place['description']   = input('description', 'string', 0, 256);
          $place['address']       = input('address', 'string', 1, 128);
          $place['url']           = input('url', 'string', 0, 128);
          $place['lat']           = input('lat', 'number', 1);
          $place['lng']           = input('lng', 'number', 1);
          $place['time']          = time();
          $place['time_updated']  = time();

          $response = mapPlace($db, $GLOBALS['user']['id'], $place, $GLOBALS['language_id']);
          $_REQUEST['place_id'] = $response['place_id'];
        }

        $portal['place_id']     = input('place_id', 'integer', 1, 11);
        $portal['purpose']      = input('purpose', 'string', 1, 140);
        $portal['time_open']    = (strtotime(input('time_open', 'string', 1)) === false) ? time() : strtotime(input('time_open'));
        $portal['time_closed']  = (strtotime(input('time_closed', 'string', 1)) === false) ? time() + 86400 : strtotime(input('time_closed'));

        $response = openPortal($db, $GLOBALS['user']['id'], $portal, $GLOBALS['language_id']);
      }*/
      break;

  //Site language - adjusts to user language
    case 'localization':
      if($GLOBALS['site']['id']){
        $route['site_id'] = $GLOBALS['site']['id'];
        $response['siteText'] = getLocalization(array('route' => $route));
      }
      break;

  //Register / signin (user account)
    case 'passport':
      //Signin
      $route['email'] =             input('email', 'email', 1, 64);
      $route['password'] =          input('password', 'string', 6, 32);
      //+Register
      $route['password_confirm'] =  input('password_confirm', 'string', 6, 32);
      $route['username'] =          input('username', 'string', 3, 32);

      $response = passThrough(array('route' => $route));
      break;

    case 'loginFacebook':
      $response = loginFacebook();
      break;

    case 'loginTwitter':
      $response = loginTwitter();
      break;

    case 'checkUsername':
      $response = usernameExists();
      break;

    case 'confirm':
      $route['code'] = input('code', 'string', 32, 32);
      $response = confirmEmail(array('route' => $route));
      break;

    case 'resendConfirmation':
      $route['email'] = input('email', 'email', 1, 64);
      $response = resendConfirmation(array('route' => $route));
      break;

//Development horizon
    //Blogchain of what happened as value holder and semi-transparent layer ["links": {"point of interest", ""}]
    /*
      Meteor shower of flowing values as pilars for decisions. ["value": "#rrggbb"]
      With life and learning the unknown changes happen
      Crucial questions are answered in time
    */
    case 'reflection':
      break;

  //Plan horizon - looking ahead together, forming a common vision
    case 'project':
      //
      break;
  }

  return $response;
}
?>