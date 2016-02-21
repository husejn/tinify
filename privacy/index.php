<?php

require_once('../config.php');

?>

<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?> - Privacy Policy</title>
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

						<div class="vertical_align privacy">

							<h1>Privacy Policy</h1>

							
							<p style="margin-top:50px;">Enter the privacy policy here:</p>

							<ul>
								<li>Title 1.</li>
								<li>Title 2.</li>
							</ul>

							<h3 style="margin-top:50px;">Anther Paragraph</h3>

							<p style="margin-top:25px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>

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








