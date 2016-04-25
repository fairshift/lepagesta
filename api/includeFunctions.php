<?php
	//Customizations to API?
	  function includeFunctions($name){
		/*if(isset($_REQUEST['o']) && file_exists("/customized/{$_REQUEST['o']}/{$name}")){
			include("customized/{$_REQUEST['o']}/{$name}");
		} else {*/
			include("functions/{$name}");
		//}
	  }

	//Load functions
	  includeFunctions('dbwrapper.php'); //content add/update/get
	  includeFunctions('safety.php'); //keep interactions with API/DB safe

	  includeFunctions('block.php'); //simulation of blockchain concept
	  //includeFunctions('merkletree.php'); //data validation algorithm (currently not in use)
	  includeFunctions('cache.php') //caching data states

	  includeFunctions("auth.php"); //session, authentication, sign in/up to service
	  includeFunctions("oauth.php"); //social media & other services integrations

	  includeFunctions("site.php"); //site specific data pool functions
	  includeFunctions("lang.php"); //language & translation functions
	  includeFunctions("user.php"); //user passport functions
	  includeFunctions("cron.php"); //DB just in time maintenance and other timely arrants
	  includeFunctions("mailer/form-handler.php"); //email loop - inviting, confirming emails, notifying

	  includeFunctions("circle.php"); //circle is common grounds, encircling people and content, and as such purposes, storylines and rules of engagement
	  includeFunctions("privilege.php"); //rules of engagement coded

	  includeFunctions("place.php"); //place on a map
	  includeFunctions("portal.php"); //a social gathering manages a portal

	  includeFunctions("reflection.php");
	  includeFunctions("value.php");
	  includeFUnctions("keyword.php");

	  includeFunctions("sphere.php"); //sphere is an extra dimension to circles, a wormhole to something undefined as of yet
?>