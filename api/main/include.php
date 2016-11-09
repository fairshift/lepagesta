<?php
//Load database functions
  includer('router');
  
  includer('database/dbwrapper'); //database session
  includer('database/helper'); //database session

  includer('database/safety'); //functions to keep interactions with API/DB safe
  includer('blockchain/block'); //emulation of a few blockchain's native functionalities (storing calls and data state changes as transactions)
    includer('database/cache'); //caching data states
    //includer('merkletree'); //data validation algorithm (currently not in use)

    includer('user/oauth'); //social media & other services integrations
    includer('user/auth'); //session, authentication, sign in/up to service
    includer('user/user'); //
    includer('user/entity'); //
    includer('user/transaction'); //emulation of blockchain to store calls and data state changes as transactions

    includer('site/site');
    includer('site/lang'); //language & translation functions
    includer('site/cron'); //DB just in time maintenance and other timely arrants

    includer('mailer/form-handler'); //email loop - inviting, confirming emails, notifying

    includer('content/nodeGet');
    //includer('content/nodeUpdate');

      //includer('content/circle'); //circle is common grounds, encircling people and content around common purposes, storylines and rules of engagement
      //includer('content/privilege'); //rules of engagement with content
      //includer('content/place'); //place on a map
      //includer('content/portal'); //a social gathering manages a portal
      //includer('content/reflection');
      //includer('content/value');
      //includer('content/keyword');
?>