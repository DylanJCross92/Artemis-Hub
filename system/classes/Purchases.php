<?php 

class Purchases {
	
	protected $_db;
	
	public function __construct(){
		
		$this->_db = new DatabaseAccessor;
	}
	
	public function add_purchase($purchase_type, $purchase_id, $amount) {
		
		$user_id = User::user_id();
		
		$db_safe_user_id = $this->_db->sanitize_db($user_id);
		$db_safe_purchase_type = $this->_db->sanitize_db($purchase_type);
		$db_safe_purchase_id = $this->_db->sanitize_db($purchase_id);
		$db_safe_amount = $this->_db->sanitize_db($amount);
		$db_safe_date = $this->_db->sanitize_db(time());
		
		$query = $this->_db->query("INSERT INTO user_purchases SET `user_id` = $db_safe_user_id, `purchase_type` = $db_safe_purchase_type, `purchase_id` = $db_safe_purchase_id, `amount` = $db_safe_amount, `date` = $db_safe_date");
		
		return $query ? true : false;
	}
	
	public function verify_purchase($user_id = false, $purchase_type, $purchase_id) {
		
		$user_id = $user_id ? $user_id : User::user_id();
		
		$db_safe_user_id = $this->_db->sanitize_db($user_id);
		$db_safe_purchase_type = $this->_db->sanitize_db($purchase_type);
		$db_safe_purchase_id = $this->_db->sanitize_db($purchase_id);
		
		$query = $this->_db->query("SELECT user_purchases.* FROM user_purchases WHERE `user_id` = $db_safe_user_id AND `purchase_type` = $db_safe_purchase_type AND `purchase_id` = $db_safe_purchase_id");
		
		return $query->num_rows > 0 ? true : false;
	}
	
	public function get_single_purchases($user_id = false) {
		
		$user_id = $user_id ? $user_id : User::user_id();
		
		$db_safe_user_id = $this->_db->sanitize_db($user_id);
		$db_safe_purchase_type = $this->_db->sanitize_db("single_purchase");
		
		$query = $this->_db->query("SELECT user_purchases.* FROM user_purchases WHERE `user_id` = $db_safe_user_id AND `purchase_type` = $db_safe_purchase_type");
		
		if($query->num_rows > 0)
		{
			$array_ids = array();
			
			foreach($query as $row)
			{
				array_push($array_ids, $row["purchase_id"]);	
			}
			
			return $array_ids;
		}
		
		return false;
	}
	
	
		
	
}


?>