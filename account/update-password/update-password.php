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
	public static function verifyPassword($p, $fieldname = "Password")
	{
		return self::basicChecker($p, 6, 32, $fieldname, false);
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
function checkPassword($password){
	if(empty($password)) return;
	global $mysqli, $user;
	$user_id = $user -> user_id;
	$hashed_password;
	$q = "SELECT `user_id`,`password` FROM `users` WHERE `user_id` = '$user_id' AND `is_active` = 1 ";
	$query = $mysqli->query($q) or addError("Unknown error.");
	if($query->num_rows == 0){
		addError("Incorrect Password");
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
		addError("Incorrect Password");
		return 0;
	}
}
function passwordMatch($password, $repeat_password){
	if($password !== $repeat_password)
		addError("Passwords don't match");
}
function generate_salt(){
	return uniqid(mt_rand(), true);
}

require_once($config['db_connect']);
if(!isset($_POST['oldpassword']) || empty($_POST['newpassword']) || !isset($_POST['repeatpassword'])) {
    addError("All fields are required.");
}

$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$repeatpassword = $_POST['repeatpassword'];

$oldpassword = trim($oldpassword);
$newpassword = trim($newpassword);
$repeatpassword = trim($repeatpassword);

$oldpassword = $mysqli->real_escape_string($oldpassword);
$newpassword = $mysqli->real_escape_string($newpassword);
$repeatpassword = $mysqli->real_escape_string($repeatpassword);



VerifySignUpData::verifyPassword($oldpassword);
VerifySignUpData::verifyPassword($newpassword, "New Password");

checkPassword($oldpassword);
passwordMatch($newpassword, $repeatpassword);




if (count($error_array) == 0) {
	$salt = generate_salt();
	$password_hash = hash("sha256", $newpassword . $salt) . "-" . $salt;

	$successfully_updated = true;

	destroyAllSessions($user->user_id, $_COOKIE['SSID']);

	$sql                  = "UPDATE `users` SET `password` = '$password_hash' WHERE `user_id`=$user_id";
	$mysqli->query($sql) or addError("Could not Update");
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