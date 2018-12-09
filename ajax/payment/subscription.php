<?php require_once('../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	$email = User::user_email($user_id);
	
	$planID = base64_decode($_POST["planID"]);
	$videoID = htmlspecialchars($_POST["videoID"]);
	$stripeToken = htmlspecialchars($_POST["stripeToken"]);
	$name = htmlspecialchars($_POST["name"]);
	$address_line1 = htmlspecialchars($_POST["address_line1"]);
	$address_line2 = htmlspecialchars($_POST["address_line2"]);
	$address_city = htmlspecialchars($_POST["address_city"]);
	$address_zip = htmlspecialchars($_POST["address_zip"]);
	
	$field_errors = array();
	$single_purchaseID = array("single_purchase");
	
	if(!ValidatePaymentForm::planID($planID) && (!in_array($planID, $single_purchaseID) && !DBStripeAccessor::validate_plan($planID)))
	{
		$field_errors["planID"] = ErrorMessage::$FORM_PLANID;
	}
	if(!ValidatePaymentForm::stripeToken($stripeToken))
	{
		$field_errors["stripeToken"] = ErrorMessage::$FORM_STRIPE_TOKEN;
	}
	if(!ValidatePaymentForm::name($name))
	{
		$field_errors["name"] = ErrorMessage::$FORM_NAME;
	}
	if(!ValidatePaymentForm::address_line1($address_line1))
	{
		$field_errors["address_line1"] = ErrorMessage::$FORM_ADDRESS_LINE1;
	}
	if(!ValidatePaymentForm::address_line2($address_line2))
	{
		$field_errors["address_line2"] = ErrorMessage::$FORM_ADDRESS_LINE2;
	}
	if(!ValidatePaymentForm::address_city($address_city))
	{
		$field_errors["address_city"] = ErrorMessage::$FORM_ADDRESS_CITY;
	}
	if(!ValidatePaymentForm::address_zip($address_zip))
	{
		$field_errors["address_zip"] = ErrorMessage::$FORM_ADDRESS_ZIP;
	}
	
	if(!count($field_errors))
	{
		try 
		{
			$Stripe = new StripeAccessor;
			$DBStripeAccessor = new DBStripeAccessor;
			$StripeUpdateDB = new StripeUpdateDB;
			$Purchases = new Purchases;
			
			$customer_id = $DBStripeAccessor->get_customer_id($user_id);
			
			if(in_array($planID, $single_purchaseID))
			{
				$active_subscriptions = $Stripe->get_active_subscriptions($customer_id);
				if(!$active_subscriptions)
				{
					$planData = $DBStripeAccessor->get_plan($planID);
					$chargeAmount = $planData["amount"];
					
					if(isset($videoID))
					{
						if(!$Purchases->verify_purchase($user_id, "single_purchase", $videoID))
						{
							$Stripe->create_single_charge(array("amount" => $chargeAmount, "currency" => "usd", "card" => $stripeToken, "receipt_email" => $email, "statement_descriptor" => "Artemis Single Charge", "description" => $videoID));
							
							if($Purchases->add_purchase($planID, $videoID, $chargeAmount))
							{
								echo ajax_success("?page=class&id=".$videoID);
							}
							else
							{
								throw new Exception(ErrorMessage::$SYSTEM_ERROR_CREATING_SUBSCRIPTION); //TODO: Change error to a more appropriate error
							}
						}
						else
						{
							throw new Exception(ErrorMessage::$SYSTEM_ALREADY_SUBSCRIBED); //TODO: Change error to a more appropriate error
						}
					}
				}
				else
				{
					throw new Exception(ErrorMessage::$SYSTEM_ALREADY_SUBSCRIBED);	
				}
			}
			else
			{
				if($customer_id)
				{
					$count = 0;
					$maxTries = 3;
					$failure = true;
					$tokenUsed = false;
					
					while($failure)
					{
						if($Stripe->get_customer_card($customer_id))
						{
							$failure = false;
							
							$active_subscriptions = $Stripe->get_active_subscriptions($customer_id);
							if(!$active_subscriptions)
							{
								$subscription_data = $Stripe->create_subscription($customer_id, $planID);
								
								$subscription_id = $subscription_data->id;
								$subscription_amount = $subscription_data->plan->amount;
								
								$StripeUpdateDB->update_stripe_subscription($subscription_data);
								
								$Purchases->add_purchase($planID, $subscription_id, $subscription_amount);
							
								echo ajax_success("?page=payment-confirmation&invoice=".base64_encode($subscription_id));
							}
							else
							{
								//There is an active subscription, do not allow another	
								throw new Exception(ErrorMessage::$SYSTEM_ALREADY_SUBSCRIBED);	
							}
						}
						else
						{
							if($count++ == $maxTries)
							{ 
								throw new Exception(ErrorMessage::$SYSTEM_ERROR_CREATING_SUBSCRIPTION);
								$failure = false;
							}
							else if(!$tokenUsed)
							{
								if($Stripe->update_customer_card($customer_id, $stripeToken))
								{
									$tokenUsed = true;
								}
							}
							else
							{
								throw new Exception(ErrorMessage::$SYSTEM_TOKEN_USED);
							}
						}
					}
				}
			}
		}
		catch (Exception $e) 
		{
			echo ajax_error(array("unexpected_error" => $e->getMessage()));			
		}
	 
	}
	else
	{
		echo ajax_error($field_errors);	
	}

?>