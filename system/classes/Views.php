<?php 
	
class Views {
	
	public static function log_label($log_type_id) {
		
		$user_id = User::user_id();
		$css_class = "";
		
		$_db = new DatabaseAccessor;
		
		if($log_type_id == 1)
		{
			if(Dashboard::get_log($user_id, $log_type_id))
			{
				$first_log = Dashboard::get_log($user_id, $log_type_id, "first");
				$first_log_value = $first_log["value"];
				
				$last_log = Dashboard::get_log($user_id, $log_type_id, "last");
				$last_log_value = $last_log["value"];
				
				$weight_diff = $first_log_value - $last_log_value;
				
				$weight_change = change($first_log_value - $last_log_value);
				
				$css_class = "";
										
				if($weight_change == "lost")
				{
					$css_class = "positive icon-down-big";
					
					if($weight_diff == 1)
					{
						$message = abs($weight_diff)." pound lost";
					}
					else
					{
						$message = abs($weight_diff)." pounds lost";
					}
				}
				else if($weight_change == "gained")
				{
					$css_class = "negative icon-up-big";
					
					if(abs($weight_diff) == 1)
					{
						$message = abs($weight_diff)." pound gained";
					}
					else
					{
						$message = abs($weight_diff)." pounds gained";
					}
				}
				else if($weight_change == "neutral")
				{
					$message = "No change";
				}
			}
			else
			{
				$message = "Start logging your weight";	
			}
			
		}
		else if($log_type_id == 2)
		{
			if(Dashboard::get_log($user_id, $log_type_id))
			{
				$db_safe_log_type_id = $_db->sanitize_db($log_type_id);
				$db_safe_user_id = $_db->sanitize_db($user_id);
				$db_safe_time = $_db->sanitize_db(time());
				
				$num_meals = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_log_type_id")->num_rows;
				$num_healthy = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_log_type_id AND `value` = '0'")->num_rows;
				
				$healthy_percentage = decto_percent($num_healthy/$num_meals);
				
				$css_class = $healthy_percentage >= "60" ? "positive" : "negative";
				
				$message = $healthy_percentage."% healthy days recorded";
			}
			else
			{
				$message = "Start logging your daily meals";	
			}
		}
		
		else if($log_type_id == 3)
		{
			if(Dashboard::get_log($user_id, $log_type_id))
			{
				$db_safe_log_type_id = $_db->sanitize_db($log_type_id);
				$db_safe_user_id = $_db->sanitize_db($user_id);
				$db_safe_time = $_db->sanitize_db(time());
				
				$num_exercises = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_log_type_id")->num_rows;
			
				$message = $num_exercises." exercises recorded";
			}
			else
			{
				$message = "Start logging your exercises";	
			}
		}
		
		?>
        <span class="log_label <?php echo $css_class?>"><?php echo $message;?></span> 
        <?php
		
		
	}
	
	public static function goal_box($goal_id = false) {
		
		if($goal_id)
		{
			$goal_data = Dashboard::get_goal($goal_id);
			
			$start_date = $goal_data["date"];
			$end_date = $goal_data["end_date"];
			$goal_type = $goal_data["goal_type_id"];
			$goal_target = $goal_data["target"];
			$goal_type_obj = Dashboard::get_goal_type_obj($goal_type);
			?>
			<li data-id="<?php echo $goal_data["id"]?>">
				<div class="top-bar">
					<div class="modify"><a href="javascript: void(0);" class="icon-pencil edit-goal"></a></div>   
				</div>
				<div class="icon-wrapper">
					<div class="<?php echo $goal_type_obj["icon"];?>"></div>
				</div>
				<div class="label"><?php echo $goal_type_obj["label"];?></div>
                <div class="date icon-clock" title="<?php echo date("l F j, Y, g:ia", $end_date);?>"><?php echo time_elapsed($end_date);?></div>
                
                <?php
					$_db = new DatabaseAccessor;
					
					$user_id = User::user_id();
					
					$db_safe_user_id = $_db->sanitize_db($user_id);
					$db_safe_goal_type = $_db->sanitize_db($goal_type);
					$db_safe_goal_start_date = $_db->sanitize_db($start_date);
					$db_safe_goal_end_date = $_db->sanitize_db($end_date);
					$db_safe_cur_date = $_db->sanitize_db(time());
				
					$query = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_goal_type AND `date` >= $db_safe_goal_start_date ORDER BY `id` ASC LIMIT 1");
					$first_log_data = $query->fetch_assoc();
					$first_log_value = $first_log_data["value"];
					
					$query2 = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_goal_type AND `date` <= $db_safe_goal_end_date ORDER BY `id` DESC LIMIT 1");
					$last_log_data = $query2->fetch_assoc();
					$last_log_value = $last_log_data["value"];
					
					if($first_log_value && $last_log_value)
					{
						if($goal_type == 1)
						{
							$pounds_to_lose = $first_log_value - $goal_target;
							$pounds_lost = $pounds_to_lose - ($last_log_value - $goal_target);
							
							$weight_diff = $pounds_to_lose - ($last_log_value - $goal_target);
							$weight_change = change($weight_diff);
							
							if($weight_change == "lost")
							{
								$css_class = "positive icon-down-big";
								
								if($weight_diff == 1)
								{
									$message = abs($weight_diff).' of <span class="goal" style="font-weight: 400;">'.$pounds_to_lose.'</span> pounds lost';
								}
								else
								{
									$message = abs($weight_diff).' of <span class="goal" style="font-weight: 400;">'.$pounds_to_lose.'</span> pounds lost';
								}
							}
							else if($weight_change == "gained")
							{
								$css_class = "negative icon-up-big";
								
								if(abs($weight_diff) == 1)
								{
									$message = abs($weight_diff)." pound gained";
								}
								else
								{
									$message = abs($weight_diff)." pounds gained";
								}
							}
							else if($weight_change == "neutral")
							{
								$message = "No change";
							}
							
						?>
							<div class="progress-wrapper">
								 <div class="progress-label"><span class="current" style="font-weight: 400;"><?php echo $message?><!--<?php echo $pounds_lost?></span> of <span class="goal" style="font-weight: 400;"><?php echo $pounds_to_lose?></span> pounds lost--></div>
								<div class="progress-bar">
									<div class="progress" style="width: <?php echo decto_percent($pounds_lost/$pounds_to_lose)?>%; background-color: #3FC380;"></div>
								</div>
							</div>
					<?php }
						
						else if($goal_type == 2)
						{
							$num_meals = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_goal_type  AND `date` >= $db_safe_goal_start_date")->num_rows;
							$num_healthy = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_goal_type AND `value` = '0' AND `date` >= $db_safe_goal_start_date")->num_rows;
							
							$healthy_percentage = decto_percent($num_healthy/$num_meals);
							
						?>	
							<div class="progress-wrapper">
								 <div class="progress-label"><span class="current" style="font-weight: 400;"><span class="goal" style="font-weight: 400;"><?php echo $healthy_percentage."%";?></span> healthy days recorded</div>
								<div class="progress-bar">
									<div class="progress" style="width: <?php echo $healthy_percentage?>%; background-color: #3FC380;"></div>
								</div>
							</div>
                        <?php 
						}
						else if($goal_type == 3)
						{
							$num_exercises = $_db->query("SELECT user_log.* FROM user_log WHERE `user_id` = $db_safe_user_id AND `log_type_id` = $db_safe_goal_type AND `date` >= $db_safe_goal_start_date")->num_rows;
						?>
                        <div class="progress-wrapper">
                            <div class="progress-label"><span class="current" style="font-weight: 400;"><span class="goal" style="font-weight: 400;"><?php echo $num_exercises;?></span> exercises recorded</div>
                            <div class="progress-bar">
                                <div class="progress" style="width: <?php echo 0?>%; background-color: #3FC380;"></div>
                            </div>
                        </div>
                        <?php 	
						}
						
						
					}?>
			</li>
			<?php
		}
		else
		{
			return false;	
		}
	}
	
	
	public static function selected_video($videoID) {
		
		
		$title = WPAccessor::get_the_title($videoID);
		$thumbnail = WPAccessor::get_field("thumbnail", $videoID);
		$thumbnailURL = $thumbnail["sizes"]["class-thumbnail-retina"];
		?>
		<div class="video-wrapper">
			<div class="thumbnail-wrapper">
            	<div class="remove-selected-video icon-cancel"></div>
				<div class="video-info"><span class="icon icon-video"></span><span class="title"><?php echo $title?></span></div>
				<img class="thumbnail" src="<?php echo $thumbnailURL?>"/>
			</div>
		</div>
        <?php 
	}
	
		
	
}

?>