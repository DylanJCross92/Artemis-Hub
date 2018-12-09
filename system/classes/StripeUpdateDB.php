<?php

class StripeUpdateDB {
	
	protected $_db;
	protected $DBStripeAccessor;
	
	public function __construct(){
		
		Stripe::setApiKey("sk_test_pULQi8OSSzHaBe0D3gHYjyJ4"); 
		$this->_db = new DatabaseAccessor;
		$this->DBStripeAccessor = new DBStripeAccessor;
	}
	
	/*
		** Update Stripe Customers
	*/
	public function update_stripe_customer($data, $webhook = false){
		
		$data = $webhook ? $data->object : $data;
		
		$db_safe_customer_id = $this->_db->sanitize_db($data->id);
		$db_safe_email = $this->_db->sanitize_db($data->email);
		$db_safe_account_balance = $this->_db->sanitize_db($data->account_balance);
		$db_safe_description = $this->_db->sanitize_db($data->description);
		$db_safe_default_card = $this->_db->sanitize_db($data->default_card);
		$db_safe_created = $this->_db->sanitize_db($data->created);
		$db_safe_last_updated = $this->_db->sanitize_db(time());
		
		$check_customer_id = $this->_db->query("SELECT stripe_customers.customer_id FROM stripe_customers WHERE customer_id = $db_safe_customer_id")->num_rows;
		
		if(!$check_customer_id)
		{
			$sql = "INSERT INTO stripe_customers 
					SET `customer_id` = $db_safe_customer_id, 
						`email` = $db_safe_email, 
						`account_balance` = $db_safe_account_balance, 
						`description` = $db_safe_description, 
						`default_card` = $db_safe_default_card, 
						`created` = $db_safe_created, 
						`last_updated` = $db_safe_last_updated";
		}
		else
		{
			$sql = "UPDATE stripe_customers 
					SET `email` = $db_safe_email, 
						`account_balance` = $db_safe_account_balance, 
						`description` = $db_safe_description, 
						`default_card` = $db_safe_default_card, 
						`created` = $db_safe_created, 
						`last_updated` = $db_safe_last_updated 
					WHERE `customer_id` = $db_safe_customer_id";
		}
	
		return $this->_db->query($sql);
	}
	
	/*
		** Update Stripe Cards
	*/
	public function update_stripe_cards($data, $webhook = false){
		
		$data = $webhook ? $data->object : $data;
		
		$db_safe_card_id = $this->_db->sanitize_db($data->id);
		$db_safe_customer_id = $this->_db->sanitize_db($data->customer);
		$db_safe_last4 = $this->_db->sanitize_db($data->last4);
		$db_safe_brand = $this->_db->sanitize_db($data->brand);
		$db_safe_funding = $this->_db->sanitize_db($data->funding);
		$db_safe_exp_month = $this->_db->sanitize_db($data->exp_month);
		$db_safe_exp_year = $this->_db->sanitize_db($data->exp_year);
		$db_safe_name = $this->_db->sanitize_db($data->name);
		$db_safe_address_line1 = $this->_db->sanitize_db($data->address_line1);
		$db_safe_address_line2 = $this->_db->sanitize_db($data->address_line2);
		$db_safe_address_city = $this->_db->sanitize_db($data->address_city);
		$db_safe_address_state = $this->_db->sanitize_db($data->address_state);
		$db_safe_address_zip = $this->_db->sanitize_db($data->address_zip);
		$db_safe_address_country = $this->_db->sanitize_db($data->address_country);
		$db_safe_date = $this->_db->sanitize_db(time());
		$db_safe_last_updated = $this->_db->sanitize_db(time());
		
		$check_card_id = $this->_db->query("SELECT stripe_cards.card_id FROM stripe_cards WHERE card_id = $db_safe_card_id")->num_rows;
		
		if(!$check_card_id)
		{
			$sql = "INSERT INTO stripe_cards 
					SET `card_id` = $db_safe_card_id, 
						`customer_id` = $db_safe_customer_id, 
						`last4` = $db_safe_last4, 
						`brand` = $db_safe_brand,
						`funding` = $db_safe_funding,
						`exp_month` = $db_safe_exp_month,
						`exp_year` = $db_safe_exp_year,
						`name` = $db_safe_name,
						`address_line1` = $db_safe_address_line1,
						`address_line2` = $db_safe_address_line2,
						`address_city` = $db_safe_address_city,
						`address_state` = $db_safe_address_state,
						`address_zip` = $db_safe_address_zip,
						`address_country` = $db_safe_address_country,
						`date` = $db_safe_date,
						`last_updated` = $db_safe_last_updated";
		}
		else
		{
			$sql = "UPDATE stripe_cards 
					SET `customer_id` = $db_safe_customer_id, 
						`last4` = $db_safe_last4, 
						`brand` = $db_safe_brand,
						`funding` = $db_safe_funding,
						`exp_month` = $db_safe_exp_month,
						`exp_year` = $db_safe_exp_year,
						`name` = $db_safe_name,
						`address_line1` = $db_safe_address_line1,
						`address_line2` = $db_safe_address_line2,
						`address_city` = $db_safe_address_city,
						`address_state` = $db_safe_address_state,
						`address_zip` = $db_safe_address_zip,
						`address_country` = $db_safe_address_country,
						`last_updated` = $db_safe_last_updated
					WHERE `card_id` = $db_safe_card_id";
		}
		
		
		$this->DBStripeAccessor->update_customer_card_id($data->customer, $data->id);
		
		return $this->_db->query($sql);
	}
	
	/*
		** Update Stripe Subscriptions
	*/
	public function update_stripe_subscription($data, $webhook = false){
		
		$data = $webhook ? $data->object : $data;
		
		$db_safe_subscription_id = $this->_db->sanitize_db($data->id);
		$db_safe_customer_id = $this->_db->sanitize_db($data->customer);
		$db_safe_plan_id = $this->_db->sanitize_db($data->plan->id);
		$db_safe_start = $this->_db->sanitize_db($data->start);
		$db_safe_status = $this->_db->sanitize_db($data->status);
		$db_safe_cancel_at_period_end = $this->_db->sanitize_db($data->cancel_at_period_end);
		$db_safe_current_period_start = $this->_db->sanitize_db($data->current_period_start);
		$db_safe_current_period_end = $this->_db->sanitize_db($data->current_period_end);
		$db_safe_ended_at = $this->_db->sanitize_db($data->ended_at);
		$db_safe_trial_start = $this->_db->sanitize_db($data->trial_start);
		$db_safe_trial_end = $this->_db->sanitize_db($data->trial_end);
		$db_safe_canceled_at = $this->_db->sanitize_db($data->canceled_at);
		$db_safe_last_updated = $this->_db->sanitize_db(time());
		
		$check_subscription_id = $this->_db->query("SELECT stripe_subscriptions.subscription_id FROM stripe_subscriptions WHERE subscription_id = $db_safe_subscription_id")->num_rows;
		
		if(!$check_subscription_id)
		{
			$sql = "INSERT INTO stripe_subscriptions 
					SET `subscription_id` = $db_safe_subscription_id, 
						`customer_id` = $db_safe_customer_id, 
						`plan_id` = $db_safe_plan_id, 
						`start` = $db_safe_start, 
						`status` = $db_safe_status,
						`cancel_at_period_end` = $db_safe_cancel_at_period_end,
						`current_period_start` = $db_safe_current_period_start, 
						`current_period_end` = $db_safe_current_period_end, 
						`ended_at` = $db_safe_ended_at,
						`trial_start` = $db_safe_trial_start,
						`trial_end` = $db_safe_trial_end,
						`canceled_at` = $db_safe_canceled_at,
						`last_updated` = $db_safe_last_updated";
		}
		else
		{
			$sql = "UPDATE stripe_subscriptions 
					SET `plan_id` = $db_safe_plan_id, 
						`customer_id` = $db_safe_customer_id,
						`start` = $db_safe_start, 
						`status` = $db_safe_status, 
						`cancel_at_period_end` = $db_safe_cancel_at_period_end,
						`current_period_start` = $db_safe_current_period_start, 
						`current_period_end` = $db_safe_current_period_end, 
						`ended_at` = $db_safe_ended_at,
						`trial_start` = $db_safe_trial_start,
						`trial_end` = $db_safe_trial_end,
						`canceled_at` = $db_safe_canceled_at,
						`last_updated` = $db_safe_last_updated
					WHERE `subscription_id` = $db_safe_subscription_id";
		}
		
		$this->DBStripeAccessor->update_customer_subscription_id($data->customer, $data->id);
		$this->DBStripeAccessor->update_customer_plan_id($data->customer, $data->plan->id);
		
		return $this->_db->query($sql);
	}
	
	/*
		** Update Stripe Plans
	*/
	public function update_stripe_plans($data, $webhook = false){
		
		$data = $webhook ? $data->object : $data;
		
		$db_safe_plan_id = $this->_db->sanitize_db($data->id);
		$db_safe_name = $this->_db->sanitize_db($data->name);
		$db_safe_interval = $this->_db->sanitize_db($data->interval);
		$db_safe_interval_count = $this->_db->sanitize_db($data->interval_count);
		$db_safe_trial_period_days = $this->_db->sanitize_db($data->trial_period_days);
		$db_safe_object = $this->_db->sanitize_db($data->object);
		$db_safe_currency = $this->_db->sanitize_db($data->currency);
		$db_safe_amount = $this->_db->sanitize_db($data->amount);
		$db_safe_created = $this->_db->sanitize_db($data->created);
		$db_safe_last_updated = $this->_db->sanitize_db(time());
		
		$check_plan_id = $this->_db->query("SELECT stripe_plans.plan_id FROM stripe_plans WHERE plan_id = $db_safe_plan_id")->num_rows;
		
		if(!$check_plan_id)
		{
			$sql = "INSERT INTO stripe_plans 
					SET `plan_id` = $db_safe_plan_id, 
						`name` = $db_safe_name, 
						`interval` = $db_safe_interval, 
						`interval_count` = $db_safe_interval_count, 
						`trial_period_days` = $db_safe_trial_period_days,
						`object` = $db_safe_object, 
						`currency` = $db_safe_currency, 
						`amount` = $db_safe_amount,
						`created` = $db_safe_created,
						`last_updated` = $db_safe_last_updated";
		}
		else
		{
			$sql = "UPDATE stripe_subscriptions 
					SET `name` = $db_safe_name, 
						`interval` = $db_safe_interval, 
						`interval_count` = $db_safe_interval_count, 
						`trial_period_days` = $db_safe_trial_period_days,
						`object` = $db_safe_object, 
						`currency` = $db_safe_currency, 
						`amount` = $db_safe_amount,
						`created` = $db_safe_created,
						`last_updated` = $db_safe_last_updated
					WHERE `plan_id` = $db_safe_plan_id";
		}
		
		return $this->_db->query($sql);
	}
	
	/*
		** Update Stripe Invoices
	*/
	public function update_stripe_invoices($data, $webhook = false){
		
		$data = $webhook ? $data->object : $data;
		
		$db_safe_invoice_id = $this->_db->sanitize_db($data->id);
		$db_safe_customer_id = $this->_db->sanitize_db($data->customer);
		$db_safe_subscription_id = $this->_db->sanitize_db($data->subscription);
		$db_safe_charge_id = $this->_db->sanitize_db($data->charge);
		$db_safe_date = $this->_db->sanitize_db($data->date);
		$db_safe_last_updated = $this->_db->sanitize_db(time());
		
		$check_invoice_id = $this->_db->query("SELECT stripe_invoices.invoice_id FROM stripe_invoices WHERE invoice_id = $db_safe_invoice_id")->num_rows;
		
		if(!$check_invoice_id)
		{
			$sql = "INSERT INTO stripe_invoices 
					SET `invoice_id` = $db_safe_invoice_id, 
						`customer_id` = $db_safe_customer_id, 
						`subscription_id` = $db_safe_subscription_id, 
						`charge_id` = $db_safe_charge_id, 
						`date` = $db_safe_date,
						`last_updated` = $db_safe_last_updated";
		}
		else
		{
			$sql = "UPDATE stripe_invoices 
					SET `customer_id` = $db_safe_customer_id, 
						`subscription_id` = $db_safe_subscription_id, 
						`charge_id` = $db_safe_charge_id, 
						`date` = $db_safe_date,
						`last_updated` = $db_safe_last_updated
					WHERE `invoice_id` = $db_safe_invoice_id";
		}
		
		return $this->_db->query($sql);
	}
		
}












































?>