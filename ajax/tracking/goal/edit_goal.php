<?php require_once('../../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	if(!User::subscription_active($user_id))
	{
		//error
		die();
	}
	
	$goal_id = $_POST["goal_id"];
	//$new_goal_type_id = $_POST["goal_type"]; //Note: 1 = weight_loss, 2 = eat_healthy, 3 = work_out
	$new_duration = $_POST["goal_duration"]; //duration in number of weeks
	
	//TODO: Don't allow a new goal if there are 3 or more active goals
	
	if(isset($user_id) && isset($goal_id) && isset($new_duration))
	{
		if($goal_id = Dashboard::update_goal($goal_id, $user_id, $new_duration))
		{
			echo json_encode(array('success' => true, 'goal_id' => $goal_id));	
		}
		else
		{
			//error creating goal
		}
	}
	else
	{	
		//Values not all set
	}
	
?>