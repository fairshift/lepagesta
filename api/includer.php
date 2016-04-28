<?php
	//Customizations to API?
	  function includer($name){
	  	$domain = $GLOBALS['site']['domain'];
		if($domain && file_exists("/custom/{$domain}/{$name}")){
			require_once("custom/{$domain}/{$name}");
		} else {
			require_once("main/{$name}");
		}
	  }
?>