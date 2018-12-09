<?php require_once('../../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	$goal_id = 1;
	
	echo Views::goal_box($goal_id);
?>