<?php require_once('../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	$Stripe = new StripeAccessor;
	$StripeUpdateDB = new StripeUpdateDB;
	$DBStripeAccessor = new DBStripeAccessor;
	
	$customer_id = $DBStripeAccessor->get_customer_id($user_id);
	try
	{
		$subscription_id = $Stripe->get_customer_info($customer_id)->subscriptions->data[0]->id;

		if($customer_id && $subscription_id)
		{
			$data = $Stripe->cancel_subscription($customer_id, $subscription_id);
	
			$StripeUpdateDB->update_stripe_subscription($data);
			echo ajax_success();
		}
		else
		{
			throw new Exception(ErrorMessage::$SYSTEM_UNEXPECTED_ERROR);
		}
	}
	catch(Exception $e)
	{
		echo ajax_error(array("unexpected_error" => $e->getMessage()));
	}
?>