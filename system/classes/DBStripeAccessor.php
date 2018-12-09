<?php

class DBStripeAccessor {
	
	protected $_db;
	
	public function __construct(){
		$this->_db = new DatabaseAccessor;
	}
	
	/*
		Update Stripe data from Stripe to local DB
	*/
	public function sync_stripe_info($customer_id) {
		try
		{
			$StripeAccessor = new StripeAccessor;
			$StripeUpdateDB = new StripeUpdateDB;
			
			$customer_info_data = $StripeAccessor->get_customer_info($customer_id);
			$customer_card_data = $StripeAccessor->get_customer_card_info($customer_id);
			$customer_subscription_data = $StripeAccessor->get_customer_subscription($customer_id);
			
			if($customer_info_data)
			{
				$StripeUpdateDB->update_stripe_customer($customer_info_data);
			}
			if($customer_card_data)
			{
				$StripeUpdateDB->update_stripe_cards($customer_card_data);
			}
			if($customer_subscription_data)
			{
				$StripeUpdateDB->update_stripe_subscription($customer_subscription_data);
			}
			
			return true;
		}
		catch(Exception $e)
		{
			return false;	
		}
	}
	
	
	/*
		Validate a plan using the plan ID
	*/
	static public function validate_plan($planID) {
		
		$DBStripeAccessor = new DBStripeAccessor();
		return $DBStripeAccessor->get_plan($planID);
	}
	
	/*
		Update OR Insert a customer ID to the subscription info table
	*/
	public function update_customer_id($user_id, $customer_id) {
		
		$db_safe_user_id = $this->_db->sanitize_db($user_id);
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$db_safe_date = $this->_db->sanitize_db(time());
		
		$subscription_exists = $this->get_subscription_info($user_id);
		
		if(!$subscription_exists)
		{
			$sql = "INSERT INTO user_subscription_info SET `user_id` = $db_safe_user_id, `customer_id` = $db_safe_customer_id, `last_updated` = $db_safe_date, date = $db_safe_date";
		}
		else
		{
			$sql = "UPDATE user_subscription_info SET `customer_id` = $db_safe_customer_id, `last_updated` = $db_safe_date WHERE user_id = $db_safe_user_id";
		}
		
		return $this->_db->query($sql) ? true : false;
	}
	
	/*
		Update a customers card ID in the subscription info table
	*/
	public function update_customer_card_id($customer_id, $card_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$db_safe_card_id = $this->_db->sanitize_db($card_id);
		
		$sql = "UPDATE user_subscription_info SET `card_id` = $db_safe_card_id WHERE customer_id = $db_safe_customer_id";
		
		return $this->_db->query($sql) ? true : false;
	}
	
	/*
		Update a customers plan ID in the subscription info table
	*/
	public function update_customer_plan_id($customer_id, $plan_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$db_safe_plan_id = $this->_db->sanitize_db($plan_id);
		
		$sql = "UPDATE user_subscription_info SET `plan_id` = $db_safe_plan_id WHERE customer_id = $db_safe_customer_id";
		
		return $this->_db->query($sql) ? true : false;
	}
	
	/*
		Update a customers coupon ID in the subscription info table
	*/
	public function update_customer_coupon_id($customer_id, $coupon_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$db_safe_coupon_id = $this->_db->sanitize_db($coupon_id);
		
		$sql = "UPDATE user_subscription_info SET `coupon_id` = $db_safe_coupon_id WHERE customer_id = $db_safe_customer_id";
		
		return $this->_db->query($sql) ? true : false;
	}
	
	/*
		Update a customers subscription ID in the subscription info table
	*/
	public function update_customer_subscription_id($customer_id, $subscription_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$db_safe_subscription_id = $this->_db->sanitize_db($subscription_id);
		
		$sql = "UPDATE user_subscription_info SET `subscription_id` = $db_safe_subscription_id WHERE customer_id = $db_safe_customer_id";
		
		return $this->_db->query($sql) ? true : false;
	}
	
	/*
		Get the customers ID from a users ID
	*/
	public function get_customer_id($user_id) {
			
		$db_safe_user_id = $this->_db->sanitize_db($user_id);
		$query = $this->_db->query("SELECT user_subscription_info.* FROM user_subscription_info WHERE `user_id` = $db_safe_user_id LIMIT 1");
		
		$customer_id = false;
		if(isset($query->num_rows) > 0)
		{
			$row = $query->fetch_assoc();
			$customer_id = $row["customer_id"];
		}
		
		return $customer_id ? $customer_id : false;
	}
	
	/*
		Get the customers subscription info
	*/
	public function get_subscription_info($customer_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$query = $this->_db->query("SELECT user_subscription_info.* FROM user_subscription_info WHERE `customer_id` = $db_safe_customer_id LIMIT 1");
		
		return $query->num_rows > 0 ? $query->fetch_assoc() : false;
	}
	
	/*
		Get the customers coupon ID
	*/
	public function get_customer_coupon_id($customer_id) {
		
		$subscription_info = $this->get_subscription_info($customer_id);
		$coupon_id = $subscription_info["coupon_id"];
		
		return isset($coupon_id) ? $coupon_id : false;
	}
	
	/*
		Get the customers plan ID
	*/
	public function get_customer_plan_id($customer_id) {
		
		$subscription_info = $this->get_subscription_info($customer_id);
		$plan_id = $subscription_info["plan_id"];
		
		return isset($plan_id) ? $plan_id : false;
	}
	
	/*
		Get the customers subscription ID
	*/
	public function get_customer_subscription_id($customer_id) {
		
		$subscription_info = $this->get_subscription_info($customer_id);
		$subscription_id = $subscription_info["subscription_id"];
		
		return isset($subscription_id) ? $subscription_id : false;
	}
	
	/*
		Get the customers card ID
	*/
	public function get_customer_card_id($customer_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($customer_id);
		$query = $this->_db->query("SELECT stripe_customers.* FROM stripe_customers WHERE `customer_id` = $db_safe_customer_id LIMIT 1");
		
		$card_id = false;
		if(isset($query->num_rows) > 0)
		{
			$row = $query->fetch_assoc();
			$card_id = $row["default_card"];
		}
		
		return $card_id ? $card_id : false;
		
	}
	
	/*
		Get the customers invoice ID
	*/
	public function get_invoice_id($subscription_id) {
		
		$db_safe_subscription_id = $this->_db->sanitize_db($subscription_id);
		$query = $this->_db->query("SELECT stripe_invoices.* FROM stripe_invoices WHERE `subscription_id` = $db_safe_subscription_id LIMIT 1");
		
		$invoice_id = false;
		
		if(isset($query->num_rows) > 0)
		{
			$row = $query->fetch_assoc();
			$invoice_id = $row["invoice_id"];
		}
		
		return isset($query->num_rows) > 0 ? $invoice_id : false;
	}
	
	/*
		Get a plans info
	*/
	public function get_plan($planID) {
		
		$db_safe_planID = $this->_db->sanitize_db($planID);
		$query = $this->_db->query("SELECT * FROM stripe_plans WHERE `plan_id` = $db_safe_planID");
		
		return $query->num_rows > 0 ? $query->fetch_assoc() : false;
	}
	
	/*
		Get the status of a subscription
	*/
	public function get_subscription_status($user_id) {
		
		$db_safe_customer_id = $this->_db->sanitize_db($this->get_customer_id($user_id));
		$query = $this->_db->query("SELECT stripe_subscriptions.* FROM stripe_subscriptions WHERE `customer_id` = $db_safe_customer_id ORDER BY id DESC LIMIT 1");
		
		$subscription_status = false;
		if(isset($query->num_rows) > 0)
		{
			$row = $query->fetch_assoc();
			$subscription_status = $row["status"];
		}
		
		return isset($subscription_status) ? $subscription_status : "canceled";
	}
	
}


?>