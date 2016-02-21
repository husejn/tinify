<?php

include_once('config.php');



define("WEB", true);
require_once($config['session']);
$user = null;
if(isset($_COOKIE['SSID'])){
	$session_id = $_COOKIE['SSID'];
	$user_id = authenticate($session_id);
	if($user_id != 0){
		extendSession($session_id);
		$user = userData($user_id);
	}
}


?>
<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?></title>
		<?php include_once($config['head']); ?>
		<style>.long_url a{color:#BBBBBB}.short_url a{color:#4d6c4d}</style>
		<script>
		<?php
			$long_url = preg_replace("(^https?://)", "", REDIRECT_DOMAIN_NAME );
			$long_url_www = preg_replace("(^https?://)", "", FULL_DOMAIN_NAME );
		?>

		var t_long_url = <?php echo '"' . $long_url . '"'; ?>;
		var t_long_url_www = <?php echo '"' . $long_url_www . '"'; ?>;
		var full_url = <?php echo '"' . REDIRECT_DOMAIN_NAME . '"'; ?>; 

		</script>
	</head>

	<body>

		<div id="wrapper">

			<div id="header">
				<div class="main_width">
					<?php include_once($config['header']) ?>
				</div>
			</div>

			<div id="content">

				<div id="main_content">

					<div class="main_width">

						<div class="vertical_align">

							<h1>The simplest URL shortener</h1>
							<div class="input_div first_div">

								<div class="input_left" onclick="document.getElementById('url').focus()">

									<div class="input_left_icon">
										<img src="/images/link_icon.png" width="30" height="30" alt="Link Icon" />
									</div>
									<div  class="input_left_input">
										<input class="input" id="url" type="text" name="url" placeholder="Type or paste a link" onkeyup="input_url_keyup(event)" autofocus />
									</div>

								</div>

								<div class="input_right">

									<button onclick="shorten()" id="shorten_button">SHORTEN</button>

								</div>

							</div>

							<p id="shorten_error">&nbsp;</p>

						</div>

					</div>

				</div>

				<div id="recently_urls" class="recently_urls_margin">

					<div class="main_width">
						<h3>Your recently shortened URLs</h3>
						<div class="recently_urls" id="urls">

							<?php

								$show_max = 3;
								include_once($config['recent_urls']);

							?>

						</div>

					</div>

				</div>

				<?php
					if($user == null){
						echo '

							<div id="register">
								<span>Need to manage and customize all your URLs?</span>
								<button onclick="location.href = \'/register/\'">Join Now for Free</button>
							</div>

						';
					}		
				?>
				<div></div>

			</div>

			<div class="push" <?php if($user==null){echo 'style="height:120px;"';} ?>></div>

		</div>

		<div id="footer" <?php if($user!=null){echo 'class="footer_green"';} ?>>
			<div class="main_width">
				<?php include_once($config['footer']); ?>
			</div>
		</div>

		<script type="text/javascript" src="/js/script.js"></script>

	</body>

</html>