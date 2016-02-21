<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/config.php');
require_once($config['short']);

if(!$url_id) die();

$short_code = $mysqli->real_escape_string($url_id);

$short_data = retriveLinkFromDB($short_code);
$short_link = $short_data[0];
$short_id = $short_data[1];
if($short_id == 0){
	die("Error");
}

function getCountriesClicks(){
	global $mysqli;
	global $short_id;
	global $user_id;

	$sql = "SELECT `country`, COUNT(*) as `count` 
		FROM `visit`, `short` 
		WHERE `visit`.`short_id` = `short`.`short_id` AND (`user_id` = 1 OR `user_id` = $user_id) AND `visit`.`short_id` = $short_id
		GROUP BY `country` 
		ORDER BY `count` DESC, `country`

			";
			$arr = array();
    $query = $mysqli->query($sql);
    while ($r = $query->fetch_assoc()) {
      $arr[] = array('country' => $r['country'], 'count' => $r['count'] );
    }
    return $arr;
}

$countrylist = getCountriesClicks();

?>