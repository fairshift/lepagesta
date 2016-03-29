<?php
  /*error_reporting(0);
  @ini_set('display_errors', 0);*/
/*
* This could be a beginning of something beautiful...
* This is not just another database, it's a metaphor...
*/

//Cross-origin enabler, to enable a bridge of data to a multitude of online services, onwards into the world of the living
  header('Access-Control-Allow-Origin: *');

/*
  Unlike the blockchain, this API is centrally organized - an all seeing eye
    It aims to enable some features a decentralized network of nodes holds, thus symbolizing the paradigm shift blockchain can bring about
    As such, it will facilitate bridging personal, private and common, as collective memories stored in nodes intertwine with conscious experiences

  Networks of brains and nodes are more aware when interacting to collaboratively shape a more complete, data driven picture
    A majority consensus on validity of data is needed for this to kick in, enabled by...
      -a process of collecting, storing and processing data should have trust at both both ends, social and technological
      -trust in validity of data is based on a mathematical proof that is socially accepted
      -on the social end trust is enabled by a critical mass of ethical peers, beholding personal traits such as [{"value": {"honesty", "integrity", "responsibility"...}}]
      -based on a need, from which changes sprout, reflected adoption of a process is social proof of trust
*/

  include('include.php');
  includeFunctions('dbwrapper.php');
  includeFunctions('safety.php'); //keep interactions with API/DB safe
  $db = dbWrapper();

/*
  A mirror neuron living in the cloud sees what isn't encrypted...

   * Gestures might be perceived by one or more people, done and received by [{"entity": {"person", "community", ...}}]
   * Gestures go around a sphere, leaving behind traces of #[{"cause &&|| effect": {"joy", "struggle"...}}]
   * Gestures can be reflected and appreciated by those who have experienced something in them.
   * For many reasons, pleasures as well as struggles, for there is something [{"meaningful", "of value", ...}] in them
*/

//Session - comments explain purpose of this API
  session_start();
  if(input('call', 'string', 1, 32)){
    if(strpos($_REQUEST['call'], '-') > 0){
      $buffer = explode("-", $_REQUEST['call']);
      $GLOBALS['o'] = $buffer[0];
      $GLOBALS['f'] = $buffer[1];
    } else {
      $GLOBALS['o'] = $_REQUEST['call'];
    }
  }

//Functions
  includeFunctions("auth.php"); //session, authentication, sign in/up to service
  includeFunctions("oauth.php"); //links to social media & other services
  includeFunctions("sphere.php"); //DB just in time maintenance
  includeFunctions("lang.php"); //language & translation functions
  includeFunctions("user.php"); //user passport object
  includeFunctions("cron.php"); //DB just in time maintenance
  includeFunctions("mailer/form-handler.php"); //email loop

  /*includeFunctions("circle.php");
  includeFunctions("place.php");
  includeFunctions("portal.php");
  includeFunctions("reflection.php");*/

//Facebook login
  if(!empty($_GET['code']) && !empty($_GET['state']) 
    && !empty($_SESSION['social_login_user_id'])){
      loginFacebook($db, $_SESSION['social_login_user_id']);
  }
//Twitter login
  if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])
    && !empty($_SESSION['social_login_user_id'])){
    loginTwitter($db, $_SESSION['social_login_user_id']);
  }

//Authentication
  $user = authenticate($db);
  $response['user'] = $user;

  if($user['email_confirmation_time'] > 0 || 
     $user['facebook_user_id'] > 0 || 
     $response['twitter_user_id']){

    $response['status'] = 'welcome'; //in the sense, user is building a transparent identity
  }

//Language
  $GLOBALS['languages'] = listLanguages($db, $user_id);
  $GLOBALS['language_id'] = $GLOBALS['languages']['en']['id'];
  $GLOBALS['default_language_id'] = $GLOBALS['languages']['en']['id'];
  $GLOBALS['language_code'] = 'en';

//Sphere is holding meanings within a common story - it's a pool of contextually related data, with services living on top of it
  /*
   *  Sphere is nesting sites, which use the API
   *  A browser extension will offer frontend editing to bring meta data to sites
   *  This API allows multiple applications on top of existing data (as is going to be the case with services living on the blockchain)
   */
  $GLOBALS['spheres'] = siteSpheres($db, $user, input('o', 'url', 1, 64), $GLOBALS['language_id']);
  $GLOBALS['site_id'] = $spheres['site_id'];

//Functions

  switch($GLOBALS['o']){ //Route: object-function          ---         <3

  //Intentions initiated, enacted in gestures - a fine blend of giving and receiving (offering, looking for)
    //# as key to codification of captured reflections social media, to fetch letters into blogchain rainbow spiral [{"topic": "impact of losing keys to data on future causes"}]
    case 'gesture':
      break;

    case 'fresh':
      //recentContent();
      break;

    case 'nearby':
      //nearbyContent();
      break;

    case 'profile':
      $structure = array('languages','messages','projects','spheres');
      $response = getProfile($db, $user, $structure);
      break;

    case 'place':
      if($GLOBALS['f'] == 'map'){

        if(!input('place_id', 'integer', 1, 11)){
          $place['user_id']       = $user_id;
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
        $response = mapPlace($db, $user_id, $place, $portal, $GLOBALS['language_id']);
      }
      break;

  //Event horizon
    case 'portal':
      if($GLOBALS['f'] == 'open'){

        $portal['place_id']     = input('place_id', 1, 11);
        $portal['purpose']      = input('purpose', 'string', 1, 140);
        $portal['time_open']    = (strtotime(input('time_open', 'string', 1)) === false) ? time() : strtotime(input('time_open'));
        $portal['time_closed']  = (strtotime(input('time_closed', 'string', 1)) === false) ? time() + 86400 : strtotime(input('time_closed'));

        $response = openPortal($db, $user, $portal, $GLOBALS['language_id']);
      }
      break;

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

  //Site language - adjusts to user language
    case 'siteText':
      $response = siteText($db, $user['id']);
      break;

    case 'languages':
      $response = $GLOBALS['languages'];
      break;

  //Register / signin
    case 'passport':
      $response = passThrough($db, $user['id']);
      break;

    case 'loginFacebook':
      $response = loginFacebook($db, $user['id']);
      break;

    case 'loginTwitter':
      $response = loginTwitter($db, $user['id']);
      break;

    case 'checkUsername':
      $response = usernameExists($db, $user['id']);
      break;

    case 'confirm':
      $response = confirmEmail($db, $user['id']);
      break;

    case 'resendConfirmation':
      $response = resendConfirmation($db, $user['id']);
      break;

  //gesture
    /*case 'offer':
      $response = offerGesture($db, $user_id);

    case 'reflect':
      $response = entangleReflection($db, $user_id);
      break;

    //reflection spheres on websites
    case 'sphere':
      $response = sphereData($db);
      break;*/

  }

//Safety
  //New auth key everytime
    /*if($GLOBALS['newUser'] == false){
      $response['auth'] = newAuth($_REQUEST['auth']);
    } */
  //Safe profile data
    $response['user'] = safeProfileData($response['user']);

//JSON response
  if(isset($response)){
    echo json_encode($response);
  }

//Maintenance functions after user's connection has been let go
  header( "Connection: Close" );

  //Update modified tables with default language translation
    if(isset($GLOBALS['translation_queue'])){
      foreach($GLOBALS['translation_queue'] AS $row){
        call_user_func('translateToDefault', $row);
      }
    }

  //Cache queues (outdated, new inserts)
    if(isset($GLOBALS['cache_queue'])){
      foreach($GLOBALS['cache_queue'] AS $setup => $object){
        cacheUpdate($db, $setup, $object);
      }
    }

  //Cron job check
    //cron($db);

  //Log
    siteLog($db, $user, $GLOBALS['site_id'], $GLOBALS['log']);

//This section is devoted to an example of a recent challenge to API/DB process/structure
  /* 
   * Examplary public initiative to [{"measure changes", "join our efforts"}] in inspiration - getting a telepathic echo of...
        dis/entanglement to representive post on Facebook timeline with public / private / encryption of meaning with transformation
        posts on Facebook as a medium to spread gestures of positive change (encryption with socially dispersed keys... who saw, remembered, commited energy, disseminated, dispersed?)
     [{"gesture": {"letting go": {"timeline": "facebook.com/blablaz",
                                               "giveaway": {"trickaweek.com", "antwalk.com", "timesaber.com"}},
                   "co-creating change": {"facebook.com/fairshift",
                                          "#lepo"}
     }]

   * Examplary private reflection 
   * Underground data  ["reflections":   {
                             "A weekend in Pula with Gaja...": {"spicy ingredients": {"Gmail letter", "Skype chat", "Facebook chat"}},
                                                                "memory links": {"a few recent inspiring conversations", "THPS3", "Inatri.eu"},
                                                                "portals": {"Jurij Jacko's discussion at an event in October 2016"
                                                                }
                                          },
                        "transformation": {
                             "#lepa": "#lepo" //what else is there to let go of?
                        }]
   
   * Viral DNA
      1. think of a change you're making in your life and memories entangled with friends on Facebook
      2. change your Facebook password, split it into a # of pieces to meaningfully reflect your transformation
      3. check your timeline to see if any of the 9 friends there understand you in that manner
      4. permanently delete a post, reflecting the part of your identity you're about to change
      5. message your friends a piece of your password and ask them to "!!!codify their identity as yours" [{"the keys to your identity", "..."}]
      4. set your profile picture and enrich it socially as on this example -> facebook.com/blablaz
      5. comment your profile picture with a wild dream stemming from this change
   
   */
?>