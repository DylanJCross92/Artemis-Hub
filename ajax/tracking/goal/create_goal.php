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
	
	$goal_type_id = (int)$_POST["goal_type"]; //Note: 1 = weight_loss, 2 = eat_healthy, 3 = work_out
	$duration = (int)$_POST["goal_duration"]; //duration in number of weeks
	$target = trim($_POST["target"]);
	
	
	//TODO: Don't allow a new goal if there are 3 or more active goals
	
	if(isset($user_id) && isset($goal_type_id) && isset($duration) && !Dashboard::is_goal_type_active($user_id, $goal_type_id))
	{
		
		if($goal_type_id == 1)
		{
			if($target != "")
			{
				if($goal_id = Dashboard::create_goal($user_id, $goal_type_id, $duration, $target))
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
				echo ajax_error(array("unexpected_error" => "Invalid Target"));	
			}
		}
		else
		{
			if($goal_id = Dashboard::create_goal($user_id, $goal_type_id, $duration, $target))
			{
				echo json_encode(array('success' => true, 'goal_id' => $goal_id));	
			}
			else
			{
				//error creating goal
			}	
		}
		
	}
	else
	{	
		//Values not all set
	}
	
?>