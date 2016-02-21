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
	<title><?php echo SITE_NAME; ?> - Account</title>
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

						<div class="main_width" >
							<h1 style="text-align:left">Account Details</h1>
							
							<p class="acc_details">Username: <?php echo $user -> username; ?></p>
							<p class="acc_details">Name: <?php echo $user -> name; ?></p>
							<p class="acc_details">Email: <?php echo $user -> email; ?></p>
							<p class="acc_details">Password: ********</p>


							<div class="change_buttons">
								<a href="/account/update/"><button class="button">Edit Details</button></a>
								<a href="/account/update-password/"><button class="button">Change Password</button></a>
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
