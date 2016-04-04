<?php

function getSite($db, $url){
	if($GLOBALS['user']['id']){
		$sql = "SELECT *, site.id AS site_id FROM site WHERE url = '$url' AND removed = 0";
	    $result = mysqli_query($db, $sql);
	    if($response = mysqli_fetch_array($result, MYSQLI_ASSOC)){

	    	if($content = getContent($db, 'site', $row['site_id'])){
	    		$response = array_merge($response, $content);
	    	}

	    	return $response;
	    } else {
	    	return false;
	    }
	}
}

?>