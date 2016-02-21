<?php

	require_once($config['session']);
	header('Content-Type: application/json');
	$user = null;
	$error_array = array();


	if(isset($_COOKIE['SSID'])){
		$session_id = $_COOKIE['SSID'];
		$user_id = authenticate($session_id);
		if(authenticate($session_id) != 0){
			extendSession($session_id);
			$user = userData($user_id);
		}
	}

	if($user==NULL){
		printApi(null, false);
	}
	else{
		printApi($user, true);
	}

	function printApi($user_, $successfully_logged_in){
		$login_response = array("Data" => $user_, "Success" => $successfully_logged_in);
		echo json_encode($login_response, JSON_PRETTY_PRINT);
	}

?>