<?php
//Call to API is following routes...
function router($call){

  $GLOBALS['call_inprocess'];
  if(strpos($call, '/') > 0){
    $GLOBALS['o'] = $buffer[0]; //object
    $GLOBALS['f'] = $buffer[1]; //function
  } else {
    $GLOBALS['o'] = $_REQUEST['call']; //object
  }

  //Social media based authentication - !!! multi-site implementation needs a stored site url to redirect back to
    //Facebook login
      if(!empty($_GET['code']) && !empty($_GET['response']) 
        && !empty($_SESSION['social_login_user_id'])){
          loginFacebook();
      }
    //Twitter login
      if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])
        && !empty($_SESSION['social_login_user_id'])){
        loginTwitter();
      }

  switch($o){ //route call: object/function   <---   data intake

  //User authentication & necessary basic data
    case 'auth':
      $route['auth'] = input($call, 'auth', 'string', 32, 32);
      if($route['auth']){
        $GLOBALS['user'] = authenticate(array('route' => $route)));
        $response['auth'] = $GLOBALS['user']['auth'];
      }
      break;

  //Register / signin (user account)
    case 'passport':

      if(!$f){
        //Signin
        $route['email'] =             input($call, 'email', 'email', 1, 64);
        $route['password'] =          input($call, 'password', 'string', 6, 32);
        //+Register
        $route['password_confirm'] =  input($call, 'password_confirm', 'string', 6, 32);
        $route['display_name'] =      input($call, 'display_name', 'string', 3, 32);

        $response = passThrough(array('route' => $route));

      } elseif($f == 'loginFacebook'){
        $response = loginFacebook();

      } elseif($f == 'loginTwitter'){
        $response = loginTwitter();

      } elseif($f == 'checkDisplayName'){
        $route['display_name'] = input($call, 'display_name', 'string', 3, 32);
        $response = displayNameExists(array('route' => $route));

      } elseif($f == 'confirm'){
        $route['code'] = input($call, 'code', 'string', 32, 32);
        $response = confirmEmail(array('route' => $route));

      } elseif($f == 'resendConfirmation'){
        $route['email'] = input($call, 'email', 'email', 1, 64);
        $response = sendConfirmation(array('route' => $route));
      }
      break;

//Is user acting on behalf of an entity? ˇ requires permission_represent, permission_manage within a circle
  //$GLOBALS['entity'] = getEntity(array('entity_id' => input($call, 'entity_id', 'integer', 1, 11)));

//With Ethereum's blockchain, user accounts are hashes that already exist

  //Stream of fresh data
    case 'fresh':
      if(input($call, 'circle_id', 'integer', 0, 11)){

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
        if(!input($call, 'branch_id', 'integer', 1, 11)){

          //Add new content
          $blog['time']        = (input($call, 'time', 1, 11)) ? input($call, 'time', 1, 11) : time();
        } else {

          //Add content to current branch
          $blog['branch_id']   = input($call, 'branch_id', 'integer', 1, 11);
        }

        $blog['user_id']       = $GLOBALS['user_id'];
        $blog['title']         = input($call, 'title', 1, 64);
        $blog['content']       = input($call, 'content', 'string', 1);
        $blog['time_updated']  = (input($call, 'time', 1, 11)) ? input($call, 'time', 1, 11) : time();
      }
      if($GLOBALS['f'] == 'remove'){
        if(input($call, 'content_id', 'integer', 1, 11)){
          
        }
        if(input($call, 'branch_id', 'integer', 1, 11)){
          
        }
        if(input($call, 'state_id', 'integer', 1, 11)){
          
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

        if(!input($call, 'place_id', 'integer', 1, 11)){
          $place['user_id']       = $GLOBALS['user']['id'];
          $place['title']         = input($call, 'title', 1, 64);
          $place['description']   = input($call, 'description', 'string', 0, 256);
          $place['address']       = input($call, 'address', 'string', 1, 128);
          $place['url']           = input($call, 'url', 'string', 0, 128);
          $place['lat']           = input($call, 'lat', 'number', 1);
          $place['lng']           = input($call, 'lng', 'number', 1);
          $place['time']          = time();
          $place['time_updated']  = time();
        } else {
          $place['id']            = input($call, 'place_id', 'integer', 1, 11);
          $place['title']         = input($call, 'title', 1, 64);
          $place['description']   = input($call, 'description', 'string', 1, 256);
          $place['time_updated']  = time();
        }
        $response = mapPlace($db, $GLOBALS['user']['id'], $place, $GLOBALS['language_id']);
      }*/
      break;

  //Event horizon
    case 'portal':
      /*if($GLOBALS['f'] == 'open'){

        if(!input($call, 'place_id', 'integer', 1, 11)){
          $place['user_id']       = $GLOBALS['user']['id'];
          $place['title']         = input($call, 'title', 1, 64);
          $place['description']   = input($call, 'description', 'string', 0, 256);
          $place['address']       = input($call, 'address', 'string', 1, 128);
          $place['url']           = input($call, 'url', 'string', 0, 128);
          $place['lat']           = input($call, 'lat', 'number', 1);
          $place['lng']           = input($call, 'lng', 'number', 1);
          $place['time']          = time();
          $place['time_updated']  = time();

          $response = mapPlace($db, $GLOBALS['user']['id'], $place, $GLOBALS['language_id']);
          $_REQUEST['place_id'] = $response['place_id'];
        }

        $portal['place_id']     = input($call, 'place_id', 'integer', 1, 11);
        $portal['purpose']      = input($call, 'purpose', 'string', 1, 140);
        $portal['time_open']    = (strtotime(input($call, 'time_open', 'string', 1)) === false) ? time() : strtotime(input($call, 'time_open'));
        $portal['time_closed']  = (strtotime(input($call, 'time_closed', 'string', 1)) === false) ? time() + 86400 : strtotime(input($call, 'time_closed'));

        $response = openPortal($db, $GLOBALS['user']['id'], $portal, $GLOBALS['language_id']);
      }*/
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

  //Site-specific functions
    case 'site':
      if($f == 'domain' || $f == 'id'){ //apply domain / site_id, as passed from frontend
        $response = $GLOBALS['site'] = getSite(array('route' => array( 'domain' => input($call, 'domain', 'string', 1, 64),
                                                         'site_id' => input($call, 'site_id', 'integer', 1, 11)) ));

      } elseif($f == 'localization'){  //Site language - adjusts to user language
        if($GLOBALS['site']['id']){
          $route['site_id'] = $GLOBALS['site']['id'];
          $response = getLocalization(array('route' => $route));
        }
      }
    break;
  }

  //Server time
    case 'server-time':
      $response['time'] = time();
      break;

  return $response; 
}
?>