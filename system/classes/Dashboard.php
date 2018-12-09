<?php 

class Dashboard {

	//TODO: Eventually store these in the database and retrieve from there
	protected static $goal_type_array = array(array("id" => 1, "label" => "Lose Weight", "icon" => "flaticon-scale16"), array("id" => 2, "label" => "Eat Healthy", "icon" => "flaticon-restaurant23"), array("id" => 3, "label" => "Work Out", "icon" => "flaticon-runer"));

	public static function get_goal_type_obj($goal_type_id){
		
		$key = array_search($goal_type_id, array_column(self::$goal_type_array, 'id'));
		
		return self::$goal_type_array[$key];
	}

	public static function get_goal_type_menu() {
		
		return self::$goal_type_array;	
	}
	
	public static function get_active_goal_by_type($user_id, $goal_type_id) {
		
		$_db = new DatabaseAccessor;
		
		$db_safe_goal_type_id = $_db->sanitize_db($goal_type_id);
		$db_safe_user_id = $_db->sanitize_db($user_id);
		$db_safe_time = $_db->sanitize_db(time());
		
		$query = $_db->query("SELECT user_goals.* FROM user_goals WHERE `user_id` = $db_safe_user_id AND `goal_type_id` = $db_safe_goal_type_id AND `end_date` > $db_safe_time AND `status` = 0 ORDER BY `id` DESC LIMIT 1");
	
		return $query->num_rows > 0 ? $query->fetch_assoc() : false;
	}
	
	public static function is_goal_type_active($user_id, $goal_type_id) {
		
		if($query = self::get_active_goal_by_type($user_id, $goal_type_id))
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	public static function create_goal($user_id, $goal_type_id, $duration, $target) {
		
		$_db = new DatabaseAccessor;
		
		$db_safe_user_id = $_db->sanitize_db((int)$user_id);
		$db_safe_goal_type_id = $_db->sanitize_db((int)$goal_type_id);
		$db_safe_duration = $_db->sanitize_db((int)$duration); //Duration in weeks
		$db_safe_target = $_db->sanitize_db((int)$target);
		$db_safe_end_date = $_db->sanitize_db(time()+ ($duration * 7 * 24 * 60 * 60)); 
		$db_safe_date = $_db->sanitize_db(time());
		
		if($db_safe_user_id && $db_safe_goal_type_id && $db_safe_duration && $db_safe_date)
		{
			$sql = "INSERT INTO user_goals 
					SET `user_id` = $db_safe_user_id,
						`goal_type_id` = $db_safe_goal_type_id, 
						`duration` = $db_safe_duration,
						`target` = $db_safe_target,
						`end_date` = $db_safe_end_date,
						`date` = $db_safe_date";
			
			$_db->query($sql);
			$goal_id = $_db->getConnection()->insert_id;
			
			return isset($goal_id) ? $goal_id : false;
		}
		else
		{
			return false;	
		}
	}
	
	public static function update_goal($goal_id, $user_id, $duration) {
		
		$_db = new DatabaseAccessor;
		
		$cur_goal_data = self::get_goal($goal_id);
		$cur_goal_date = $cur_goal_data["date"];
		
		$goal_elapsed_time = time() - $cur_goal_date;
		
		$db_safe_goal_id = $_db->sanitize_db((int)$goal_id);
		$db_safe_user_id = $_db->sanitize_db((int)$user_id);
		$db_safe_duration = $_db->sanitize_db((int)$duration); //Duration in weeks
		$db_safe_end_date = $_db->sanitize_db((time() - $goal_elapsed_time) + ($duration * 7 * 24 * 60 * 60)); 
		$db_safe_date = $_db->sanitize_db(time());
		
		if($db_safe_goal_id && $db_safe_user_id && $db_safe_duration && $db_safe_end_date && $db_safe_date)
		{
			$sql = "UPDATE user_goals 
					SET `duration` = $db_safe_duration,
						`end_date` = $db_safe_end_date,
						`last_updated` = $db_safe_date
					WHERE `id` = $db_safe_goal_id	 
					AND	   `user_id` = $db_safe_user_id	";
			
			return $_db->query($sql) ? $goal_id : false;
		}
		else
		{
			return false;	
		}
	}
	
	public static function get_goal($goal_id) {
		
		$_db = new DatabaseAccessor;
		
		$db_safe_goal_id = $_db->sanitize_db($goal_id);
		$query = $_db->query("SELECT user_goals.* FROM user_goals WHERE `id` = $db_safe_goal_id LIMIT 1");
	
		return $query->num_rows > 0 ? $query->fetch_assoc() : false;
	}
	
	public static function update_log($user_id, $log_type_id, $value) {
		
		$_db = new DatabaseAccessor;
		
		$db_safe_user_id = $_db->sanitize_db((int)$user_id);
		$db_safe_log_type_id = $_db->sanitize_db((int)$log_type_id);
		$db_safe_value = $_db->sanitize_db((int)$value);
		$db_safe_date = $_db->sanitize_db(time());
		
		if($db_safe_user_id && $db_safe_log_type_id && $db_safe_value && $db_safe_date)
		{
			$sql = "INSERT INTO user_log 
					SET `user_id` = $db_safe_user_id,
						`log_type_id` = $db_safe_log_type_id, 
						`value` = $db_safe_value,
						`date` = $db_safe_date";
			
			$_db->query($sql);
			$log_id = $_db->getConnection()->insert_id;
			
			return isset($log_id) ? $log_id : false;
		}
		else
		{
			return false;	
		}
	}
	
	public static function get_log($user_id, $log_type_id, $result_set = false) {
		
		$_db = new DatabaseAccessor;
		
		$db_safe_user_id = $_db->sanitize_db($user_id);
		$db_safe_log_type_id = $_db->sanitize_db($log_type_id);
		
		if($result_set == "first")
		{
			$query = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_log_type_id ORDER BY `id` ASC LIMIT 1");
		}
		else if($result_set == "last")
		{
			$query = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_log_type_id ORDER BY `id` DESC LIMIT 1");
		}
		else if(is_numeric($result_set))
		{
			$db_safe_log_id = $_db->sanitize_db($result_set);
			
			$query = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `id` = $db_safe_log_id LIMIT 1");
		}
		else
		{
			$query = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_log_type_id ORDER BY `id` ASC");
		}
		
		return $query->num_rows > 0 ? $query->fetch_assoc() : false;
	}
	
}

?>