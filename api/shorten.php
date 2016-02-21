<?php

require_once('../config.php');


header("Content-Type: application/json");

$error_array = array();
$successfully_shortened = false;
$session_id;
$url;
$short_url;
define("WEB", true);	

require_once($config['db_connect']);
require_once($config['session']);

$user = null;
if(isset($_COOKIE['SSID'])){
	$session_id = $_COOKIE['SSID'];
	$user_id = authenticate($session_id);
	if(authenticate($session_id) != 0){
		extendSession($session_id);
		$user = userData($user_id);
	}
}
/** From http://stackoverflow.com/questions/4357668/how-do-i-remove-http-https-and-slash-from-user-input-in-php
*/
function remove_http($url) {
	$disallowed = array('http://', 'https://');
	foreach($disallowed as $d) {
		if(strpos($url, $d) === 0) {
			return str_replace($d, '', $url);
		}
	}
	return $url;
}
function addError($string){
	global $error_array;
	$error_array[] = $string;
}
function printError(){
	global $error_array;
	echo json_encode(array("Error" => $error_array), JSON_PRETTY_PRINT);
	die();
}
function addProtocol($url){
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		$url = "http://" . $url;
	}
	return $url;
}
function addScheme($url, $scheme = 'http://'){
	return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
}
function validateUrl($url){
	if(!filter_var($url, FILTER_VALIDATE_URL)){
		addError("URL is not valid.");
		printError();
	}
	if(strlen($url) >= 2000){
		addError("URL too long.");
		printError();
	}
	$sd = remove_http(FULL_DOMAIN_NAME);
	if( strpos(parse_url($url, PHP_URL_HOST), $sd) !== false )
	{
		addError("Already short links are not allowed.");
		printError();
	}
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url']) && !empty($_POST['url'])) {
	require_once($config['short']);
	$url = trim($_POST['url']);
	$url = addProtocol($url);
	$url = $mysqli->real_escape_string($url);
	validateUrl($url);
	if($user === null){
		$short_url = addLinkToDB($url, 1);
	}
	else{
		$short_url = addLinkToDB($url, $user_id);
	}

	if(isset($_POST['add_cookie'])){
		if(empty($_COOKIE['shortlinks'])){
			$cookie = $short_url;
		}
		else{
			$cookie = $_COOKIE['shortlinks'] . "," . $short_url;
		}
		$sd = remove_http($_SERVER['SERVER_NAME']);
		
		setcookie("shortlinks", $cookie, time()+(365*24*60*60), "/", "$sd");
	}

	$success_response = array("hash"=>$short_url, "url"=>$url);
	echo json_encode(array("Success" => $success_response), JSON_PRETTY_PRINT);

}
else{
	addError("Bad Request.");
	printError();
}

?>