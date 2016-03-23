<?php
function dbWrapper($sphere){
	include("local/config.php");
	$acco
	$db = mysqli_connect($account['host'], $account['database-user'], $account['database-password'], $account['database']) or die(mysqli_error());
 	mysqli_set_charset( $db , "utf8" );
 	return $db;
}}