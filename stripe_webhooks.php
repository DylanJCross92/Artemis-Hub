<?php require_once('initialize.php');

$DatabaseAccessor = new DatabaseAccessor;
	
$body = @file_get_contents("php://input");
$stripe_json = json_decode($body);

if($stripe_json)
{
	$StripeUpdateDB = new StripeUpdateDB;
	
	$type = $stripe_json->type;
	$data = $stripe_json->data;
	
	if(in_array($type, array("plan.created", "plan.updated", "plan.deleted")))
	{
		$StripeUpdateDB->update_stripe_plans($data, true);
	}
	else if(in_array($type, array("customer.created", "customer.updated", "customer.deleted")))
	{
		$StripeUpdateDB->update_stripe_customer($data, true);
	}
	else if(in_array($type, array("customer.card.created", "customer.card.updated", "customer.card.deleted")))
	{
		$StripeUpdateDB->update_stripe_cards($data, true);
	}
	else if(in_array($type, array("customer.subscription.created", "customer.subscription.updated", "customer.subscription.deleted")))
	{
		$StripeUpdateDB->update_stripe_subscription($data, true);
	}
	else if(in_array($type, array("invoice.created", "invoice.updated")))
	{
		$StripeUpdateDB->update_stripe_invoices($data, true);
	}
}
else
{
	http_response_code(404);	
}
?>