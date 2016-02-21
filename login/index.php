<?php
	require_once('../config.php');
	define("WEB", true);
	require_once($config['session']);
	if(isset($_COOKIE['SSID'])){
		$session_id = $_COOKIE['SSID'];
		$user_id = authenticate($session_id);
		if(authenticate($session_id) != 0){
			header('Location: '.FULL_DOMAIN_NAME);
			exit;
		}
	}
	if(isset($_POST['username']) && isset($_POST['password'])){ //trying to log in
		require_once($config['login']);
	}

?>

<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?> - Register</title>
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

							<h1>Login on <?php echo SITE_NAME; ?></h1>

							<form name="login" method="post" action="" onsubmit="return validateLogin()">

								<?php

								$username = array("Username", "username", "user", "text");
								$password = array("Password", "password", "password", "password");

								$array = array($username, $password);

								for($i=0;$i<count($array);$i++){
									$first = ($i == 0) ? "first_div" : "";
									$str = '
									<div>
										<div class="input_div ' . $first . '">
											<div class="input_left_icon">
												<img src="/images/'.$array[$i][2].'.png" width="24" height="24" alt="Link Icon" />
											</div>
											<div class="input_left_input">
												<input ' . (($i==0)?'autofocus':'') . ' class="input" type="'.$array[$i][3].'" name="'.$array[$i][1].'" placeholder="'.$array[$i][0].'" value="'.(isset($_POST[$array[$i][1]])==true?$_POST[$array[$i][1]]:"").'" />
											</div>
										</div>
										<div id="'.$array[$i][1].'_error" class="input_error">&nbsp;</div>
									</div>
									';
									echo $str;
								}
								?>

								<div>
									<input type="checkbox" name="remember_me" id="remember_me" value="yes" />
									<label for="remember_me">Remember Me?</label>
								</div>

								<input type="submit" value="Login" name="submit" class="button" style="margin-top:20px"/>

							</form>

							<br/>

							<p style="opacity:1;text-align:left" id="shorten_error">
								<?php
									if(isset($error_array))
										echo implode("<br/>", $error_array);
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








