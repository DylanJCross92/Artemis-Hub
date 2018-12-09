/*
	Signup Form
*/
$(function(){
	
	/*
		Signup Form Submit	
	*/
	
	$(document).on("submit", "form.signup-form", function(e){
		e.preventDefault();
		
		var $this = $(this);
		var formData = $this.serialize();
		
		if(ValidateForm($(this).closest("form")))
		{
			
			var loading = setTimeout(function(){Loading("Signing In...")}, 250);
			
			$.ajax({
				type: "POST",
				url: "ajax/signup/signup.php",
				data: formData,
				dataType: "json",
				success: function(json) {
					
					if("success" in json)
					{
						$(".form-errors").hide().empty();
						$(".error", $this).removeClass("error");	
						
						window.location = '/portal/?page=select-plan';
					}
					else if("field_errors" in json)
					{	
						hideOverlay();
						clearTimeout(loading);
						
						displayErrors(toArray(json.field_errors), "form.signup-form");	
					}
					
				},
				error: function(){
					hideOverlay();
					clearTimeout(loading);
				}
			});
		}
		
		return false;
		
	});
	
	/*
		Login Form Submit	
	*/
	
	$(document).on("submit", "form.signin-form", function(e){
		e.preventDefault();
		
		var $this = $(this);
		var formData = $this.serialize();
		
		if(ValidateForm($(this).closest("form")))
		{
			var loading = setTimeout(function(){Loading("Signing In...")}, 250);
			
			$.ajax({
				type: "POST",
				url: "ajax/signin/signin.php",
				data: formData,
				dataType: "json",
				success: function(json) {
					
					if("success" in json)
					{
						$(".form-errors").hide().empty();
						$(".error", $this).removeClass("error");
						
						window.location = '/portal/';
					}
					else if("field_errors" in json)
					{	
						hideOverlay();
						clearTimeout(loading);
						
						displayErrors(toArray(json.field_errors), "form.signin-form");	
						$("[type='password']").val("").focus();
					}
					
				},
				error: function(){
					
					hideOverlay();
					clearTimeout(loading);
				}
			});
		}
		
		return false;
		
	});
	
	/*
		Logout
	*/
	
	$(document).on("click", ".logout-button", function(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: "ajax/signout/signout.php",
			dataType: "json",
			success: function(json) {
				if("success" in json)
				{
					window.location = 'login.php';	
				}
			}
			
		});
		
	});
	
	
	
	
	
	
	/*
		Cancel Subscription
	*/
	$(document).on("click", ".cancel-subscription", function(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: "ajax/subscription/cancel_subscription.php",
			dataType: "json",
			success: function(json) {
				if("success" in json)
				{
					alert("You have canceled your subscription");
				}
			}
			
		});
		
	});
	
});

function ErrorMessage()
{
	/*
		General Error Messages
	*/
	
	this.email = {
					errorMessage: "Invalid Email"	
	},
	
	this.password = {
					//errorMessage: "Password is required <ul style='font-size: 14px;'><li>Between 6 and 20 characters</li><li>At least 1 uppercase character</li><li>At least 1 lowercase character</li><li>At least one number</li></ul>"
					errorMessage: "Invalid Password"
	},
	
	this.password_verification = {
					errorMessage: "Passwords do not match"		
	},
	
	/* 
		Payment Form Errors 
	*/
	this.planID = {
					errorMessage: "Invalid Plan Id"		
	},
	
	this.name = {
					errorMessage: "Invalid Name"		
	},
	
	this.address_line1 = {
					errorMessage: "Invalid Address"		
	},
	
	this.address_line2 = {
					errorMessage: "Invalid Address Line 2"		
	},
	
	this.address_zip = {
					errorMessage: "Invalid Zipcode"		
	},
	
	this.address_city = {
					errorMessage: "Invalid City"		
	},
	
	this.number = {
					errorMessage: "Invalid Credit Card Number"		
	},
	
	this.cvc = {
					errorMessage: "Invalid CVC Code"		
	},
	
	this.exp_month = {
					errorMessage: "Invalid Expiration Month"		
	},
	
	this.exp_year = {
					errorMessage: "Invalid Expiration Year"		
	}
}

function displayErrors(fieldErrors, formName)
{
	$(".form-errors", formName).remove();
	$(formName).prepend('<div class="form-errors icon-attention-circled"></div>');
	
	$(".form-errors").hide().empty();
	$(".error", formName).removeClass("error");	
	
	if(Object.keys(fieldErrors).length > 0)
	{
		var fieldErrorsMessage = [];
		
		$.each(fieldErrors, function(fieldName, value){
			
			var $this = $("[name='"+fieldName+"'], [data-stripe='"+fieldName+"']");
			var parentError = $this.data("parent");
			
			if(parentError)
			{
				$this.closest(".form-row").find("."+parentError).addClass("error");	
			}
			else
			{
				$this.addClass("error");
			}
			
			var esc_fieldName = String(fieldName).replace(/-/g, "_");
			var ErrorMessages = new ErrorMessage();
			
			if(esc_fieldName in ErrorMessages)
			{
				var errorMessage = ErrorMessages[esc_fieldName].errorMessage;
			}
			else
			{
				var errorMessage = value;
			}
			
			fieldErrorsMessage.push(errorMessage);
		});
		
		$(".form-errors").show().html(fieldErrorsMessage[0]);	
		
	}
	else
	{
		$(".form-errors", formName).remove();
		$(".form-errors").hide().empty();
		$(".error", formName).removeClass("error");	
	}
	
}


function ValidateForm(formName)
{
	var fieldErrors = {};
	
	$("[name], [data-stripe]", formName).each(function(i, e){
		
		var validate = new Validator();
		
		var $this = $(this);
		
		var fieldName = $.trim($this.attr("name")) ? $.trim($this.attr("name")) : $this.data("stripe");
		var required = $this.data("required");
		var validationType = $this.data("validation");
		var minLength = $this.data("min-length") ? $this.data("min-length") : null;
		var maxLength = $this.data("max-length") ? $this.data("max-length") : null;
		var value = $.trim($this.val());
		
		if(validationType == "equals")
		{
			var equalsTo = $this.data("equals");
			var validation = value == $.trim($(equalsTo).val());
		}
		else
		{
			var validation = validate[validationType](value);
		}	
		
		var validateLength = validate.length(value, minLength, maxLength);
	
		if((!value && required) || (value && !validation) || (value && validation && !validateLength))
		{
			fieldErrors[fieldName] = "";
		}
		
	});
	
	displayErrors(fieldErrors, formName);
	
	return Object.keys(fieldErrors).length > 0 ? false : true;
}

$(function(){
	
	$(document).on("click", ".button.disabled", function(e){
		e.preventDefault();
		return false;
	});
	
	if($(".credit-card-number").length>0)
	{
		
		$('input.credit-card-number').payment('formatCardNumber');
		$('input.credit-card-cvc').payment('formatCardCVC');
		
		$(document).on("keypress change", "input.credit-card-number", function(e){
			
			var cc_num = $.trim($(this).val());
			
			if(cc_num.length > 0)
			{
				var card_type = $.payment.cardType(cc_num);	
				
				if(card_type)
				{
					$(".accepted-payment-methods [class^='cc-']").removeClass("selected");
					$(".cc-"+card_type).addClass("selected");
				}
				
				if($.payment.validateCardNumber(cc_num))
				{
					$(this).removeClass("error").removeAttr("data-error");
				}
				
			}
			else
			{
				$(".accepted-payment-methods [class^='cc-']").removeClass("selected");	
			}
			
		});
		
		$('input').trigger("change");
			
	}
	
});

function Loading(label){ 

	label = $.trim(label);
	
	$.ajax({
		type: "POST",
		url: "ajax/overlay/popup.php?d="+new Date(),
		cache: false,
		success: function(html){
			$(".overlay-wrapper").remove();
			$("body").append(html);
			if(label)
			{
				$(".overlay-wrapper .overlay-label").html(label);
			}
			$(".overlay-wrapper").show();	
			
			PositionOverlayBox();
		}
		
	});

}

function hideOverlay() {
	
	$(".overlay-wrapper").hide().remove();
	
}


/* 
	Select plan - Enable/Disable Continue Button
*/
$(function(){
	$(document).on("click", ".plan-select > li:not(.disabled) .inner-wrapper", function(){
		
		var $this = $(this).closest("li");
		var $continueBtn = $(".continue-payment");
		
		var paymentURL = $continueBtn.data("href");
		var planID = $this.data("plan-id");
		
		$(".plan-select > li").removeClass("selected");
		$this.addClass("selected");
		
		if(!$this.hasClass("unverified"))
		{
			$(".continue-payment").removeClass("disabled");//.attr("href", paymentURL+"&plan="+planID);
		}
		else
		{
			$(".continue-payment").addClass("disabled");//.removeAttr("href");
		}
		
		/*
		if($this.hasClass("single-video-price"))
		{
			$continueBtn.addClass("disabled");
		}*/
		
		
	});
});



/*
	Select plan - Verify Account Popup
*/
$(function(){
	$(document).on("click", ".plan-select > li.unverified .button.verify-account", function(){
		$.ajax({
			type: "POST",
			url: "ajax/overlay/verify-account.php",
			cache: false,
			success: function(html){
				$(".overlay-wrapper").remove();
				$("body").append(html);
				$(".overlay-wrapper").show();
				
				PositionOverlayBox();
			}
		});
	});	
});


/*
	Select plan - Validate Coupon for Benefitness Member Pricing
*/
$(function(){	
	$(document).on("submit", "#coupon-code-form", function(e){
		e.preventDefault();
		
		var $this = $(this);
		var submitBtn = $this.find(".submit-button");
		
		var loadingText = submitBtn.data("loading-text");
		
		submitBtn.html(loadingText).addClass("disabled");
		$this.closest("form").children("input").removeClass("error");
		
		if($.trim($this.find("input").val()))
		{
			var formData = $this.serialize();
			
			$.ajax({
				type: "POST",
				url: "ajax/subscription/redeem_coupon.php",
				data: formData,
				dataType: "json",
				success: function(json) {
					if("success" in json)
					{
						submitBtn.html("Verified");
						
						if($this.closest("form").children("input[name='coupon_code']").val())
						{
							$planSelector = $(".plan-select > li.selected");
							$planSelector.removeClass("unverified").addClass("verified").find(".inner-wrapper").trigger("click");
							$planSelector.find(".bottom-notice").html("<div class='icon-ok account-verified' style='color: #3FC380;'>Account verified</div>");
							
							$(".plan-select > li.non-member-price").addClass("disabled");
							
							$(".continue-payment").removeClass("disabled");
							
							hideOverlay();
						}
					}
					else
					{
						submitBtn.html("Verify").removeClass("disabled");
						$this.closest("form").children("input[name='coupon_code']").addClass("error");
					}
				}
				
			});
		}
		else
		{
			submitBtn.html("Verify").removeClass("disabled");
			$this.closest("form").children("input[name='coupon_code']").addClass("error");	
		}
		
	});
});


/*
	Select plan - Select Single Video Popup
*/
$(function(){
	$(document).on("click", ".plan-select > li.unverified .button.select-video-purchase, .plan-select > li.single-video-price .button.select-video-purchase", function(){
		$.ajax({
			type: "POST",
			url: "ajax/overlay/select-video-purchase.php",
			cache: false,
			success: function(html){
				$(".overlay-wrapper").remove();
				$("body").append(html);
				$(".overlay-wrapper").show();
				
				PositionOverlayBox();
			}
		});
	});	
});


/*
	Select plan - Select Single Video Popup - Store Selected Video
*/

$(function(){
	$(document).on("click", ".video-purchase-list > li:not(.disabled)", function(e){
			
		e.preventDefault();
		
		var plan_id = $(this).data("plan-id");
		var video_id = $(this).data("video-id");
		
		$.ajax({
				type: "POST",
				url: "ajax/subscription/set_single_plan_id.php",
				data: {
					plan_id: plan_id
				},
				dataType: "json",
				success: function(json) {
					
					if("success" in json)
					{
						$(".plan-select > li.single-video-price").attr("data-plan-id", plan_id).removeClass("unverified").addClass("verified").find(".inner-wrapper").trigger("click");
						
						$.ajax({
							type: "POST",
							url: "ajax/subscription/select_video/view/selected_video.php",
							data: {
								video_id: video_id
							},
							cache: false,
							success: function(html){
								$(".plan-select li.selected .selected-video").html(html);
								$(".plan-select li.selected").addClass("verified");
							}
						});
						
						hideOverlay();
					}
					
				}
			});
		
	});
});


/*
	Select plan - Store Selected Plan in Session
*/
$(function(){
	$(document).on("click", ".continue-payment:not(.disabled)", function(e){
			
		e.preventDefault();
		
		var plan_id = $(".plan-select > li.selected").attr("data-plan-id");
		var href = $(this).attr("href");
		
		$.ajax({
				type: "POST",
				url: "ajax/session/set_session.php",
				data: {
					plan_id: plan_id	
				},
				dataType: "json",
				success: function(json) {
					
					if("success" in json)
					{
						window.location.href = href;
					}
					
				}
			});
		
	});
});



/*
	Payment Page
*/



$(function(){
 
 	$('#payment-form input').on('change', function() {
		if($("#payment-form").find(".error").length>0)
		{
			ValidateForm($(this).closest("form"));
		}
	});
 	
  	$('#payment-form').submit(function(e) {
	e.preventDefault();
	
	var $form = $(this);
	var valid_form = ValidateForm($form);
	
	if(valid_form)
	{
		Stripe.setPublishableKey('pk_test_9xSzCkTyXaIayeVMJdiC0f2J');
		
		$form.find('.button.submit-form').addClass("disabled");
		Stripe.card.createToken($form, stripeResponseHandler);
		
		Loading("Please wait while we process your payment...");
	}
	
    // Prevent the form from submitting with the default action
    return false;
  });
});


function stripeResponseHandler(status, response) {
	
  var $form = $('#payment-form');
  
  if(response.error) 
  {
	displayErrors(response.error, "#payment-form");	
	
	$form.find('.button.submit-form').removeClass("disabled");
	hideOverlay();
  } 
  else 
  {
    var token = response.id;
	
	if($form.find("[name='stripeToken']").length<1)
	{
		$form.append($('<input type="hidden" name="stripeToken" data-required="true" data-validation="not_empty"/>').val(token));
	}
	else
	{
		$form.find("[name='stripeToken']").val(token);
	}
    
  
	$.ajax({
		type: "POST",
		url: "ajax/payment/subscription.php",
		dataType: "json",
		data: { 
			name: $("#payment-form input[data-stripe='name']").val(),
			address_line1: $("#payment-form input[data-stripe='address_line1']").val(),
			address_line2: $("#payment-form input[data-stripe='address_line2']").val(),
			address_zip: $("#payment-form input[data-stripe='address_zip']").val(),
			address_city: $("#payment-form input[data-stripe='address_city']").val(),
			planID: $("#payment-form input[name='planID']").val(),
			videoID: $("#payment-form input[name='videoID']").val(), 
			stripeToken: $("#payment-form input[name='stripeToken']").val()
		},
		cache: false,
		success: function(json){
			
			if("success" in json)
			{
				$form.find('.button.submit-form').addClass("disabled");
				$("#payment-form input").val("").change();
				if(json.redirect_url)
				{
					window.location.href = json.redirect_url;
				}
			}
			else if("field_errors" in json)
			{	
				displayErrors(json.field_errors, "#payment-form");	
				
				hideOverlay();
				$form.find('.button.submit-form').removeClass("disabled");
			}
		},
		error: function(){
			$form.find('.button.submit-form').removeClass("disabled");
			hideOverlay();
		}
	});
  
  }
  
};


/*
	Dashboard Page
*/

$(function(){
	/*
		Create a new goal
	*/
	
	$(document).on("click", ".goals > li.add-new", function(){
		
		$.ajax({
		type: "POST",
		url: "ajax/overlay/create-goal.php",
		cache: false,
		success: function(html){
				$(".overlay-wrapper").remove();
				$("body").append(html);
				$(".overlay-wrapper").show();	
				
				PositionOverlayBox();
			}
			
		});
	
	});
	
	
	$(document).on("submit", "#create-goal-form", function(e){
		e.preventDefault();
		
		var $this = $(this);
		var submitBtn = $this.find(".submit-button");
		
		var defaultText = "Create Goal";
		var loadingText = submitBtn.data("loading-text");
		
		submitBtn.html(loadingText).addClass("disabled");

		var formData = $(this, "[name]:enabled").serialize();
		
		$.ajax({
			type: "POST",
			url: "ajax/tracking/goal/create_goal.php",
			data: formData,
			dataType: "json",
			success: function(json) {
				if("success" in json)
				{
					submitBtn.html("Created!");
					hideOverlay();
					
					if("goal_id" in json)
					{
						var goal_id = json.goal_id;
						
						$.ajax({
							type: "POST",
							url: "ajax/tracking/goal/view/goal_box.php",
							data: "goal_id="+goal_id,
							success: function(html) {
								
								$(html).hide().prependTo(".top-row .goals").fadeIn("fast");
								
								if($(".top-row .goals > li:not(.add-new)").length >= 3)
								{
									$(".top-row .goals > li.add-new").remove();
								}
							}
						});
					}
					else
					{
						submitBtn.html(defaultText).removeClass("disabled");
					}
					
				}
				else
				{
					submitBtn.html(defaultText).removeClass("disabled");
					
					//$this.closest("form").children("input[name='coupon_code']").addClass("error");
				}
			}
		});
		
	});
	
	/*
		Edit a goal
	*/
	
	$(document).on("click", ".goals > li .edit-goal", function(){
		
		var goal_id = $(this).closest("li").data("id");
		
		$.ajax({
		type: "POST",
		url: "ajax/overlay/edit-goal.php",
		data: {
			goal_id : goal_id	
		},
		cache: false,
		success: function(html){
				$(".overlay-wrapper").remove();
				$("body").append(html);
				$(".overlay-wrapper").show();
				
				PositionOverlayBox();
			}
			
		});
	
	});
	
	
	$(document).on("submit", "#edit-goal-form", function(e){
		e.preventDefault();
		
		var $this = $(this);
		var submitBtn = $this.find(".submit-button");
		
		var defaultText = "Update Goal";
		var loadingText = submitBtn.data("loading-text");
		
		submitBtn.html(loadingText).addClass("disabled");

		var formData = $(this, "[name]:enabled").serialize();
			
		$.ajax({
			type: "POST",
			url: "ajax/tracking/goal/edit_goal.php",
			data: formData,
			dataType: "json",
			success: function(json) {
				if("success" in json)
				{
					submitBtn.html("Created!");
					hideOverlay();
					
					if("goal_id" in json)
					{
						var goal_id = json.goal_id;
						
						$.ajax({
							type: "POST",
							url: "ajax/tracking/goal/view/goal_box.php",
							data: "goal_id="+goal_id,
							success: function(html) {
								
								$(".goals > li[data-id='"+goal_id+"']").fadeOut("fast", function(){
									var div = $(html).hide();
									$(this).replaceWith(div);
									$(".goals > li[data-id='"+goal_id+"']").fadeIn("fast");
								});	
							}
						});
					}
					else
					{
						submitBtn.html(defaultText).removeClass("disabled");
					}
					
				}
				else
				{
					submitBtn.html(defaultText).removeClass("disabled");
				}
			}
		});
		
	});
	
});


/*
	Global Actions
*/

$(document).on("submit", "form", function(e){
	
	e.preventDefault();
	//$(this).find(".submit").trigger("click");
	
});

function toArray(obj)
{
	return $.map(obj, function(value, index) {
		return [value];
	});	
}

$(function(){
	
	//$("select.custom-select").selectOrDie();
	
	$(document).on('keypress', "form input:last", function (event) {
         if(event.which == '13')
		 {
			$(this).closest("form").find(".submit-form").trigger("click");
         }
    });
   
   
	$(document).on("keyup", function(e) {
		if(e.keyCode == 27) 
		{
			$(this).find(".close").trigger("click");
		}
	});
	
	
	$(document).on("click", "form .submit-form:not('.disabled')", function(e){
		$(this).closest("form").trigger("submit");
	});
	
	$(document).on("click", ".select-menu", function(e){
		
		$(".select-menu").not(this).removeClass("active");
		$(this).toggleClass("active");
		
		e.stopPropagation();
		
	});
	
	$(document).on("click", ".select-menu .dropdown > li", function(e){
		
		$(this).closest(".select-menu").children(".selected").html($(this).html()).attr("class", "selected").addClass($(this).attr("class"));
		$(this).parent(".select-menu").removeClass("active");
		$(this).closest(".select-menu").children("input").val($(this).text()).trigger("change");
		
	});
	
	$(document).on("click", document, function(e){
		
		$(".select-menu").removeClass("active");
		
	});
	
	$(".select-menu").each(function(i,e){
		
		$this = $(this);
		
		if($this.children("input").length>0)
		{
			if($this.children("input").val())
			{
				var thistext = $this.children("input").val();
				
				$('li', this).filter(function() {
					return $(this).text().trim() == thistext;
				}).trigger("click").trigger("click");
				
			}
		}
		
	});
	
	$(document).on("click", ".overlay-wrapper .close", function(e){
		$(this).closest(".overlay-wrapper").hide().remove();
	});
	
	
});


function PositionOverlayBox()
{
	//Center overlay boxes
	var marginTop = 0;
	var marginLeft = 0;
	var $overlayContainer = $(".overlay-wrapper .container-wrapper");
	var containerHeight = $overlayContainer.height();
	var windowHeight = $(window).height();
	var containerPadding = $overlayContainer.outerHeight() - containerHeight;
	
	$overlayContainer.css({"max-height" : windowHeight-containerPadding, "height":"auto"});
	
	marginTop = $overlayContainer.outerHeight()/2;
	marginLeft = $overlayContainer.outerWidth()/2;
	
	//$overlayContainer.css({"margin-top" : - marginTop, "margin-left" : - marginLeft});	
	$overlayContainer.css({"margin-left" : - marginLeft});	
}

$(function(){
	$(window).resize(function() {
		
		PositionOverlayBox();
		
	});
});
