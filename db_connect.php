<?php

require_once('config.php');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("ERROR");
$mysqli->set_charset('utf8');
$now_time = "now()";

?>