<?php

/** This is the main config file, and it is included in every php file that needs access to the data provided here.
 *  Please update the data before running your own instance.
 *
*/


/** Included files location */
$config = array(
	"head" => "/usr/share/nginx/html/head.php",
	"header" => "/usr/share/nginx/html/header.php",
	"footer" => "/usr/share/nginx/html/footer.php",
	"db_connect" => "/usr/share/nginx/html/db_connect.php",
	"signup" => "/usr/share/nginx/html/signup/signup.php",
	"update" => "/usr/share/nginx/html/account/update/update.php",
	"updatepassword" => "/usr/share/nginx/html/account/update-password/update-password.php",		
	"session" => "/usr/share/nginx/html/login/session.php",
	"login" => "/usr/share/nginx/html/login/login.php",
	"contact" => "/usr/share/nginx/html/contact/contact.php",
	"short" => "/usr/share/nginx/html/api/short.php",
	"urldata" => "/usr/share/nginx/html/urls/urldata.php",
	"statistics" => "/usr/share/nginx/html/urls/statistics.php",
	"recent_urls" => "/usr/share/nginx/html/recent_urls.php"

	);


/** Enter your website name
 ** To be used for titles, text etc.
 */
define( 'SITE_NAME', 'YOUR WEBSITE NAME (NOT NECESSARILY DOMAIN) eg.Tinify or Tinify.co' );

/** Enter your REDIRECT domain
 ** To be used for short links.
 */
define( 'REDIRECT_DOMAIN_NAME', 'YOUR SHORT DOMAIN: eg: http://tinify.co' );

/** Enter your full website domain
 ** To be used for full domain, login etc.
 */
define( 'FULL_DOMAIN_NAME', 'YOUR LONG DOMAIN: eg: http://tinify.co' );

/** MySQL database username */
define( 'DB_USER', 'your db username' );

/** MySQL database password */
define( 'DB_PASS', 'your db password' );

/** The name of the database */
define( 'DB_NAME', 'tinify' );

/** MySQL hostname.
 ** If you are not using a standard port, specify it like 'hostname:port' */
define( 'DB_HOST', 'localhost' );

/** URL shortening hash length: dafault 3, enter 2-6 */
define( 'HASH_LENGTH', 5 );

/** recaptcha public key for your domain
 *  
 */
define( 'RECAP_PUBLIC', '' );

/** recaptcha pivate key for your domain
 *  
 */
define( 'RECAP_PRIVATE', '' );


/** Function used to print html to avoid possible XSS */
function printHTML($string){
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

?>
