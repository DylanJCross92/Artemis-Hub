<?php require_once('../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
	if(isset($_POST["coupon_code"]))
	{
		$coupon_code = strtolower($_POST["coupon_code"]); //TODO Validate data
		
		$Stripe = new StripeAccessor;
		$DBStripeAccessor = new DBStripeAccessor;
		
		try
		{
			$check_coupon = $Stripe->get_coupon($coupon_code);
			
			if($check_coupon->valid)
			{
				$customer_id = $DBStripeAccessor->get_customer_id($user_id);
				$DBStripeAccessor->update_customer_coupon_id($customer_id, $check_coupon->id);
				echo ajax_success();
			}
		}
		catch (Exception $e) 
		{
			echo ajax_error(array("unexpected_error" => $e->getMessage()));			
		}
	
	}
	else
	{
		echo ajax_error(array("unexpected_error" => "Invalid Coupon Code"));	
	}
	

	
	
?>