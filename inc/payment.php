<?php
$b64_planID = Session::get_session("plan_id");
$planID = base64_decode($b64_planID);

if($parts = explode("_", $planID))
{
	$planID = base64_decode($parts[0]);
	$videoID = isset($parts[1]) ? $parts[1] : false;
}

if(!$planID || !DBStripeAccessor::validate_plan($planID))
{
	//die(redirect("?page=select-plan"));
}
$user_id = User::user_id();
if(User::subscription_active($user_id))
{
	//die(redirect("portal.php?page=dashboard"));	
}
?>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<style>
body  {
	background-color: #FFF;
}
</style>
<?php 
	
$Stripe = new DBStripeAccessor;
$html_safe_planID = Sanitize::html_output($planID);
$html_safe_b64_planID = base64_encode($html_safe_planID);

$html_safe_videoID = $videoID ? Sanitize::html_output($videoID) : "";

$plan_details = $Stripe->get_plan($planID);
$plan_name = $plan_details["name"];
$plan_amount = $plan_details["amount"];
$plan_interval = $plan_details["interval"];

?>              
<div class="payment-page">
	<div class="container center">
			<div class="header-wrapper">
				<h2>Payment</h2>
			</div>
			<div class="sub-header-wrapper">
				<h4><span class="icon-ok"></span><?php if(WPAccessor::get_the_title($videoID)){ echo WPAccessor::get_the_title($videoID); } else {echo $plan_name;}?> - <strong>$<?php echo cents_to_dollars($plan_amount)?></strong></h4>
			</div>
			
	  <div class="payment-wrapper">
			
		<form action="javascript:void(0);" method="POST" id="payment-form">
		  
          <!-- TODO: Possibly change planID and videoID from form field to SESSION variable so it is always available-->
		  <input type="hidden" value="<?php echo $html_safe_b64_planID?>" name="planID" data-required="true" data-validation="alphanumeric" data-min-length="5" data-max-length="50"/>
          <input type="hidden" value="<?php echo $html_safe_videoID?>" name="videoID" data-required="false" data-validation="alphanumeric" data-min-length="0" data-max-length="10"/>
		  
		  <div class="two-col">
					<div class="left-col">
						<div class="billing-info-wrapper">
						
							<h3>Billing Address</h3>
							
							<div class="contact-info-wrapper">
								<div class="form-row">
									<input type="text" class="input" size="25" placeholder="Full Name" data-stripe="name" data-required="true" data-validation="name" data-min-length="5" data-max-length="50"/>
								</div>
							</div>
						
						  <div class="contact-info-wrapper">
								<div class="form-row">
									<input type="text" class="input" size="25" placeholder="Street Address" data-stripe="address_line1"  data-required="true" data-validation="address" data-min-length="5" data-max-length="50"/><span class="form-spacer"></span><input type="text" class="input" size="5" placeholder="Apt." data-stripe="address_line2" data-required="false" data-validation="address" data-min-length="0" data-max-length="15"/><span class="form-spacer"></span><input type="text" class="input" size="5" placeholder="Zip Code" data-stripe="address_zip"  data-required="true" data-validation="zipcode" data-min-length="5" data-max-length="12"/>
								</div>
								
								<div class="form-row">
									<input type="text" class="input" size="25" placeholder="City" data-stripe="address_city" data-required="true" data-validation="city" data-min-length="5" data-max-length="50"/>
								</div>
								
							</div>
						</div>
					
					</div>
					<div class="right-col">
						<div class="payment-method-wrapper">
						
							<h3>Payment Method</h3>
							
							<div class="accepted-payment-methods">
								<span class="cc-visa icon-cc-visa"></span><span class="cc-mastercard icon-cc-mastercard"></span><span class="cc-discover icon-cc-discover"></span><span class="cc-amex icon-cc-amex"></span>
							</div>
							
							<div class="form-row">
								<input type="text" class="input credit-card-number" size="25" placeholder="Debit/Credit Card Number" data-stripe="number" data-required="true" data-validation="credit_card_number"/><span class="form-spacer"></span><input type="text" class="input credit-card-cvc" size="4" autocomplete="off" placeholder="CVC" data-stripe="cvc" data-required="true" data-validation="credit_card_cvc"/>
							</div>
							
							<div class="form-row">
								<h4 style="clear-margins">Expires: 
									<ul class="select-menu thin exp-month-input-parent">
										<input type="hidden" data-stripe="exp-month" data-required="true" data-validation="credit_card_expiration_month" data-parent="exp-month-input-parent"/>
										<span class="selected">--</span>
										<div class="dropdown">
										  <li>01</li>
										  <li>02</li>
										  <li>03</li>
										  <li>04</li>
										  <li>05</li>
										  <li>06</li>
										  <li>07</li>
										  <li>08</li>
										  <li>09</li>
										  <li>10</li>
										  <li>11</li>
										  <li>12</li>
										</div>
									</ul>
									
									<span> / </span>
									
									<ul class="select-menu thin exp-year-input-parent">
										<input type="hidden" data-stripe="exp-year" data-required="true" data-validation="credit_card_expiration_year" data-parent="exp-year-input-parent"/>
										<span class="selected">--</span>
										<div class="dropdown">
										  <?php 
										  $curYear = date("Y");
										  $maxYear = $curYear + 5;
										  $year = $curYear;
										  
										  while($year <= $maxYear)
										  {
											  ?>
												<li><?php echo $year;?></li>
												<?php
												
												$year++;
										  }
										  ?>
										</div>
									</ul>
								
								</h4>
							</div>
							
						</div>
						
					</div>
				</div>
				
				<div class="center-text" style="margin-top: 50px;">
					<div>
						<?php 
							$checkoutInterval = $plan_interval;
							
							if($checkoutInterval == "month")
							{
								$checkoutIntervalLabel = "Monthly";	
								$checkoutIntervalWarning = "You will be charged this amount each month for<br> the duration of your membership.";	
							}
							else if($checkoutInterval = "one_time")
							{
								$checkoutIntervalLabel = "One Time";	
								$checkoutIntervalWarning = "This is a one time charge.";	
							}
						?>
						<h3 class="clear-margins"><?php echo $checkoutIntervalLabel?> Total: <span style="font-weight: 400;">$<?php echo cents_to_dollars($plan_amount)?></span></h3>
					</div>
					<h4 class="clear-margins" style="font-size: 18px;"><span class="icon-attention-circled" style="color:#F7CA18;"></span><?php echo $checkoutIntervalWarning?></h4>
				</div>
				
				<div style="text-align: center; margin-top: 50px;">
					<a href="?page=select-plan" class="button gray small icon-left-open-big" style="float: left; margin-top: 5px; margin-right: -9999px;">Select a Plan</a>
					<a class="button submit-form pay-button">Pay Now</a>
				</div>
				
			</form>
			
			</div>
			
  </div>
		
</div> 