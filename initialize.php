<?php ob_start(); session_start(); 

/* Testing code only */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
/* */

date_default_timezone_set("America/New_York");

require_once("system/payment/Stripe.php");
	
function __autoload($class_name){
	
	if(file_exists($_SERVER["DOCUMENT_ROOT"]."/portal/system/classes/".$class_name.".php"))
	{
		require_once($_SERVER["DOCUMENT_ROOT"]."/portal/system/classes/".$class_name.".php"); 	
	}
}
require_once("functions.php");
?>