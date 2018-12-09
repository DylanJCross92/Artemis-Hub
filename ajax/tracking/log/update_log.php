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
	
	$valid_log_types = array("1", "2", "3");
	$log_type_id = $_POST["log_type_id"]; //Note: 1 = weight_loss, 2 = eat_healthy, 3 = work_out
	$value = $_POST["value"];
	
	if(isset($user_id) && isset($log_type_id) && in_array($log_type_id, $valid_log_types) && is_numeric($log_type_id) && isset($value) && is_numeric($value))
	{
		if($log_id = Dashboard::update_log($user_id, $log_type_id, $value))
		{
			
			//TODO: Remove code if using new design
			$first_log = Dashboard::get_log($user_id, $log_type_id, "first");
			$first_log_value = $first_log["value"];
			
			$last_log = Dashboard::get_log($user_id, $log_type_id, "last");
			$last_log_value = $last_log["value"];
			
			$goal_data = Dashboard::get_active_goal_by_type($user_id, $log_type_id);
			
			echo json_encode(array('success' => true, 'log_type_id' => $log_type_id, 'goal_id' => $goal_data["id"]));	
		}
		else
		{
			//error updating log
		}
	}
	else
	{	
		//Values not all set
	}
	
?>