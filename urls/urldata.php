<?php

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/config.php');

require_once($config['db_connect']);
require_once($config['session']);
$user = null;
$user_id = 1;
if(isset($_COOKIE['SSID'])){
	$session_id = $_COOKIE['SSID'];
	$user_id = authenticate($session_id);
	if(authenticate($session_id) != 0){
		extendSession($session_id);
		$user = userData($user_id);
		$user_id = $user -> user_id;
	}
}

if(!isset($url_id)){
	$url_id = $_POST['url'];
}
function readUrlDataFromDB(){
	global $user_id;
	global $url_id;
	global $mysqli;
	$url_id = $mysqli->real_escape_string($url_id);	
	$sql = "SELECT `short_id`, `short_code`, `short`.`user_id` as `user_id`, `users`.`username` as `username`, `time_created`, `url_to` 
		FROM `users`, `short` 
		WHERE `short`.`user_id` = `users`.`user_id` AND `short_code` = '$url_id'";

	$arr = array();
	$query = $mysqli->query($sql);
	while ($r = $query->fetch_assoc()) {
		$arr['short_id'] =  $r['short_id'];
		$arr['short_code'] =  $r['short_code'];
		$arr['user_id'] =  $r['user_id'];
		$arr['username'] =  $r['username'];
		$phpdate = strtotime( $r['time_created'] );
		$mysqldate = date( 'F j, Y', $phpdate );
		$arr['time_created'] =  $mysqldate;
		$arr['url_to'] =  $r['url_to'];
	}
	return $arr;
}
require_once($config['statistics']);



$url_data = readUrlDataFromDB();

if(defined("WEB")){
	
}
else{
	header('Content-Type: application/json');
	echo json_encode(array("urldata" => $url_data, "countries" => $countrylist), JSON_PRETTY_PRINT);
}




?>