<?php 

	require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/config.php');

	require_once($config['db_connect']);

	$date_time = new DateTime();
	$new_date_time = new DateTime();

	$new_date_time->modify('+' . (365*24) . " hour");
	
	function generateSessionKey(){
		$characters = '0123456789abcdef';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < 36; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
	}
	function getUserID($username){
		global $mysqli;
		$query = $mysqli->query("SELECT `user_id` FROM `users` WHERE `username`='$username' ");
		while($r = $query->fetch_assoc()){
			return $r["user_id"];
		}
	}
	function createSession($user_id){
		global $session_id, $date_time, $new_date_time, $mysqli;
		$userAgent = $mysqli->real_escape_string($_SERVER['HTTP_USER_AGENT']);
		$ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
		$date_time_f = $date_time->format('Y-m-d H:i:s');
		$new_date_time_f = $new_date_time->format('Y-m-d H:i:s');
		$session_id = generateSessionKey();
		$session_id_hash = hash("sha256", $session_id);
		
		$query = $mysqli->query("INSERT INTO `sess` (`ses_id`, `user_id`, `time_created`, `time_updated`, `time_expire`, `ip`, `device_id`) 
			VALUES ('$session_id_hash', '$user_id', '$date_time_f', '$date_time_f', '$new_date_time_f', '$ip', '$userAgent') ");

		return array($session_id, $new_date_time_f);
	}
	function extendSession($session_id){
		global $date_time, $new_date_time, $mysqli;
		$date_time_f = $date_time->format('Y-m-d H:i:s');
		$new_date_time_f = $new_date_time->format('Y-m-d H:i:s');
		$session_id_hash = hash("sha256", $session_id);
		$query = $mysqli->query("UPDATE `sess` SET `time_updated`='$date_time_f',`time_expire`='$new_date_time_f' WHERE `ses_id`='$session_id_hash'");
	}
	function destroySession($session_id){
  		global $mysqli;
  		$session_id_hash = hash("sha256", $session_id);
		$query = $mysqli->query("DELETE FROM `sess` WHERE `ses_id`='$session_id_hash'");
	}
	function destroyAllSessions($user_id, $current_session = ""){
		global $mysqli;
		$current_session_hash = hash("sha256", $current_session);
		$query = $mysqli->query("DELETE FROM `sess` WHERE `user_id`='$user_id' AND `ses_id`!='$current_session_hash'");
	}
	//0 for not loggedin, otherwise user_id 
	function authenticate($session_id){
		global $date_time, $mysqli;
		$session_id_hash = hash("sha256", $session_id);
		$date_time_f = $date_time->format('Y-m-d H:i:s');
		$query = $mysqli->query("SELECT `user_id` FROM `sess` WHERE `ses_id` LIKE '$session_id_hash' AND `time_expire` > '$date_time_f' LIMIT 0,1") or die();
		while($r = $query->fetch_assoc()){
			return $r["user_id"];
		}
		return 0;
	}
	function userData($user_id){
		$u = new User();
		$u -> fillFromDB($user_id);
		return $u;
	}
	//right now, only read only
	class User {
	  	public $user_id;
	  	public $username;
	  	public $name;
	  	public $email;
	  	public $is_registered;
	    function fillFromDB($user_id){
			global $mysqli;
			$query = $mysqli->query("SELECT `user_id`, `username`, `name`, `email`, `is_registered` FROM `users` WHERE `user_id` = '$user_id'");
			while($r = $query->fetch_assoc()){
				$this -> user_id = $r["user_id"];
				$this -> username = $r["username"];
				$this -> name = $r["name"];
				$this -> email = $r["email"];
				$this -> is_registered = $r["is_registered"];
			}
		}
	}



?>