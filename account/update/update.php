<?php

require_once('../../config.php');

$error_array          = array();
$successfully_updated = false;

$user = null;
require_once($config['session']);
if (isset($_COOKIE['SSID'])) {
	$session_id = $_COOKIE['SSID'];
	$user_id    = authenticate($session_id);
	if($user_id != 0){
		extendSession($session_id);
		$user = userData($user_id);
	}
} 
else {
	addError("Please log in.");
}



class VerifySignUpData
{
	private static function basicChecker($value, $lengthMin, $lengthMax, $description, $isAlphaNum)
	{
		if (empty($value))
			return addError($description . " cannot be empty.");

		if ($isAlphaNum && !ctype_alnum($value))
			return addError($description . " is not valid.");

		if (strlen($value) < $lengthMin)
			return addError($description . " must be at least " . $lengthMin . " characters.");

		if (strlen($value) > $lengthMax)
			return addError($description . " must be shorter than " . $lengthMax . " characters.");
	}

	public static function verifyUsername($u)
	{
		return self::basicChecker($u, 3, 64, "Username", true);
	}
	public static function verifyPassword($p)
	{
		return self::basicChecker($p, 6, 32, "Password", false);
	}
	public static function verifyName($n)
	{
		return self::basicChecker($n, 3, 64, "Name", false);
	}
	public static function verifyEmail($e)
	{
		if (filter_var($e, FILTER_VALIDATE_EMAIL))
			return true;
		addError("Email is not valid.");
	}

}
function addError($string)
{
	global $error_array;
	$error_array[] = $string;
}
function userNameAvailable($username, $mysqli)
{
	global $user;
	if($username == $user -> username) return;
	$checkUsernameSQL   = "SELECT 1 FROM `users` WHERE `username` = '$username'";
	$checkUsernameQuery = $mysqli->query($checkUsernameSQL);
	if ($checkUsernameQuery->num_rows != 0)
		addError("This username already exists.");
}
function emailAvailable($email, $mysqli)
{
	$checkEmailSQL   = "SELECT 1 FROM `users` WHERE `email` = '$email'";
	$checkEmailQuery = $mysqli->query($checkEmailSQL);
	if ($checkEmailQuery->num_rows != 0)
		addError("This e-mail is already registered.");
}


require_once($config['db_connect']);

if (isset($_POST['username']) && isset($_POST['name'])) {
	$name     = $_POST['name'];
	$username = $_POST['username'];
}
$username = trim($username);

$name     = $mysqli->real_escape_string($name);
$username = $mysqli->real_escape_string($username);

userNameAvailable($username, $mysqli);
emailAvailable($email, $mysqli);
VerifySignUpData::verifyUsername($username);
VerifySignUpData::verifyName($name);



if (count($error_array) == 0) {
	$successfully_updated = true;
	$sql                  = "UPDATE `users` SET `username` = '$username', `name`='$name' WHERE `user_id`=$user_id";
	$mysqli->query($sql) or die();
}
$update_response = array(
	"Errors" => $error_array,
	"Success" => $successfully_updated
	);

  if (!defined("WEB")) { //API
  	header('Content-Type: application/json');
  	echo json_encode($update_response, JSON_PRETTY_PRINT);
  }
  
  ?>