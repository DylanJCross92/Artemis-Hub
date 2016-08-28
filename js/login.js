$(document).ready(function() { 
     
	 /* 
	 	Signin Form
	 */
	 $('.submit.signin-btn').on("click", function(event){
		 
		event.preventDefault();
		var $windowLocation = $(this).attr('href');
		var $serializedData = $(".signin-form").serialize();
      	
		$.ajax({
			url: "ajax/login/login.php",
			dataType: "json",
			type: "POST",
			data: $serializedData,
			success: function(json) {
				
				if(!json.error)
				{
					window.location = "html/dashboard";  
				}
				else 
				{
					$(".signin-form [name]").removeClass("error");
					$(".form-error").remove();
					
					$.each(json.error_messages, function(name, value){
						var $field = $(".signin-form [name='"+name+"']");
						
						$field.addClass("error");
						$field.before("<div class='form-error'>"+value+"</div>");
					});
				}
			}
		});
     });

});