<?php
/*
* This centralized database is a metaphor for it's decentralized counterparts. It could be a beginning of a beautiful transformation...
*/

  header('Access-Control-Allow-Origin: *'); //Cross-origin enabler, a bridge of data to a multiverse of online services, onwards into the world of the living
  //error_reporting(0); @ini_set('display_errors', 0);
  error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

/*
  With blockchain, any changes to data states are stored in the distributed database
  This API aims to emulate some core features a decentralized network of nodes running blockchain 

    * creating a data pool accessible accross many services the user chooses to share it with (this database isn't distributed, though)
    * maintaining proof for validity of data

  Doing so we keep in mind necessities of the blockchain paradigm shift, enabling a smoother passage onwards (trust in central authority's proper management is needed until processed data is signed by users as well as functions/objects dealing with it - without the possibility of tampering with internal states)

  Networks of brain and computer nodes are more aware when interacting to collaboratively shape a more complete, data driven picture
  A majority consensus on validity of data is needed for this to kick in, enabled by...

    * a process of collecting, storing and processing data that enjoys trust at both both ends, social and technological
    * trust in validity of data is based on a mathematical proof that is socially accepted (merkletree)
    * on the social end trust is enabled by a critical mass of ethical peers, beholding personal traits such as [{"value": {"honesty", "integrity", "responsibility"...}}]
    * based on a need from which changes sprout, adoption of a process is social proof of trust

  As such, this API aims to spark imagination, supporting transition of collective memories from conscious experiences into data blocks
*/

  session_start();
  include('local/config.php');
  include('includer.php'); //script, which allows for customizations to API (in customized/$domain/ folder)

  dbWrapper($account); //$GLOBALS['db'] stores database object
  unset($account);

/*
  Ethereum(.org) decentralized platform charges computing power to the user (in amount of Ether as currency), providing incentive for script efficiency. Here, ...

    * Interactions with this API are logged, tracking changes to data states, enabling data validation, access control and measuring script efficiency
    * Such pattern can be imagined to facilitate validation of encrypted data (requires use of private-public key pairs by users) - eg.: http://enigma.media.mit.edu/
*/
  $transaction = transaction(array('function' => 'main.php', 'route' => $_REQUEST));

  //Load languages
  //$GLOBALS['language_id'] = (input('lang', 'string', 1, 10)) ? $GLOBALS['languages'][input('lang', 'string', 1, 10)]['id'] : exit; //language of current query
  $GLOBALS['default_language_id'] = 20; //default language is English
  $GLOBALS['node_languages'][] = $GLOBALS['default_language_id'];
  //$GLOBALS['node_languages'] = arrayAddDistinct($, $GLOBALS['node_languages']); //user's spoken languages
  $response['languages'] = getLanguageList(array('route' => array('languages' => $GLOBALS['node_languages'])));

//Social media based authentication
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
//User authentication & basic necessary data
  $GLOBALS['profile'] = authenticate(array('route' => array( 'auth' => input( 'auth', 'md5', 32, 32 ), 'dataset' => array('user_language') )));

//Site-specific functions - validate domain / site_id, passed from frontend
  $GLOBALS['site'] = getSite(array('route' => array( 'domain' => input('o', 'string', 1, 64), //passed when user first loads a site
                                                     'site_id' => input('s', 'integer', 1, 11)) )); //passed on all next loads site_id

//Is user acting on behalf of an entity? ˇ requires permission_represent, permission_manage within a circle
  //$GLOBALS['entity'] = getEntity(array('entity_id' => input('entity_id', 'integer', 1, 11)));

/* 
  with Ethereum's blockchain, user accounts are hashes that already exist
*/

//Call to API 
  if(strpos($_REQUEST['call'], '/') > 0){
    $buffer = explode('/', $_REQUEST['call']);
    $GLOBALS['o'] = $buffer[0]; //object
    $GLOBALS['f'] = $buffer[1]; //function
  } else {
    $GLOBALS['o'] = $_REQUEST['call']; //object
  }

  $response = router(); //route call: object/function   <---   data intake

  $response['profile'] = $GLOBALS['profile'];
  $response['site'] = $GLOBALS['site'];

//JSON response
  $response['nodes'] = $GLOBALS['nodes']; //TO-DO don't send out what has already been sent ($GLOBALS['sent-nodes'] array)
  if(isset($response)){
    echo json_encode($response);
  }

//Maintenance functions after connection to the frontend has been closed
  header( "Connection: Close" );

//Update modified tables with default language translation
/* if(isset($GLOBALS['translation_queue'])){
    foreach($GLOBALS['translation_queue'] AS $row){
      translateToDefault($row);
    }
}*/

//Cron job check
  //cron($db);

//Save transactions
  transaction(array('transaction' => $transaction)); //close 'main.php'
  toBlockchain();
?>