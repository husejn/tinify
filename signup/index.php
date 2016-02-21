<?php
	//
	require_once('../config.php');

	define("WEB", true);

	require_once($config['session']);
	if(isset($_COOKIE['SSID'])){
		$session_id = $_COOKIE['SSID'];
		$user_id = authenticate($session_id);
		if(authenticate($session_id) != 0){
			header('Location: ' . FULL_DOMAIN_NAME);
			exit;
		}
	}

	$null = "";
	if(isset($_POST) && !empty($_POST)){
		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
			$captcha_response = $_POST['g-recaptcha-response'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$captcha_response_json = post_request("https://www.google.com/recaptcha/api/siteverify", "secret=" . RECAP_PRIVATE . "&remoteip=" . $ip . "&response=" . $captcha_response);

			
			
			$captcha_response_json = json_decode($captcha_response_json, true);
			if($captcha_response_json['success'] === true){
				require_once($config['signup']);	
			}
			else{
				$error = array("Incorrect captcha.");
			}
		}
		else{
			$error = array("Please complete captcha verification.");
		}

		if(isset($error)){
			$signup_response = array("Errors"=> $error);
		}

	}

	function post_request($url, $data){
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => $data,
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}

?>
<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?> - Register</title>
		<?php include_once($config['head']); ?>
		<script src="https://www.google.com/recaptcha/api.js" type="text/javascript"></script>
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

							<h1>Register on <?php echo SITE_NAME; ?></h1>

							<form name="register" method="post" action="" onsubmit="return validateRegister()">

								<?php

								$username = array("Username", "username", "user", "name");
								$email = array("Email", "email", "email", "name");
								$name = array("Name", "name", "user", "name");
								$password = array("Password", "password", "password", "password");
								$repeat_password = array("Confirm Password", "repeat_password", "password", "password");

								$array = array($username, $email, $name, $password, $repeat_password);

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

								
								<noscript><br/>Enable JavaScript on your browser to register.</noscript>

								<div style="margin-top:4px" class="g-recaptcha" id="rcaptcha" data-sitekey="<?php echo RECAP_PUBLIC; ?>"></div>
								<div id="captcha_error" class="input_error" style="text-align:left">&nbsp;</div>

								<input type="submit" value="Register" name="submit" class="button" style="margin-top:20px"/>

							</form>

							<br/>

							<p style="opacity:1;text-align:left" id="shorten_error">
								<?php
									if(isset($signup_response['Errors'])){
										echo implode("<br/>", $signup_response['Errors']);
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
