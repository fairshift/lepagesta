<?php

/*
Flow:
-user clicks login with and is transferred to OAuth confirmation
-user is transferred back to site
*/

function loginFacebook(){

  $db = $GLOBALS['db'];
  $route['social_login_user_id'] = $_SESSION['social_login_user_id'];
  $user_id = $route['social_login_user_id'];

  transaction(__FUNCTION__, $route);

  require '../vendor/Facebook/facebook.php';

  // Create our Application instance (replace this with your appId and secret).
  $facebook = new Facebook(array(
    'appId'  => '1726534820894448',
    'secret' => '19393a3f88a1681ba94cc631a8c0ffc3',
  ));

  // Get User ID
  $user = $facebook->getUser();

  // We may or may not have this data based on whether the user is logged in.
  // If we have a $user id here, it means we know the user is logged into
  // Facebook, but we don't know if the access token is valid. An access
  // token is invalid if the user logged out of Facebook.
  if ($user) {
    try {

      // Proceed knowing you have a logged in user who's authenticated.
      $user_profile = $facebook->api('/me');

    } catch (FacebookApiException $e) {

      error_log($e);
      $user = null;
    }
  }

  /*echo $user;
  print_r($user_profile);*/

  // Login or logout url will be needed depending on current user state.
  if ($user) {

    $logoutUrl = $facebook->getLogoutUrl();
    $auth = md5("LOL%I=ISUP".microtime());
    $user = getUser('route' => array('id' => $user['id'], 'selector' => 'facebook_user_id', 'dataset' => array('me')))['state'];

    if(!$user){

      if($GLOBALS['site']['id']){
        $sql_site_field = ', auth_site_id';
        $sql_site_value = ", '{$GLOBALS['site']['id']}'";
      }

      $sql = "INSERT INTO user (facebook_user_id, username, time_registered, last_visit, auth {$sql_site_field}) VALUES (".
                  "'".$user_profile['id']."', ".
                  "'".$user_profile['name']."', ".
                  "'".time()."',".
                  "'".time()."',".
                  "'".$auth."'".
                  "{$sql_site_value});";

      mysqli_query($db, $sql);
      $user = getUser($db, $user_profile->id, 'facebook_user_id', array('me'));

    } else {

        if($GLOBALS['site']['id']){
          $sql_site = ", auth_site_id = '{$GLOBALS['site']['id']}'";
        }

        mysqli_query($db, "UPDATE user SET last_visit = '".time()."', auth = '".$auth."' {$sql_site} WHERE facebook_user_id = '".$user_profile['id']."'");
        $user['last_visit'] = time();
        $user['auth'] = $auth;
    }

    /*if (isset($_POST['publish_fb'])){

          try {
              $publishStream = $facebook->api("/$user/feed", 'post', array(

                  'message' => $_POST['publish_fb'],
                  'link'    => 'https://www.kickstarter.com/projects/323324338/zenegg-create-time-for-yourself',
                  'picture' => 'http://zenegg.si/img/zenegg_totem.jpg',
                  'name'    => 'Zen Egg • Create Time for Yourself',
                  'description'=> 'Balancing wooden totem, elegantly crafted to help you de-stress and reconnect with yourself.'
                  )

              );
              //as $_GET['publish'] is set so remove it by redirecting user to the base url

          } catch (FacebookApiException $e) {

              d($e);
          }
      }*/

  } else {

    $fb_login_url = $facebook->getLoginUrl(array('scope' => 'email', 'redirect_uri' => 'http://fairshift.org/api/main.php')); //publish_actions, read_stream
    $_SESSION['social_login_user_id'] = $user_id;
  }

  if(isset($fb_login_url)){

      $row['status_code'] == '200';
      $row['login_url'] = $fb_login_url;
      //header("Location: ".$row['login_url']);

  } else {

      if(isset($user_profile)){

          $row['status_code'] = '200';
          $row['user'] = $user;

          if(!$_REQUEST['f'] == 'loginFacebook'){

              redirect("http://fairshift.org?auth=".$auth);

          }
          //header("Location: http://fairshift.org/?auth=".$auth);

      } else {

          $row['status_code'] = false;
          //header("Location: http://fairshift.org/");
      }
  }

  transaction(array('function' => __FUNCTION__));

  return $row;
}

//Get email address: http://stackoverflow.com/questions/3599621/is-there-a-way-to-get-an-users-email-id-after-verifying-his-her-twitter-identit
function loginTwitter(){

  $db = $GLOBALS['db'];
  $route['social_login_user_id'] = $_SESSION['social_login_user_id'];
  $user_id = $route['social_login_user_id'];

  transaction(array('function' => __FUNCTION__, 'route' => $route));

  require("../vendor/Twitter/twitteroauth.php");

  if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])){

      // TwitterOAuth instance, with two new parameters we got in twitter_login.php
      $twitteroauth = new TwitterOAuth('iFvufysWxTVK1WB9hm9N9jjNU', '2eDJgHfjDnVAwEnJF7uJnxo1VsCmx7M0gyv8PeaX1LB78hedLw', $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

      // Let's request the access token
      $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);

      // Save it in a session var
      //$_SESSION['access_token'] = $access_token;

      // Let's get the user's info
      $user_info = $twitteroauth->get('account/verify_credentials');

      // Print user's info
      /*if(isset($_POST['publish_tw'])){
          $twitteroauth->post('statuses/update', array('status' => $_POST['publish_tw']));
      }*/

      if(isset($user_info)){

          $auth = md5("LOL%I=ISUP".microtime());
          $user = getUser($db, $user_info->id, 'twitter_user_id', array('me'));

          if(!$user){

            $sql = "INSERT INTO user (twitter_user_id, username, time_registered, last_visit, auth, auth_site_id) VALUES (".
                      "'".$user_info->id."', ".
                      "'".$user_info->name."', ".
                      "'".time()."',".
                      "'".time()."',".
                      "'".$auth."',".
                      "'".$GLOBALS['site']['id']."');";

            mysqli_query($db, $sql);
            //$user = getUser($db, $user_info->id, 'twitter_user_id', array('me'));
            transaction(array('function' => __FUNCTION__));

          } else {

            mysqli_query($db, "UPDATE user SET last_visit = '".time()."', auth = '".$auth."' WHERE twitter_user_id = '".$user_info->id."'");
            transaction(array('function' => __FUNCTION__));
          }

          newBlock();

          redirect("http://fairshift.org?auth=".$auth);

      } else {

          $row['status_code'] = false;
      }

  } else {

      // The TwitterOAuth instance
      $twitteroauth = new TwitterOAuth('iFvufysWxTVK1WB9hm9N9jjNU', '2eDJgHfjDnVAwEnJF7uJnxo1VsCmx7M0gyv8PeaX1LB78hedLw');

      // Requesting authentication tokens, the parameter is the URL we will be redirected to
      $request_token = $twitteroauth->getRequestToken('http://fairshift.org/api/main.php');

      // Saving them into the session
      $_SESSION['oauth_token'] = $request_token['oauth_token'];
      $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
      $_SESSION['social_login_user_id'] = $user_id;

      // If everything goes well..
      if($twitteroauth->http_code==200){

          // Let's generate the URL and redirect
          $twitter_login_url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
          $row['login_url'] = $twitter_login_url;
          //header("Location: ".$row['login_url']);

      } else {

          $twitteroauth->http_code;
      }

      $row['status_code'] = $twitteroauth->http_code;
  }

  transaction(array('function' => __FUNCTION__));

  return $row;
}

//Have to make this one work, still
/*
https://developers.google.com/identity/protocols/OpenIDConnect#server-flow
https://github.com/google/google-api-php-client/tree/v1-master
http://phppot.com/php/php-google-oauth-login/

Error log:
    Fatal error: Uncaught exception 'Google_Service_Exception' with message 'Error calling GET https://www.googleapis.com/oauth2/v2/userinfo?key=AIzaSyAzHB4KqT61iivsLlWPwyBO4Ww2ThUQw9g: (401) Invalid Credentials' in /home/ownprodu/public_html/fairshift.org/api/Google/Http/REST.php:110 Stack trace: #0 /home/ownprodu/public_html/fairshift.org/api/Google/Http/REST.php(62): Google_Http_REST::decodeHttpResponse(Object(Google_Http_Request), Object(Google_Client)) #1 [internal function]: Google_Http_REST::doExecute(Object(Google_Client), Object(Google_Http_Request)) #2 /home/ownprodu/public_html/fairshift.org/api/Google/Task/Runner.php(174): call_user_func_array(Array, Array) #3 /home/ownprodu/public_html/fairshift.org/api/Google/Http/REST.php(46): Google_Task_Runner->run() #4 /home/ownprodu/public_html/fairshift.org/api/Google/Client.php(593): Google_Http_REST::execute(Object(Google_Client), Object(Google_Http_Request)) #5 /home/ownprodu/public_html/fairshift.org/api/Google/Service/Resource.php(240): Google_Client->execute(Object(G in /home/ownprodu/public_html/fairshift.org/api/Google/Http/REST.php on line 110
*/

function loginGoogle(){

//Google API PHP Library includes
    require_once 'Google/autoload.php';

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
    $client_id = '611257152422-fqh098h0cajcoj8l6uqt706lu5r6qbii.apps.googleusercontent.com';
    $client_secret = 'wf98K9nW1ZsKZSrpZpvjCFbG';
    $redirect_uri = 'http://fairshift.org/api/oauth.php';
    $simple_api_key = 'AIzaSyAzHB4KqT61iivsLlWPwyBO4Ww2ThUQw9g';

//Create Client Request to access Google API
    $client = new Google_Client();
    $client->setApplicationName("Fairshift");
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->setDeveloperKey($simple_api_key);
    $client->addScope("https://www.googleapis.com/auth/userinfo.email");

//Send Client Request
    $objOAuthService = new Google_Service_Oauth2($client);

//Logout
    if (isset($_REQUEST['logout'])) {

      unset($_SESSION['access_token']);
      $client->revokeToken();
      header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
    }

//Authenticate code from Google OAuth Flow

//Add Access Token to Session
    if (isset($_GET['code'])) {

      $client->authenticate($_GET['code']);
      $_SESSION['access_token'] = $client->getAccessToken();
      header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }

//Set Access Token to make Request
    if(isset($_SESSION['access_token']) && $_SESSION['access_token']) {

      $client->setAccessToken($_SESSION['access_token']);
    }

//Get User Data from Google Plus
//If New, Insert to Database
    if ($client->getAccessToken()) {

      $userData = $objOAuthService->userinfo->get();

      if(!empty($userData)) {

        $row['status_code'] == '200';
        $row['user'] = $userData;
      }
      //$_SESSION['access_token'] = $client->getAccessToken();

    } else {

      $authUrl = $client->createAuthUrl();
      $row['status_code'] == '200';
      $row['login_url'] = $authUrl;
      header("Location: ".$authUrl);
    }

    return $row;
}

/*if(isset($_GET['google']) || (isset($_GET['code']) && !isset($_GET['state']))){
    $google = loginGoogle();
}
$facebook = loginFacebook();
?>

<html>
<body>

<?php

if($facebook['user']){
    echo "Facebook:<br/>\r\n".print_r($facebook['user'])."\r\n<br/><br/>";
}
if($twitter['user']){
    echo "Twitter:<br/>\r\n".print_r($twitter['user'])."\r\n<br/><br/>";
}
if($google['user']){
    echo "Google:<br/>\r\n".print_r($google['user'])."\r\n<br/><br/>";
}

?>
Or sign in with<br/>
<a href="oauth.php?facebook">Facebook</a><br/>
<a href="oauth.php?twitter">Twitter</a><br/>
<a href="oauth.php?google">Google (!)</a>
</body>
</html>
*/

function redirect($url){
    ?>
    <html>
    <body>
        <script type="text/javascript">
        window.location.href = '<?php echo $url; ?>';
        </script>
    </body>
    </html>
    <?php
    die();
}
?>