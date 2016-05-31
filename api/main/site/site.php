<?php

function getSite(){

	$db = $GLOBALS['db'];

    $input = func_get_args()[0];
	$route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

	if($route['domain']){

		$where['domain'] = $route['domain'];
    	$query = getNode(array('route' => array('table' => 'site', 'where' => $where)));

        //print_r($query);
	}

    if($route['site_id']){

        $query = getNode(array('route' => array('table' => 'site', 'id' => $route['site_id'])));
    }

    transaction(array('transaction' => $transaction));

	return $query;
}

?>