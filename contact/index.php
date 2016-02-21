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
if(isset($_POST['email']) && isset($_POST['message'])){ //trying to contact
	require_once($config['contact']);
}

?>

<!DOCTYPE html>
<html>

<head>
	<title><?php echo SITE_NAME; ?> - Contact</title>
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

						<h1>Contact Us</h1>

						<?php
						$displayForm = true;
						if($contact_response){
							if($contact_response['Success']){
								$displayForm = false;
								echo '<h3 style="margin-top: 24px; color: green">Message succesfully sent</h3>';
							}else{
								echo '<h3 style="margin-top: 24px; color: red">Message failed to be sent.</h3>';
							}
						}


						if($displayForm){
							?>


							<form name="contact" method="post" action="" onsubmit="return validateContact()">

								<?php

								$email = array("Email", "email", "email", "name");

								$array = array($email);

								$email_value = "";

								if(isset($_POST['email'])){
									$email_value = $_POST['email'];
								}
								else if($user!=null){
									$email_value = $user -> email;
								}


								for($i=0;$i<count($array);$i++){
									$first = ($i == 0) ? "first_div" : "";
									$str = '
									<div>
										<div class="input_div ' . $first  .'">
											<div class="input_left_icon">
												<img src="/images/'.$array[$i][2].'.png" width="24" height="24" alt="Link Icon" />
											</div>
											<div class="input_left_input">
												<input class="input" type="'.$array[$i][3].'" name="'.$array[$i][1].'" placeholder="'.$array[$i][0].'" value="'.$email_value.'" />
											</div>
										</div>
										<div id="'.$array[$i][1].'_error" class="input_error">&nbsp;</div>
									</div>
									';
									echo $str;
								}
								?>

								<div>
									<div class="input_div" style="overflow:hidden;height:auto;min-height:70px;">
										<div class="input_left_icon">
											<img src="/images/comment.png" width="24" height="24" alt="Link Icon" />
										</div>
										<div class="input_left_input">
											<textarea style="padding-top:7px;height:200px;width:99%;max-width:435px;" class="input" name="message" placeholder="Your Message"><?php $a = (isset($_POST['message'])) == true ? $_POST['message'] : ""; echo $a; ?></textarea>
										</div>
									</div>
									<div id="message_error" class="input_error">&nbsp;</div>
								</div>


								<input type="submit" value="Send" name="submit" class="button" style="margin-top:20px"/>

							</form>
							<?php

						}
						
						?>
						<br/>

						<p style="opacity:1;text-align:left" id="shorten_error">

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








