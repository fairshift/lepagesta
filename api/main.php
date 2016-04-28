<?php
/*
* This centralized database is a metaphor for it's decentralized counterparts. It could be a beginning of a beautiful transformation...
*/

  header('Access-Control-Allow-Origin: *'); //Cross-origin enabler, a bridge of data to a multiverse of online services, onwards into the world of the living
  //error_reporting(0); @ini_set('display_errors', 0);

/*
  With blockchain, any changes to data states are stored in the distributed database
  This API aims to emulate some core features a decentralized network of nodes running blockchain 

    * creating a data pool accessible accross many services the user chooses to share it with (this database isn't distributed, though)
    * maintaining proof for validity of data

  Doing so we keep in mind necessities of the blockchain paradigm shift, enabling a smoother passage onwards (trust in central authority's proper management is needed until processed data is signed by users as well as functions/objects dealing with it - without the possibility of tampering with internal states)

  Networks of brains and computer nodes are more aware when interacting to collaboratively shape a more complete, data driven picture
  A majority consensus on validity of data is needed for this to kick in, enabled by...

    * a process of collecting, storing and processing data that enjoys trust at both both ends, social and technological
    * trust in validity of data is based on a mathematical proof that is socially accepted (merkletree)
    * on the social end trust is enabled by a critical mass of ethical peers, beholding personal traits such as [{"value": {"honesty", "integrity", "responsibility"...}}]
    * based on a need from which changes sprout, adoption of a process is social proof of trust

  As such, this API aims to spark imagination, supporting transition of collective memories from conscious experiences into data blocks
*/

  session_start();
  include('includer.php'); //script, which allows for customizations to API (in customized/$domain/ folder)
  dbWrapper(); //$GLOBALS['db'] stores database object

//Load database functions
  includer('database/dbwrapper.php'); //database session
  includer('database/safety.php'); //functions to keep interactions with API/DB safe

//Site specific data pool functions - validate domain / site_id, passed from frontend
  includer("site/site.php");
  $GLOBALS['site'] = getSite('route' => array('domain' => input('o', 'string', 1, 64), //passed when user first loads a site
                                              'site_id' => input('s', 'integer', 1, 11)))['state']; //passed on all next loads site_id

//Now when site domain is established, include the rest of functionalities (customized/$domain/ folder is now ready for use)
  includer('include.php');

/*
  Ethereum(.org) charges computing power to the user (in amount of Ether as currency), providing incentive for script efficiency

    * Interactions with this API are logged, tracking changes to data states, enabling data validation, access control and measuring script efficiency
    * Such pattern can be imagined to facilitate validation of encrypted data (requires use of private-public key pairs by users) - eg.: http://enigma.media.mit.edu/
*/
  $transaction = transaction(array('function' => 'main.php', 'route' => $_REQUEST));

/* 
  with Ethereum's blockchain, user accounts are hashes that already exist
*/
//User authentication & basic necessary data
  $GLOBALS['user'] = authenticate('route' => array('auth' => input( 'auth', 'md5', 32, 32 )))['state'];

//Is user acting on behalf of an entity? ˇ requires permission_represent, permission_manage
  $GLOBALS['entity'] = getEntity('entity_id' => input('entity_id', 'integer', 1, 11)); 

//Language
  $GLOBALS['languages'] = listLanguages($GLOBALS['user']['id']); //list all languages ('googletranslate' supported for a language? 0 or 1)
  $GLOBALS['default_language_id'] = $GLOBALS['languages']['en']['id']; //default language is English
  $GLOBALS['language_id'] = (input('lang', 'string', 1, 10)) ? $GLOBALS['languages'][input('lang', 'string', 1, 10)]['id'] : exit; //language of current query

//Call to API 
  if(strpos($_REQUEST['call'], '/') > 0){
    $buffer = explode('/', $_REQUEST['call']);
    $GLOBALS['o'] = $buffer[0]; //object
    $GLOBALS['f'] = $buffer[1]; //function
  } else {
    $GLOBALS['o'] = $_REQUEST['call']; //object
  }

//Router
  includer('router.php'); //route call: object/function   <---   data intake

//Safe user profile data response (how about new auth key everytime?)
  $response['user'] = safeProfileData($GLOBALS['user']); //$response['user']['status']; equals 'welcome' when user is confirmed (email, Facebook, Twitter)

//JSON response
  if(isset($response)){
    echo json_encode($response);
  }

//Maintenance functions after connection to the frontend has been closed
  header( "Connection: Close" );
  transaction(array('transaction' => $transaction));

//Update modified tables with default language translation
/* if(isset($GLOBALS['translation_queue'])){
    foreach($GLOBALS['translation_queue'] AS $row){
      translateToDefault($row);
    }
}*/

//Cron job check
  //cron($db);

//Save transactions
  newTransaction();
?>