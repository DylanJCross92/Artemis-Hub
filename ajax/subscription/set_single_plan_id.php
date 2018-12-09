<?php require_once('../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	$plan_id = $_POST["plan_id"];
	//$video_id = $_POST["video_id"];
	
	if(isset($plan_id))// && isset($video_id))
	{
		
		if(Session::set_session("plan_id", base64_encode($plan_id)))
		{
			echo ajax_success();
		}
	}
	
	
?>