<?php

require_once('../config.php');

define("WEB", true);
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


?>
<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?> - My URLs</title>
		<?php include_once($config['head']); ?>
		<style>.long_url a{color:#BBBBBB}.short_url a{color:#4d6c4d}</style>
	</head>

	<body>

		<div id="wrapper">

			<div id="header">
				<div class="main_width">
					<?php include_once($config['header']) ?>
				</div>
			</div>

			<div id="content">

				<div id="main_content" class="gradient_background">

					<div id="recently_urls">

						<div class="main_width" style="display:block">
							<h1>Your shortened URLs</h1>
							<div class="recently_urls" id="urls">

								<?php

									$long_url = null;

									if($user != null || isset($_COOKIE['shortlinks'])){

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
											$a = array();	
											while($r = $result->fetch_assoc()){
												$a[] = $r;
											}
											for($i=0; $i<count($a); $i++){
												add_recent_url($a[$i]['short_code'], $a[$i]['url_to']);
											}
										}
										else{
											echo '<p id="no_shortlinks">You have not shortened any URLs yet.</p>';
										}

									}
									else{
										echo '<p id="no_shortlinks">You have not shortened any URLs yet.</p>';
									}

									function add_recent_url($short_url, $long_url){
										$short_url = REDIRECT_DOMAIN_NAME . "/" . $short_url;
										echo '<div class="recently_url"><div class="recently_urls_side"><img src="/images/link_icon.png" alt="Link"/></div><div class="recently_urls_center"><p class="short_url"><a target="_blank" href="'.$short_url.'">'.$short_url.'</a></p><p class="long_url"><a target="_blank" href="'.$long_url.'">'.$long_url.'</a></p></div><div class="recently_urls_side recently_urls_side_right"><a href="' . $short_url . '+"><img src="/images/stats_icon.png" alt="Statistics" title="View statistics"/></a></div></div>';		
									}					

								?>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="push"></div>

		</div>

		<div id="footer" class="footer_green">
			<div class="main_width">
				<?php include_once($config['footer']); ?>
			</div>
		</div>

		<script type="text/javascript" src="/js/script.js"></script>

	</body>

</html>