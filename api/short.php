<?php

class ShortURL {
	const ALPHABET = 'Vpdm7rXLZkf9jzQRy6JWKvMSGYgPH5xbwnhtB8q42cNCs3DTF';
	const BASE = 49;//strlen(self::ALPHABET);
	public static function encode($num) {
		$str = '';
		while ($num > 0) {
			$str = substr(self::ALPHABET, ($num % self::BASE), 1) . $str;
			$num = floor($num / self::BASE);
		}
	return $str;
	}
	public static function decode($str) {
		$num = 0;
		$len = strlen($str);
		for ($i = 0; $i < $len; $i++) {
			$num = $num * self::BASE + strpos(self::ALPHABET, $str[$i]);
		}
		return $num;
	}
}
/** Checks if shorten hash is already in use
*/
function checkIfShortenAvailable($short_code){
	global $mysqli;
	$query = $mysqli->query("SELECT `short_code` FROM `short` where BINARY `short_code` = '$short_code'");
	if($query->num_rows == 0){
		return true;
	} 
	return false;
}
function getLastIdFromDB(){
	global $mysqli;
	$query = $mysqli->query("SELECT `short_id` FROM `short` ORDER BY(`short_id`) DESC LIMIT 1");
	while($r = $query->fetch_assoc()){
		return $r["short_id"];
	}
	return pow(ShortURL::BASE, HASH_LENGTH - 1); //start with three char code
}

function addLinkToDB($url, $user_id = 1){
	global $mysqli;
	$short_id = getLastIdFromDB() + 1;
	$short_code = ShortURL::encode($short_id);
	while(!checkIfShortenAvailable($short_code)){
		$short_id = $short_id + 1;
		$short_code = ShortURL::encode($short_id);
	}
	$userAgent = $mysqli->real_escape_string($_SERVER['HTTP_USER_AGENT']);
	$ip = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
	$sql = "INSERT INTO `tinify`.`short` (`short_id`, `short_code`, `user_id`, `time_created`, `url_to`, `ip`, `device_id`) VALUES ($short_id, '$short_code', '$user_id', CURRENT_TIMESTAMP, '$url', '$ip', '$userAgent');";
	$mysqli->query($sql) or die("Error");
	return $short_code;
}
function retriveLinkFromDB($short_code){
	global $mysqli;
	$sql = "SELECT `url_to`, `short_id` FROM `tinify`.`short` where `short_code` = '$short_code'";
	$query = $mysqli->query($sql);
	while($r = $query->fetch_assoc()){
		return array($r["url_to"], $r["short_id"]);
	}
	return 0;
}
/** Not yet implemented
*/
function editLinkFromDB($short_code, $user_id, $newUrl){
	if($user_id == 0) return 0;
}
/** Not yet implemented
*/
function editShortFromDB($short_code, $user_id, $newShort){
	if($user_id == 0) return 0;
}

?>