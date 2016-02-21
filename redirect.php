<?php

	require_once('config.php');

	require_once($config['db_connect']);
	require_once($config['short']);
	$statistic_request = false;
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
	$request_code = str_replace('/','',$uri_parts[0]);

	if(endsWith($request_code, '+')){
		$statistic_request = true;
		$request_code = substr($request_code, 0, -1);
	}
	$short_code = $mysqli->real_escape_string($request_code);

	$short_data = retriveLinkFromDB($short_code);
	$short_link = $short_data[0];
	$short_id = $short_data[1];
	$ip = $_SERVER['REMOTE_ADDR'];
	function endsWith($haystack, $needle) {
	    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
	function getCountryFromIp(){
		global $mysqli;
		global $ip;

		$ip = $mysqli->real_escape_string($ip);		
		$sql = 'SELECT 
	            c.country as `country`
	        FROM 
	            ip2nationCountries c,
	            ip2nation i 
	        WHERE 
	            i.ip < INET_ATON("'. $ip .'") 
	            AND 
	            c.code = i.country 
	        ORDER BY 
	            i.ip DESC 
	        LIMIT 0,1';

	    $query = $mysqli->query($sql);
	    while ($r = $query->fetch_assoc()) {
	      return $r['country'];
	    }

	    return "Unknown";

	}
	function addVisitToDB(){
		global $short_id;
		global $mysqli;
		global $ip;

		$country = getCountryFromIp();
		$referer = '';
		if(isset($_SERVER['HTTP_REFERER']))
			$referer = $_SERVER['HTTP_REFERER'];

		$useragent = $_SERVER['HTTP_USER_AGENT'];

		$ip = $mysqli->real_escape_string($ip);
		$referer = $mysqli->real_escape_string($referer);
		$useragent = $mysqli->real_escape_string($useragent);
	    $sql = "INSERT INTO `visit` (`short_id`, `ip`, `country`, `referer`, `useragent`) VALUES ($short_id, '$ip', '$country', '$referer', '$useragent')";
		$mysqli->query($sql);
	}


	if($statistic_request && $short_data !== 0){ ?>

<?php

	define("WEB", true);
	require_once($config['session']);
	$url_id = $request_code;
	require_once($config['urldata']);
	$user = null;
	if(isset($_COOKIE['SSID'])){
		$session_id = $_COOKIE['SSID'];
		$user_id = authenticate($session_id);
		if(authenticate($session_id) != 0){
			extendSession($session_id);
			$user = userData($user_id);
		}
	}		


	$hasPermissionForStatisctics = false;
	if ($url_data['user_id'] == 1 || $url_data['user_id'] == $user -> user_id){
		$hasPermissionForStatisctics = true;
	} 

?>
<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?></title>
		<?php include_once($config['head']); ?>
		<style>
			.long_url a{
				color:#BBBBBB
			}
			.short_url a{
				color:#4d6c4d
			}
			#canvas-holder{
				width:60%;
				margin: 12px auto;
			}
			.left-holder{
				width:50%;
				margin: 12px auto;
				float: left;
			}
			.right-holder{
				width:50%;
				margin: 12px auto;
				float: right;
			}
			.legend { 
				list-style: none; 
			}
			.legend li { 
				margin-right: 10px; 
			}
			.legend span { 
				float: left; 
				display: block;
				width: 13px; 
				height: 13px;
				margin: 2px 6px 1px 2px; 
			}
			@media only screen and (max-width: 800px){
				.left-holder{
					width:100%;
					clear: both;
				}
				.right-holder{
					width:100%;
					clear: both;
				}

			}
		</style>
		<script type="text/javascript" src="/js/chart.min.js"></script>
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
							<?php 
								$url = preg_replace("(^https?://)", "", REDIRECT_DOMAIN_NAME );
							?>
							<h1 style="text-align:left"><?php echo $url; ?>/<span style="color:green;"><?php echo $short_code; ?></span></h1>
							<div>
								<p style="float: left;"><?php echo printHTML( $url_data['url_to'] ) ?></p>
								<p style="float: left;clear: left;">Created by <span style="color:green;"><?php echo printHTML( $url_data['username'] ) ?></span> on <?php echo printHTML( $url_data['time_created'] );  ?></p>

								<p style="float: right;"><?php if ($url_data['user_id'] == 1 ) echo "Public"; else echo "Private"; ?></p>
							</div>


							<h3 style="clear: both; margin: 77px auto 33px auto;">Geographic Distribution of Clicks</h3>
							<div class="left-holder">
								<?php
									if(!$hasPermissionForStatisctics)
										echo "You don't have permission to view statistics for this link.";
								?>
								<div id="canvas-holder">
									<canvas id="chart-area" width="500" height="500"/>
								</div>
							</div>
							<div class="right-holder">
							<?php
								if($hasPermissionForStatisctics)
									echo '<p id="clicks">Total Clicks: 0</p>';
							?>
								
								<ul class="legend" id="legend">
								</ul>

							</div>
							<script>
								<?php
									$colorlist = array(
										"color" => array("F7464A", "46BFBD", "FDB45C", "949FB1", "4D5360", "616774", "95A57C", "B7784E"),
										"highlight" => array("FF5A5E", "5AD3D1", "FFC870", "A8B3C5", "616774", "5F7EAf", "8C9D70", "D9B8A2")
									);

									$printinglist = array();

									for ($i = 0; $i < count($countrylist); $i++) {
										$printinglist[] = array(
											'value' => $countrylist[$i]['count'], 
											'label' => $countrylist[$i]['country'], 
											'color' => '#' . $colorlist['color'][ $i % count($colorlist['color'] )], 
											'highlight' => '#' . $colorlist['highlight'][ $i % count($colorlist['highlight'] )]
											);

									}

								?>


								var countryList = <?php echo json_encode($printinglist, JSON_PRETTY_PRINT); ?>;
								var html = "";
								if(countryList.length > 0){
									var sum = 0;
									for(var i = 0; i < countryList.length; i++){
										sum += parseInt(countryList[i]['value']);
										html += '<li><span style="background-color:' + countryList[i]['color'] + '"></span> ' + countryList[i]['label'] + '</li>';
									}
									document.getElementById('legend').innerHTML = html;
									document.getElementById('clicks').innerHTML = "Total Clicks: " + sum;
								}


								window.onload = function(){
									var ctx = document.getElementById("chart-area").getContext("2d");
									window.myDoughnut = new Chart(ctx).Doughnut(countryList, {
										responsive : true,
										animationEasing : "easeOutExpo"

									});
								};





							</script>

							

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















	<?php }
	else if($short_data !== 0){
		header("Location: " . $short_link);
		addVisitToDB();
		exit;
	}
	else{ ?>
<!DOCTYPE html>
<html>
	<head></head>
	<body>
		<p>This Link could not be found. Please recheck the URL.</p>
	</body>	

</html>
	<?php
	}
	

?>
