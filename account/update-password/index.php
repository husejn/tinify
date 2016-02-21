<?php

require_once('../../config.php');

define("WEB", true);

require_once($config['session']);
if(isset($_COOKIE['SSID'])){
	$session_id = $_COOKIE['SSID'];
	$user_id = authenticate($session_id);
	if($user_id == 0){
		header('Location: '. FULL_DOMAIN_NAME .'/login/');
		exit;
	}
	$user = userData($user_id);
}
else {
	header('Location: '. FULL_DOMAIN_NAME .'/login/');
	exit;
}


if(isset($_POST) && !empty($_POST)){
	require_once($config['updatepassword']);	
}

?>
<!DOCTYPE html>
<html>

<head>
	<title><?php echo SITE_NAME; ?> - Change Password</title>
	<?php include_once($config['head']); ?>
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

				<div class="main_width">

					<div class="vertical_align register_input">

						<h1>Change your password</h1>

						<form name="register" method="post" action="">

							<?php
							// Placeholder, input name, picture location, input type, value
							$array = array();
							$array[] = array("Password", "oldpassword", "password", "password");
							$array[] = array("New Password", "newpassword", "password", "password");
							$array[] = array("New Password", "repeatpassword", "password", "password");

							for($i=0;$i<count($array);$i++){
								$first = ($i == 0) ? "first_div" : "";
								$str = '
								<div>
									<div class="input_div '. $first .'">
										<div class="input_left_icon">
											<img src="/images/'.$array[$i][2].'.png" width="24" height="24" alt="Link Icon" />
										</div>
										<div class="input_left_input">
											<input class="input" type="'.$array[$i][3].'" name="'.$array[$i][1].'" placeholder="'.$array[$i][0].'" value="" />
										</div>
									</div>
									<div id="'.$array[$i][1].'_error" class="input_error">&nbsp;</div>
								</div>
								';
								echo $str;
							}
							?>
							<input type="submit" value="Change Password" name="submit" class="button" style="margin-top:20px"/>

						</form>

						<br/>

						<p style="opacity:1;text-align:left" id="shorten_error">
							<?php
							if($update_response['Success']){
								echo "Successfully updated";
							}
							else {
								echo implode("<br/>", $update_response['Errors']);
							}

							?>
						</p>

					</div>

				</div>

			</div>

		</div>

		<div class="push"></div>

		<script src="/js/script.js" type="text/javascript"></script>
		
	</div>

	<div id="footer" class="footer_green">
		<div class="main_width">
			<?php include_once($config['footer']); ?>
		</div>
	</div>
</body>

</html>
