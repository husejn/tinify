<?php


require_once('../config.php');

$error_array = array();
$successfully_registered = false;
$session_id;
class VerifySignUpData
{
	private static function basicChecker($value, $lengthMin, $lengthMax, $description, $isAlphaNum){
		if(empty($value))
			return addError($description . " cannot be empty.");

		if($isAlphaNum && !ctype_alnum($value))
			return addError($description . " is not valid.");

		if(strlen($value) < $lengthMin)
			return addError($description . " must be at least " . $lengthMin . " characters.");

		if(strlen($value) > $lengthMax)
			return addError($description . " must be shorter than " . $lengthMax . " characters.");
	}

	public static function verifyUsername($u){
		return self::basicChecker($u, 3, 64, "Username", true);
	}
	public static function verifyPassword($p){
		return self::basicChecker($p, 6, 32, "Password", false);
	}
	public static function verifyName($n){
		return self::basicChecker($n, 3, 64, "Name", false);
	}
	public static function verifyEmail($e){
		if(filter_var($e, FILTER_VALIDATE_EMAIL)) return true;
		addError("Email is not valid.");
	}

}
function generate_salt(){
	return uniqid(mt_rand(), true);
}
function addError($string){
	global $error_array;
	$error_array[] = $string;
}
function userNameAvailable($username, $mysqli){
	$checkUsernameSQL = "SELECT 1 FROM `users` WHERE `username` = '$username'";
	$checkUsernameQuery = $mysqli->query($checkUsernameSQL);
	if($checkUsernameQuery->num_rows != 0) 
		addError("This username already exists.");
}
function emailAvailable($email, $mysqli){
	$checkEmailSQL = "SELECT 1 FROM `users` WHERE `email` = '$email'";
	$checkEmailQuery = $mysqli->query($checkEmailSQL);
	if($checkEmailQuery->num_rows != 0) 
		addError("This e-mail is already registered.");
}
function passwordMatch($password, $repeat_password){
	if($password !== $repeat_password)
		addError("Passwords don't match");
}

require_once($config['db_connect']);

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['repeat_password'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$repeat_password = $_POST['repeat_password'];
}
$username = trim($username);
$password = trim($password);

$name = $mysqli->real_escape_string($name);
$email = $mysqli->real_escape_string($email);
$username = $mysqli->real_escape_string($username);
$password = $mysqli->real_escape_string($password);				
$userAgent = $mysqli->real_escape_string($_SERVER['HTTP_USER_AGENT']);
$ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
//echo VerifySignUpData::verifyPassword("
//check data integrity
userNameAvailable($username, $mysqli);
emailAvailable($email, $mysqli);
passwordMatch($password, $repeat_password);
VerifySignUpData::verifyUsername($username);
VerifySignUpData::verifyEmail($email);
VerifySignUpData::verifyName($name);
VerifySignUpData::verifyPassword($password);

$salt = generate_salt();
$password_hash = hash("sha256", $password . $salt) . "-" . $salt;
//all clear
if(count($error_array) == 0){
	$successfully_registered = true;
	$sql = "INSERT INTO `users` (`username`, `password`, `name`, `email`, `ip`, `device_id`) VALUES ('$username', '$password_hash', '$name', '$email', '$ip', '$userAgent')";
	$mysqli->query($sql) or die();

	require_once($config['session']);
	$user_id = getUserID($username);


	$s = createSession($user_id);
	$session_id = $s[0];
	$expire = strtotime($s[1]);

	if(defined("WEB")){ //API
		$long_url = preg_replace("(^https?://)", "", REDIRECT_DOMAIN_NAME );
		setcookie("SSID", $session_id, $expire, "/", "$long_url");
		header('Location: ' . FULL_DOMAIN_NAME);
	}



}

$signup_response = array("Session" => $session_id, "Errors"=> $error_array, "Success" => $successfully_registered);
if(!defined("WEB")){ //API
	header('Content-Type: application/json');
	echo json_encode($signup_response, JSON_PRETTY_PRINT);
}



