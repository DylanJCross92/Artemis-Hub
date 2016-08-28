$(document).ready(function() { 
     
	 /* 
	 	Submit the Create Account Form
	 */
	 $('.submit.create-account-btn').on("click", function(event){
		 
		event.preventDefault();
		var $windowLocation = $(this).attr('href');
		var $serializedData = $(".create-account-form").serialize();
      	
		$.ajax({
			url: "ajax/register/create_account.php",
			dataType: "json",
			type: "POST",
			data: $serializedData,
			success: function(json) {
				
				if(!json.error)
				{
					window.location = $windowLocation;  
				}
				else 
				{
					$(".create-account-form [name]").removeClass("error");
					$(".form-error").remove();
					
					$.each(json.error_messages, function(name, value){
						var $field = $(".create-account-form [name='"+name+"']");
						
						$field.addClass("error");
						$field.before("<div class='form-error'>"+value+"</div>");
					});
				}
			}
		});
     });
	 
	 
	 /* 
	 	Submit the Business Information Form
	 */
	 $('.submit.business-info-btn').on("click", function(event){
		 
		event.preventDefault();
		var $windowLocation = $(this).attr('href');
		var $serializedData = $(".business-info-form").serialize();
      	
		console.log($serializedData);
		
		$.ajax({
			url: "ajax/register/save_business_info.php",
			dataType: "json",
			type: "POST",
			data: $serializedData,
			success: function(json) {
				
				if(!json.error)
				{
					window.location = $windowLocation;  
				}
				else 
				{
					$(".business-info-form [name]").removeClass("error");
					$(".form-error").remove();
					
					$.each(json.error_messages, function(name, value){
						var $field = $(".business-info-form [name='"+name+"']");
						
						$field.addClass("error");
						$field.before("<div class='form-error'>"+value+"</div>");
					});
				}
			}
		});
     });
}); 