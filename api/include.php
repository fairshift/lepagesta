<?php
	function includeFunctions($name){
		if(input('o', 'url', 1, 64) && file_exists("/customized/{$_REQUEST['o']}/{$name}")){
			include("customized/{$_REQUEST['o']}/{$name}");
		} else {
			include("functions/{$name}");
		}
	}
?>