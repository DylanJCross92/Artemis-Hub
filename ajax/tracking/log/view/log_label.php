<?php require_once('../../../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	$goal_type_id = $_POST["log_type_id"];
	
	Views::log_label($goal_type_id);
?>