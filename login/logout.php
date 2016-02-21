<?php
require_once('../config.php');
$error_array = array();
$successfully_logged_in = false;
require_once($config['db_connect']);
require_once($config['session']);
if(isset($_COOKIE['SSID'])){
	$session_id = $_COOKIE['SSID'];
	destroySession($session_id);
}
$long_url = preg_replace("(^https?://)", "", REDIRECT_DOMAIN_NAME );
setcookie("SSID", "", time()-100000, "/", "$long_url");
header('Location: ' . FULL_DOMAIN_NAME);
?>