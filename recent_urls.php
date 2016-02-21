<?php

require_once('config.php');

$long_url = null;
$a = array();

$error_array = array();

if(isset($_GET['max'])){
	$show_max = $_GET['max'];
}

if(!defined("WEB")){
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
}


if(!defined("WEB")){
	require_once($config['db_connect']);
}

if($user != null){
	$sql = "SELECT `short_code`, `url_to` FROM `short` WHERE `user_id` = $user->user_id ORDER BY `time_created` DESC";
}
else{
	$shortlinks = $mysqli->real_escape_string($_COOKIE['shortlinks']);
	if(strpos($shortlinks, ",") !== false){
		$shortlinks = explode(",", $shortlinks);
		foreach ($shortlinks as &$value){
			$value = "'".$value."'";
		}
		$all_short_codes = implode(",", $shortlinks);
	}
	else{
		$all_short_codes = "'" . $shortlinks . "'";
	}
	$sql = "SELECT `short_code`, `url_to` FROM `short` WHERE `short_code` IN ($all_short_codes) ORDER BY `time_created` DESC";
}

$result = $mysqli->query($sql);
if($result->num_rows != 0){	
	$limit = 0;
	if(isset($show_max)){
		while(($r = $result->fetch_assoc()) && ($limit < $show_max)){
			$a[] = $r;
			$limit++;
		}
	}
	else{
		while($r = $result->fetch_assoc()){
			$a[] = $r;
		}
	}
}


if(!defined("WEB")){
	header('Content-Type: application/json');

	$errors = array();

	if(empty($a)){
		$errors[] = "You have not shortened any URLs yet.";
	}

	$a = array("URLs" => $a, "Errors" => $errors);
	echo json_encode($a, JSON_PRETTY_PRINT);
}
else{
	for($i=0; $i<count($a); $i++){
		add_recent_url($a[$i]['short_code'], $a[$i]['url_to']);
	}
}

if(isset($show_max) && defined("WEB")){
	if($result->num_rows > $show_max){
		echo '<div class="recently_url" style="line-height:70px;text-align:center"><a style="color:#2e9a31;font-weight:400" href="/urls/">View More</a></div>';
	}
}

function add_error($error){
	if(defined("WEB")){
		echo '<p id="no_shortlinks">You have not shortened any URLs yet.</p>';
	}
}

function add_recent_url($short_url, $long_url){
	$short_url = REDIRECT_DOMAIN_NAME .'/'. $short_url;
	echo '<div class="recently_url"><div class="recently_urls_side"><img src="/images/link_icon.png" alt="Link"/></div><div class="recently_urls_center"><p class="short_url"><a target="_blank" href="'.$short_url.'">'.$short_url.'</a></p><p class="long_url"><a target="_blank" href="'.$long_url.'">'.$long_url.'</a></p></div><div class="recently_urls_side recently_urls_side_right"><a href="' . $short_url . '+"><img src="/images/stats_icon.png" alt="Statistics" title="View Statistics"/></a></div></div>';		
}					

?>