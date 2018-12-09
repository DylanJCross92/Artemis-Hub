<style>
	.small-center-container {
		width: 700px;
		background-color: #FFF;
		padding: 25px;
		box-sizing: border-box;
		margin-top: 100px;
		margin-left: auto;
		margin-right: auto;
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
	}

	.payment-confirmation-wrapper {
		
	}
	
	.payment-confirmation-wrapper .header-wrapper {
		text-align: center;
	}
	.payment-confirmation-wrapper .sub-header-wrapper {
		text-align: center;
	}
	.order-summary-wrapper {
		margin-top: 25px;
		background-color: #EEE;
		border-radius: 5px;
		overflow: hidden;
	}
	.order-summary-wrapper .header {
		background-color: #663399;
		color: #FFF;
		font-size: 18px;
		line-height: 18px;
		padding: 10px;
		font-weight: 400;
	}
	.order-summary-wrapper .header .order-number {
		font-size: 14px;
		line-height: 18px;
		font-weight: 300;
		float: right;
	}
	.order-summary-wrapper .order-summary {
		padding: 0px;
		margin: 0px;
	}
	
	.order-summary-wrapper .order-summary > li {
		padding: 10px 10px;
		margin: 0px;
		list-style: none;
	}
	
	.order-summary-wrapper .order-summary > li > .two-col {
		display: table;
		width: 100%;
	}
	
	.order-summary-wrapper .order-summary > li > .two-col > .left-col {
		display: table-cell;
		vertical-align: middle;
		font-size: 18px;
		color: #95A5A6;
		font-weight: 400;
	}
	.order-summary-wrapper .order-summary > li > .two-col > .left-col .product {
		color: #6C7A89;
	}
	
	.order-summary-wrapper .order-summary > li > .two-col > .right-col {
		display: table-cell;
		vertical-align: middle;
		text-align: right;
		font-size: 20px;
		color: #6C7A89;
		font-weight: 400;
	}
	
	.order-summary-wrapper .order-summary > li > .two-col .price .text {
		font-size: 14px;
		color: #95A5A6;
	}

	
	.order-summary-wrapper .order-summary > li .note {
		font-size: 14px;
		color: #95A5A6;
		font-style:italic;
	}
	
	.payment-confirmation-wrapper .receipt-email-wrapper {
		text-align: center;
		margin-top: 25px;
	}
	

</style>

<?php
	$DBStripeAccessor = new DBStripeAccessor;
	$subscription_id = base64_decode($_GET["invoice"]);
	
	if(!$subscription_id)
	{
		redirect("portal.php?page=dashboard");
	}
	
	$Stripe = new StripeAccessor;
	
	$invoice_available = true;
	$receipt_date = time();
	
	$count = 0;
	$maxTries = 3;
	$failure = true;
	
	while($failure)
	{
		$invoice_id = $DBStripeAccessor->get_invoice_id($subscription_id);
		$invoice_data = $Stripe->get_invoice($invoice_id);
		
		if($invoice_data)
		{
			$failure = false;
			$invoice_available = true;
			
			$invoice_subscription_data = $invoice_data->lines->data[0];
			$invoice_plan_data = $invoice_subscription_data->plan;
			
			$receipt_date = $invoice_data->date;
			$receipt_number = $invoice_data->receipt_number;
			$receipt_type = $invoice_subscription_data->type;
			$plan_name = $invoice_plan_data->name;
			$plan_amount = $invoice_plan_data->amount;
			$plan_period_end_date = $invoice_subscription_data->period->end;
		}
		else
		{
			if($count++ == $maxTries)
			{ 
				$failure = false;
			}
			else
			{
				$failure = true;
			}
			
			$invoice_available = false;
		}
	}
	
	
?>

<div class="payment-confirmation-wrapper small-center-container">
    <div class="header-wrapper">
    	<h2><span class="icon-ok-circled"></span> Thank You!</h2>
    </div>
    <div class="sub-header-wrapper">
        <h4>Order Date: <strong><?php echo date("F j, Y", $receipt_date)?></strong></h4>
    </div>
	
    <?php if($invoice_available){?>
    <div class="order-summary-wrapper">
    	<div class="header">Order Summary <?php if($receipt_number){?><span class="order-number">Order #<strong><?php echo $receipt_number?></strong></span><?php }?></div>
        <ul class="order-summary">
        	<li>
            	<div class="two-col">
            		<?php if($receipt_type == "subscription"){?><div class="left-col">Artemis Subscription: <span class="product"><?php echo $plan_name?></span></div><?php }?>
               		<div class="right-col price">$<?php echo cents_to_dollars($plan_amount)?><span class="text">/mo</span></div>
               	</div>
                <?php if($plan_period_end_date){?><div class="note">Next billed on <strong><?php echo date("F j, Y", $plan_period_end_date)?></strong></div><?php }?>
            </li>
        </ul>
    </div>
    <?php }
	else {?>
    
    <div class="receipt-email-wrapper">
        <h3 class="icon-mail-1">Your receipt will be emailed to you</h3>
    </div>    
    <?php }?>
    
</div>

<div class="center" style="text-align: center; margin-top: 25px;">
	<a href="/portal" class="button">Continue to the Dashboard <span class="icon-right-circled"></span></a>
</div>
