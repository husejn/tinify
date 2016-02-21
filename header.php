<div id="header_left">
	<a href="/"><img src="/images/logo.png" width="141" alt="tinify.co" /></a>
</div>
<div id="header_right">
<?php
	if(isset($user)){
		echo "
	<a href='/account/' title='".printHTML($user -> name)."'>". substr(printHTML($user -> name), 0, 12) . "</a>
	<a href='/urls/'>URLs</a>
	<a href='/login/logout.php'>Logout</a>"; 
	} else{
		echo "<a href='/login/'>Log in</a>";
		echo "<a href='/signup/'><button>Sign Up</button></a>";
	}
	?>
	
</div>