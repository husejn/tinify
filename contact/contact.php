<?php

require_once('../config.php');

$error_array = array();
$successfully_sent = false;

require_once($config['db_connect']);

function addError($string){
	global $error_array;
	$error_array[] = $string;
}


if(!isset($_POST['email']) || empty($_POST['email'])|| !isset($_POST['message']) || empty($_POST['message'])) {
	addError("Please enter both email address and message.");
}


$email = $_POST['email'];
$message = $_POST['message'];

$email = trim($email);
$message = trim($message);

$email = $mysqli->real_escape_string($email);
$message = $mysqli->real_escape_string($message); 

//all clear
if(count($error_array) == 0){
	require_once($config['session']);

	$userAgent = $mysqli->real_escape_string($_SERVER['HTTP_USER_AGENT']);
	$ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);

	$sql = "INSERT INTO `contact` (`message`, `email`, `ip`, `device_id`) VALUES ('$message', '$email', '$ip', '$userAgent')";

	$mysqli->query($sql) or die('ERROR SENDING MESSAGE');
	$successfully_sent = true;
	$contact_response = array("Success" => $successfully_sent);

} 
else{ //message failed
	$contact_response = array("Success" => $successfully_sent);
}






?>