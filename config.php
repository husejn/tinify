<?php

/** This is the main config file, and it is included in every php file that needs access to the data provided here.
 *  Please update the data before running your own instance.
 *
*/


/** Included files location */
$document_root = $_SERVER['DOCUMENT_ROOT'];

/** Included files location */
$config = array(
	"head" => "$document_root/head.php",
	"header" => "$document_root/header.php",
	"footer" => "$document_root/footer.php",
	"db_connect" => "$document_root/db_connect.php",
	"signup" => "$document_root/signup/signup.php",
	"update" => "$document_root/account/update/update.php",
	"updatepassword" => "$document_root/account/update-password/update-password.php",		
	"session" => "$document_root/login/session.php",
	"login" => "$document_root/login/login.php",
	"contact" => "$document_root/contact/contact.php",
	"short" => "$document_root/api/short.php",
	"urldata" => "$document_root/urls/urldata.php",
	"statistics" => "$document_root/urls/statistics.php",
	"recent_urls" => "$document_root/recent_urls.php"

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
