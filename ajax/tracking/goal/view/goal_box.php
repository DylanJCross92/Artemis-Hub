<?php require_once('../../../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	$goal_id = $_POST["goal_id"];
	
	echo Views::goal_box($goal_id);
?>