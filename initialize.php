<?php ob_start(); session_start(); 

/* Testing code only */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
/* */

define("SITE_ROOT", $_SERVER["DOCUMENT_ROOT"]."/WebWork/Artemis/Hub/Website");
define("REDIRECT_ROOT", "/WebWork/Artemis/Hub/Website");

/*
define("SITE_ROOT", $_SERVER["DOCUMENT_ROOT"]."/dev/Website");
define("REDIRECT_ROOT", "/dev/Website");
*/

date_default_timezone_set("America/New_York");

//require_once("system/payment/Stripe.php");
	
function __autoload($class_name)
{
	$abs_dir = realpath(dirname(__FILE__));
	if(file_exists($abs_dir."/system/classes/".$class_name.".php"))
	{
		require_once($abs_dir."/system/classes/".$class_name.".php"); 	
	}
	else
	{
		echo "Class '<strong>".$class_name."</strong>' not found.";	
	}
}

require_once(SITE_ROOT."/functions.php");

/*	TEST DATABASE CALLS 
	
$Database = new Database;

$Database->insert("users", "ss", array(
	"email" => "email@yahoo.c'& LIMIT 1'<strong>om</strong>",
	"password" => "password123"
));


$get_user_info = $Database->select("users", "ss", array(
	"email" => "email@yahoo.c'& LIMIT 1'<strong>om</strong>",
	"RELATION" => "AND",
	"password" => "password123"
));

foreach($get_user_info as $row)
{
	print_r($row);//$row["password"];	
}
*/
?>