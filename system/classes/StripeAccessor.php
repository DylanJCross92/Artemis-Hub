<?php

class StripeAccessor {
	
	//Set API key for API calls to use
	public function __construct()
  	{
    	Stripe::setApiKey("sk_test_pULQi8OSSzHaBe0D3gHYjyJ4"); 
  	}
	
	//Create a customer and add subscription at once
	public function create_customer($params) {
		
		try 
		{
			return Stripe_Customer::create($params);
		}
		catch (Exception $e) 
		{
			return false;
		}
	}
	
	public function get_customer_info($customer_id) {
		
		try
		{
			return Stripe_Customer::retrieve($customer_id); 
		}
		catch(Exception $e)
		{
			throw new Exception(ErrorMessage::$SYSTEM_UNEXPECTED_ERROR);
		}
			
	}
	
	
	public function get_coupon($coupon_id) {
		
		try
		{
			return Stripe_Coupon::retrieve($coupon_id);
		}
		catch(Exception $e)
		{
			throw new Exception(ErrorMessage::$SYSTEM_INVALID_COUPON);
		}	
	}
	
	//Create a single one time charge
	public function create_single_charge($params) {
		try
		{
			Stripe_Charge::create($params);	
		}
		catch(Stripe_CardError $e)
		{
			$e_json = $e->getJsonBody();
   			$error = $e_json['error'];
			
			throw new Exception($error);
		}
		catch(Exception $e)
		{
			throw new Exception($e);
			//throw new Exception(ErrorMessage::$SYSTEM_UNEXPECTED_ERROR);
		}
	}
	
	//Create a new subscription with the plan id and customer id
	public function create_subscription($customer_id, $plan_id) {
		
		try 
		{
			$cu = $this->get_customer_info($customer_id);
			return $cu->subscriptions->create(array("plan" => $plan_id));	
		}
		catch (Exception $e) 
		{
			throw new Exception(ErrorMessage::$SYSTEM_ERROR_CREATING_SUBSCRIPTION);
		}
		
	}
	
	//Update an existing subscription
	public function update_subscription($planID, $cusomter_id, $subscription_id) {
		
		try 
		{
			$cu = $this->get_customer_info($customer_id); 
			$subscription = $cu->subscriptions->retrieve($subscription_id); 
			$subscription->plan = $planID; 
			$subscription->save();
		}
		catch (Exception $e) 
		{
			return false;
		}
			
	}
	
	public function cancel_subscription($customer_id, $subscription_id) {
		
		try
		{
			$cu = Stripe_Customer::retrieve($customer_id);
			return $cu->subscriptions->retrieve($subscription_id)->cancel(array('at_period_end' => false));
		}
		catch (Exception $e) 
		{
			throw new Exception(ErrorMessage::$SYSTEM_ERROR_CANCELING_SUBSCRIPTION);
		}
	}
	
	//Retrieve plan by plan id and return all information
	public function retrieve_plan($planID) {
		
		try 
		{
			return $response = Stripe_Plan::retrieve($planID);
		}
		catch (Exception $e) 
		{
			return false;
		}
	}
	
	//Validate that the plan exists
	public function validate_plan($planID) {
		
		try 
		{
			Stripe_Plan::retrieve($planID);
			return true;
		}
		catch (Exception $e) 
		{
			return false;
		}
	}
	
	//Retrieve a subscripted by the customer
	public function get_customer_subscription($customer_id) {
		
		try
		{
			$DBStripeAccessor = new DBStripeAccessor;
			$subscription_id = $DBStripeAccessor->get_customer_subscription_id($customer_id);
			$cu = $this->get_customer_info($customer_id); 
			return $cu->subscriptions->retrieve($subscription_id);
		}
		catch(Exception $e) 
		{
			return false;
		}
	}
	
	
	public function update_customer_card($customer_id, $card_token) {
		
		$StripeUpdateDB = new StripeUpdateDB;
		
		try
		{
			$cu = $this->get_customer_info($customer_id); 
			$card_data = $cu->cards->create(array("card" => $card_token));
			if($card_data)
			{
				$StripeUpdateDB->update_stripe_cards($card_data);
				return true;
			}
			else
			{
				throw new Exception(ErrorMessage::$SYSTEM_UNEXPECTED_ERROR);
			}
			
		}
		catch(Stripe_CardError $e)
		{
			throw new Exception($e->getMessage());
		}
		catch(Exception $e)
		{
			throw new Exception(ErrorMessage::$SYSTEM_UNEXPECTED_ERROR);
		}
	}
	
	
	public function get_customer_card($customer_id) {
		
		$DBStripeAccessor = new DBStripeAccessor;
		$card_id = $DBStripeAccessor->get_customer_card_id($customer_id);
		
		$customer = $this->get_customer_info($customer_id);
		$customer_card = $customer->default_card;
		
		if($customer_card)
		{
			return $customer_card;	
		}
		else
		{
			return false;
		}
	}
	
	
	public function get_customer_card_info($customer_id) {
		
		try 
		{
			$DBStripeAccessor = new DBStripeAccessor;
			$card_id = $DBStripeAccessor->get_customer_card_id($customer_id);
			
			$customer = $this->get_customer_info($customer_id);
			$customer_card = $customer->cards->retrieve($card_id);
			
			return $customer_card;	
		}
		catch (Exception $e) 
		{
			return false;
		}
	}
	
	//Retrieve a number of all active subscriptions by the customer id
	public function get_active_subscriptions($customer_id) {
		
		try 
		{
			$get_customer = $this->get_customer_info($customer_id);
			
			$get_active_plans = false;
			if($get_customer && !$get_customer->deleted)
			{
				$get_active_plans = $get_customer->subscriptions->all();
				$get_num_active_plans = count($get_active_plans->data) == 0 ? false : true;
			}
			
			return $get_num_active_plans;
		}
		catch (Exception $e) 
		{
			return false;
		}
		
	}
	
	public function get_invoice($invoice_id) {
		try 
		{
			return Stripe_Invoice::retrieve($invoice_id);
		}
		catch (Exception $e) 
		{
			return false;
		}
			
	}
	
	
}

?>