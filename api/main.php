<?php
  /*error_reporting(0);
  @ini_set('display_errors', 0);*/
/*
* This could be a beginning of something beautiful...
* This database is a metaphor...
*/

//Cross-origin enabler, to enable a bridge of data to a multiverse of online services, onwards into the world of the living
  header('Access-Control-Allow-Origin: *');

/*
  Unlike with the blockchain, image of data is centrally managed
  This database can't check if code really did what the resulting data did, yet. In other words, trusting the central authority managing the website is necessary, still

    This API aims to enable some features a decentralized network of nodes running blockchain holds, thus symbolizing the paradigm shift blockchain can bring about
      - data pool accessible to services user chooses
      - mathematical proof for validity of data (might be impossible without trusting the platform)
      - trust in central authority's proper management is needed until every function/object dealing with data signs, without ability to tamper with internal states
    As such, it could facilitate bridging personal, private and common, as collective memories stored in data blocks intertwine with conscious experiences during this shift

  Networks of brains and nodes are more aware when interacting to collaboratively shape a more complete, data driven picture
    A majority consensus on validity of data is needed for this to kick in, enabled by...
      -a process of collecting, storing and processing data should have trust at both both ends, social and technological
      -trust in validity of data is based on a mathematical proof that is socially accepted (merkletree)
      -on the social end trust is enabled by a critical mass of ethical peers, beholding personal traits such as [{"value": {"honesty", "integrity", "responsibility"...}}]
      -based on a need from which changes sprout, adoption of a process is social proof of trust
*/

  include('includeFunctions.php'); //Check out for overview of functionalities
  dbWrapper(); //$GLOBALS['db'] stores database object

/*
  With blockchain, any changes to data states are stored in the distributed database
  Ethereum's consensus charges computing power to the user (in amount of Ether as currency), providing incentive for script efficiency

  * With this database interactions are logged, enabling tracking changes to data, access control and measuring script efficiency
*/
  $transaction = transaction(array('function' => 'main.php', 'route' => $_REQUEST));
  session_start();

//Site
  $GLOBALS['site'] = getSite('route' => array('domain' => input('site', 'string', 1, 64)))['state']; //in $GLOBALS['user']['auth_site_id']; as set by authenticate();

//User authentication & basic necessary data
  $GLOBALS['user'] = authenticate('route' => array('auth' => input( 'auth', 'md5', 32, 32 )));
  if(!isset($_REQUEST['call'])){
    //$GLOBALS['user']['profile'] = array_merge($GLOBALS['user'], getUser($GLOBALS['user']['id']));
  }
  $response['user'] = $GLOBALS['user'];
  if($GLOBALS['user']['email_confirmation_time'] > 0 || 
     $GLOBALS['user']['facebook_user_id'] > 0 || 
     $$GLOBALS['user']['twitter_user_id']){
    $response['status'] = 'welcome'; //in the sense that user is on a path to building a (transparent?) identity
  }
  $GLOBALS['entity'] = getEntity('entity_id' => input('entity_id', 'integer', 1, 11));

//Language
  $GLOBALS['languages'] = listLanguages($GLOBALS['user']['id']); //list all languages ('googletranslate' 0 or 1?)
  $GLOBALS['default_language_id'] = $GLOBALS['languages']['en']['id']; //all data is envisioned to be translated into one default language for search capabilites
  //$GLOBALS['language_code'] = 'en'; //default site language is English
  $GLOBALS['language_id'] = (input('lang', 'string', 1, 10)) ? $GLOBALS['languages'][input('lang', 'string', 1, 10)]['id'] : exit; //language of current query - now set to english, soon to be reflecting the user's query

//Circle
  /*if(input('circle_id', 'integer', 1, 11)){
    $GLOBALS['circle_id'] = $_REQUEST['circle_id'];
  }
  $GLOBALS['circles'] = []; //circle caching object*/

//Call to API 
  if(strpos($_REQUEST['call'], '/') > 0){
    $buffer = explode('/', $_REQUEST['call']);
    $GLOBALS['o'] = $buffer[0]; //object
    $GLOBALS['f'] = $buffer[1]; //function
  } else {
    $GLOBALS['o'] = $_REQUEST['call']; //object
  }

//Available functionalities
  switch($GLOBALS['o']){ //Route: object/function   <---   data flow

    case 'blog':
      if($GLOBALS['f'] == 'add'){
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
        if(input('iteration_id', 'integer', 1, 11)){
          
        }
      }
      if($GLOBALS['f'] == 'fork'){
        
      }
      if($GLOBALS['f'] == 'get'){
        //get what's visible in current circle?
        //getContent();
      }
      break;

    case 'nameSpace': //user/content_state OR circle/content_branch (combinations among user & circle and content_branch & content_state) 
      if($GLOBALS['f'] == 'add'){
        
      }
      if($GLOBALS['f'] == 'get'){
        
      }
      break;

    case '':
      break;

    case 'media':
      break;

  //Intentions initiated, enacted in gestures - a fine blend of giving and receiving (offering, looking for)
    //# as key to codification of captured reflections social media, to fetch letters into blogchain rainbow spiral [{"topic": "impact of losing keys to data on future causes"}]
    case 'gesture':

      break;

    case 'fresh':
      if(input('circle_id', 'integer', 0, 11)){

      } else {

      }
      break;

    case 'nearby':
      //nearbyContent();
      break;

    case 'post':

      break;

    case 'profile':
      $structure = array('languages','messages','projects','spheres');
      $response = getProfile($db, $user, $structure);
      break;

    case 'place':
      if($GLOBALS['f'] == 'map'){

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
      }
      break;

  //Event horizon
    case 'portal':
      if($GLOBALS['f'] == 'open'){

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
      $response = siteText($GLOBALS['site_id']);
      break;

    case 'languages':
      $response = $GLOBALS['languages'];
      break;

  //Register / signin
    case 'passport':
      //Signin
      $route['email'] =             input('email', 'email', 1, 64);
      $route['password'] =          input('password', 'string', 6, 32);
      //+Register
      $route['password_confirm'] =  input('password_confirm', 'string', 6, 32);
      $route['username'] =          input('username', 'string', 3, 32);

      $response = passThrough('route' => $route);
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
      $response = confirmEmail('route' => $route);
      break;

    case 'resendConfirmation':
      $route['email'] = input('email', 'email', 1, 64);
      $response = resendConfirmation('route' => $route);
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
  transaction(array('function' => 'main.php'));

  //Update modified tables with default language translation
    if(isset($GLOBALS['translation_queue'])){
      foreach($GLOBALS['translation_queue'] AS $row){
        translateToDefault($row);
      }
    }


  //Cron job check
    //cron($db);

  //Save transactions
  newTransaction();

//This section is devoted to an example of a recent challenge to current order of API/DB process/structure
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