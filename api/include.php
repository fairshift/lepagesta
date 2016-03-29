<?php
	function includeFunctions($name){
		if(isset($_REQUEST['o']) && file_exists("/customized/{$_REQUEST['o']}/{$name}")){
			include("customized/{$_REQUEST['o']}/{$name}");
		} else {
			include("functions/{$name}");
		}
	}
?>