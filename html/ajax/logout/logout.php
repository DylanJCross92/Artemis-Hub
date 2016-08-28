<?php require_once("../../../initialize.php");?>
<?php 
if(User::is_logged_in())
{
	$User = new User;
	$User->logout_user();
}
?>