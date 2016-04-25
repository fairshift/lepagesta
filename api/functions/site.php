<?php

function getSite(){

	$db = $GLOBALS['db'];

	$route = $input['route'];

    transaction(__FUNCTION__, $route);

	if($route['domain']){

		$sql = "SELECT *, site.id AS site_id FROM site WHERE domain = '{$route['domain']}'";
	    $result = mysqli_query($db, $sql);
	    if($buffer['state'] = mysqli_fetch_array($result, MYSQLI_ASSOC)){

	    	if($buffer = getContent('route' => array('table_name' => 'site', 'entry_id' => $row['site_id']), 'block' => $buffer)){
	    		$block = $buffer;
	    	}

	    	$response = $block;
	    }
	}
    
    transaction(array('function' => __FUNCTION__));

	return $response;
}

?>