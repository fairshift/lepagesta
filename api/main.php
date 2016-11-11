<?php
//Todo:
/*
$GLOBALS['nodes_memory'] array
$GLOBALS['nodes_cache'] array
        onReady
            &call=nodes/blankslate (GET)
                - frontend requests to purge any nodes related to current session's auth key

        During flow
            &call=nodes/enstate (POST list of nodes)
                - frontend requests to purge any nodes related to current session's auth key, except for submitted list of nodes

            get (&call=object or &call=object/function & params) (GET, or POST list of nodes) 
                - frontend requests data and submits list of nodes it has cached

            post (&call=object or &call=object/function & params) (POST form data) 
                -

            &call=nodes/sync (&sync=object or &sync=object/function & params) (POST list of nodes) 
                - frontend synchronized with backend for any changes to observed data at a regular interval (default or subscription specific) or with any calls

            &call=nodes/drop (&drop=object or &drop=object/function & params) (POST list of nodes)
                - frontend notifies backend which data nodes it dropped from cache*/

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
    * based on a need from which changes sprout, adoption of a process is social proof of it's validity

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
  $transaction = transaction(array('api_call' => $_GET['calls']));

//Load languages
  //$GLOBALS['language_id'] = (input('lang', 'string', 1, 10)) ? $GLOBALS['languages'][input('lang', 'string', 1, 10)]['id'] : exit; //language of current query
  $GLOBALS['default_language_id'] = 20; //default language is English
  $GLOBALS['node_languages'][] = $GLOBALS['default_language_id'];
  //$GLOBALS['node_languages'] = arrayAddDistinct($, $GLOBALS['node_languages']); //user's spoken languages
  $response['languages'] = getLanguageList(array('route' => array('languages' => $GLOBALS['node_languages'])));

//Process call(s) to API - multiple possible
  $calls = explode($_GET['calls'][',']);

  $priorities[] = 'user/auth';
  $priorities[] = 'user/languages';
  $priorities[] = 'site/domain'; //when user first loads a site
  $priorities[] = 'site/id'; //on all next loads site_id
  foreach($priorities AS $call){
    if(in_array($call, $calls)){
      $response[$call] = router($call);
      unset($calls[$call]);
    }
  }

  foreach($calls AS $call){
    $response[$call] = router($call); //route call: object/function <-- $_REQUEST[$call] stores input parameters
  }

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
  storeTransactions();
?>