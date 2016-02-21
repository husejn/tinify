<?php
$error_array = array();
$successfully_logged_in = false;

require_once($config['db_connect']);

function addError($string){
	global $error_array;
	$error_array[] = $string;
}
function checkCredentials($username, $password){
	global $mysqli, $successfully_logged_in;
	$hashed_password;
	$query = $mysqli->query("SELECT `user_id`,`password` FROM `users` WHERE `username` = '$username' AND `is_active` = 1 ") or addError("Unknown error.");
	if($query->num_rows == 0){
		addError("Username or password is incorrect");
		return 0;
	} 
	while ($r = $query->fetch_assoc()) {
		$hashed_password = $r['password'];
		$user_id = $r['user_id'];
	}
	$salt = explode("-", $hashed_password)[1];
	$hashed_password = explode("-", $hashed_password)[0];

	if(hash("sha256", $password . $salt) == $hashed_password){
		$successfully_logged_in = true;
		return $user_id;
	} else{
		addError("Username or password is incorrect");
		return 0;
	}
}
function printApi($session_id, $error_array, $successfully_logged_in){
	$login_response = array("Session" => $session_id, "Errors" => $error_array, "Success" => $successfully_logged_in);
	header('Content-Type: application/json');
	echo json_encode($login_response, JSON_PRETTY_PRINT);
}
if(!isset($_POST['username']) || empty($_POST['username'])|| !isset($_POST['password']) || empty($_POST['password'])) {
	addError("Please enter both your username and your password.");
}


$username = $_POST['username'];
$password = $_POST['password'];

$username = trim($username);
$password = trim($password);

$username = $mysqli->real_escape_string($username);
$password = $mysqli->real_escape_string($password); 

//all clear
if(count($error_array) == 0){
	require_once($config['session']);
	$user_id = checkCredentials($username, $password);

	if($user_id != 0){
		$s = createSession($user_id);
		$session_id = $s[0];
		$expire = strtotime($s[1]);

if(!defined("WEB")){ //API
	printApi($session_id, $error_array, $successfully_logged_in);
}
else{
	$long_url = preg_replace("(^https?://)", "", REDIRECT_DOMAIN_NAME );

	setcookie("SSID", $session_id, $expire, "/", "$long_url");
	header('Location: '. FULL_DOMAIN_NAME);
}
} 
else {
	$login_response = array("Errors" => $error_array, "Success" => $successfully_logged_in);
if(!defined("WEB")){ //API
	printApi($session_id, $error_array, $successfully_logged_in);
}
}
} 
else{ //login failed
	$login_response = array("Errors" => $error_array, "Success" => $successfully_logged_in);
if(!defined("WEB")){ //API
	printApi($session_id, $error_array, $successfully_logged_in);
}
}






?>