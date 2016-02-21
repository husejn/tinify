<?php
require_once('../config.php');
?>

<!DOCTYPE html>
<html>

	<head>
		<title><?php echo SITE_NAME; ?> - Terms</title>
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

							<h1>Terms of Use</h1>

							<p style="margin-top:50px;">Enter the terms of user here.</p>
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
							

							<h3 style="margin-top:80px;">Title</h3>

							<p style="margin-top:25px;">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
							<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
							  
							<h3 style="margin-top:80px">Contact Us</h3>

							<p style="margin-top:25px;text-align:center;font-weight:bold">If you have any questions about these Terms, please <a href="/contact/">contact us</a>.</p>

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








