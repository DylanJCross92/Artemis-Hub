<?php

class Video {
	
	protected $_db;

	public function __construct(){
		$this->_db = new DatabaseAccessor;
	}
	
	public static function add_view($video_id) {
		
		$db = new DatabaseAccessor;	
		$user_id = User::user_id();
		
		$db_safe_user_id = $db->sanitize_db($user_id);
		$db_safe_video_id = $db->sanitize_db($video_id);
		$db_safe_date = $db->sanitize_db(time());
		
		$query = $db->query("INSERT INTO user_video_history SET `user_id` = $db_safe_user_id, `video_id` = $db_safe_video_id, `date` = $db_safe_date");
		
		if($query->affected_rows > 0)
		{
			return true;
		}
		
		return false;
	}
	
	
	public static function recently_viewed($user_id) {
		
		$db = new DatabaseAccessor;	
		$db_safe_user_id = $db->sanitize_db($user_id);
		
		$query = $db->query("SELECT 
								A . *
							FROM
								user_video_history A,
								(SELECT 
									video_id, max(date) maxdate
								FROM
									user_video_history
								WHERE
									`user_id` = $db_safe_user_id
								GROUP BY video_id) B
							where
								A.video_id = B.video_id
									and A.date = B.maxdate
							order by A.date DESC
							limit 3");

		$results = array();
		
		if($query->num_rows > 0)
		{
			while($row = $query->fetch_assoc()) 
			{
				array_push($results, $row["video_id"]);
			}
		}
		
		return count($results) > 0 ? $results : false;
	}
	
}

?>