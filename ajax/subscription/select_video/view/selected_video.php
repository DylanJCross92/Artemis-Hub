<?php require_once('../../../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	$video_id = $_POST["video_id"];
	
	echo Views::selected_video($video_id);
?>