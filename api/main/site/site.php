<?php

function getSite(){
echo "Lol";
	$db = $GLOBALS['db'];

    $input = func_get_args()[0];
	$route = $input['route'];

    $transaction = transaction(array('function' => __FUNCTION__, 'route' => $route));

	if($route['domain']){

		$where['domain'] = $route['domain'];
    	$query = getNode(array('route' => array('table' => 'site', 'where' => $where)));

        print_r($GLOBALS['nodes']);
	}

    if($route['site_id']){

        $query = getNode(array('route' => array('table' => 'site', 'id' => $route['site_id'])));
    }
    
    if($query){
        $GLOBALS['site']['id'] = $query['response']['id'];
        $GLOBALS['site']['domain'] = $GLOBALS['nodes']['site'][$query['response']['id']]['domain'];
    }

    transaction(array('transaction' => $transaction));

	return $query;
}

?>