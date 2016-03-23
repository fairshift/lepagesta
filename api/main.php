<?php
//Cross-origin enabler
  header("Access-Control-Allow-Origin: *");

//Session - comments explain purpose of this API
/*
 * This could be a beginning of something beautiful...
 */
  session_start(); 

//Database - s(t)imulate blockchain challenges - collective memory metaphor of conscious experience, reflected in blockchain
  /*
    Bridging understanding of personal, private and common - data in networks of nodes intertwines with conscious experiences
      Neural networks of nodes and people are more aware when interacting to collaboratively shape a more complete picture
      A majority consensus on validity of data is needed, enabled by...
        -a process of collecting, storing and processing data based on trust, enabled by [{"trust": {"honest nodes", ...}
        -identity is (to be) be reflected in network of nodes, providing authentication ~ as in real life
        -validity of data is based on a mathematical proof that nodes agree on
        -adoption of a process is social proof, sprouting from a need
  */
  $db = dbWrapper(mysqli_connect("localhost", "ownprodu_lepa", "openMinded1", "ownprodu_fairshift")) or die(mysqli_error());
  mysqli_set_charset ( $db , "utf8" );

/*
  Gestures might be perceived by one or more people, done and received by [{"entity": {"person", "community", ...}}]
  Gestures go around a sphere, leaving behind traces of #[{"cause &&|| effect": {"joy", "struggle"...}}]
  Gestures can be reflected and appreciated by those who have experienced something in them.
  For many reasons, pleasures as well as struggles, for something [{"why": {"meaningful", "of value", ...}}]
*/

//Functions
  include("auth.php"); //session, authentication, sign in/up to service
  include("oauth.php"); //links to social media & other services
  include("lang.php"); //language & translation functions
  include("safety.php"); //keep interactions with DB safe
  include("user.php"); //user passport object
  include("cron.php"); //DB just in time maintenance
  include("mailer/form-handler.php"); //email loop

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
  if(!isset($_GET['f'])){
    $response['user'] = $user;
    if($response['email_confirmation_time'] > 0 || $response['facebook_user_id'] > 0 || $response['twitter_user_id']){
      $response['status'] = 'welcome'; //initiated a boarding process to building identity
    }
  } else {
    $user_id = $user['id']; //anonymous identity
  }

//Functions
  /*
   * This API will allow for uses of the same service
   *  Spheres will nest sites, which may use the API
   *  Spheres through which users with a browser extension can crawl
   */
  switch($_GET['f']){

  //Sphere is a social membrane, real life circles interacting with technology, socially difusing redefining elements into database structure
    /* 
     * Examples challenge current order of API/DB process/structure
     *
       Examplary public initiative to measure changes in inspiration - getting a telepathic echo of...
          dis/entanglement to representive post on Facebook timeline with public / private / encryption of meaning with transformation
          posts on Facebook as a medium to spread gestures of positive change (encryption with socially dispersed keys... who saw, remembered, commited energy, disseminated, dispersed?)
       [{"gesture": {"letting go": {"timeline": "facebook.com/blablaz",
                                                 "giveaway": {"trickaweek.com", "antwalk.com", "timesaber.com"}},
                     "co-creating change": {"facebook.com/fairshift",
                                            "#lepo"}
       }]
     *
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
     *
     * Viral DNA
        1. think of a change you're making in your life and memories entangled with friends on Facebook
        2. change your Facebook password, split it into a # of pieces to meaningfully reflect your transformation
        3. check your timeline to see if any of the 9 friends there understand you in that manner
        4. permanently delete a post, reflecting the part of your identity you're about to change
        5. message your friends a piece of your password and ask them to codify their identity as yours [{"the keys to your identity", "..."}]
        4. set your profile picture and enrich it socially as on this example -> facebook.com/blablaz
        5. comment your profile picture with a wild dream stemming from this change
     *
     */

    case 'sphere':
      break;

  //Plan horizon - looking ahead
    //Intentions initiated, enacted in gestures - a fine blend of giving and receiving (offering, looking for)
        //# as key to codification of keywords social media, to fetch letters into blogchain rainbow spiral [{"topic": "impact of losing keys to data on future causes"}]
          //appeal / petition to FB post - request removal API feature a 
    case 'gesture':
      break;

  //Event horizon
    //initiated intentions
    case 'portal':
      break;

    //Blogchain of what happened as value holder and semi-transparent layer ["links": {"point of interest", ""}]
    //Meteors
    /*
      A rainbow of flowing values as pilars for decisions. [Ask for color coding values]
      With life and learning the unknown changes happen
      Crucial questions are answered in time - as people receive 
    */
    case 'reflection':
      break;

  //SMART KISS
    case 'project':
      break;


  //Site language
    case 'siteText':
      $response = siteText($db, $user_id);
      break;

    case 'languages':
      $response = siteText($db, $user_id);
      break;

  //Register / signin
    case 'passport':
      $response = passThrough($db, $user_id);
      break;

    case 'loginFacebook':
      $response = loginFacebook($db, $user_id);
      break;

    case 'loginTwitter':
      $response = loginTwitter($db, $user_id);
      break;

    case 'checkUsername':
      $response = usernameExists($db, $user_id);
      break;

    case 'confirm':
      $response = confirmEmail($db, $user_id);
      break;

    case 'resendConfirmation':
      $response = resendConfirmation($db, $user_id);
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

  //Database changed - what changed with (which set of data?) - what changed with you?
    case 'changed':
      $response = changed($db);
      break;
      //

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
    //header( "Connection: Close" );
  }

//Cron job check
cron($db);
?>