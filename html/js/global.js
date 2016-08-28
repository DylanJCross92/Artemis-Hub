var ROOT = "../";

$(function(){
	
	$(".schedule-dates-wrapper .schedule-dates li").on("click", function(){
		$(".schedule-dates-wrapper .schedule-dates li").not($(this)).removeClass("current");
		$(this).addClass("current");
	});
	
	$(".class-block-list .class-block").on("click", function(){
		$(".class-block-list .class-block").not($(this)).closest("li").removeClass("selected");
		$(this).closest("li").toggleClass("selected");
	});
	
	
	$(".class-details .class-students li .checkin").on("click", function(){
		$(this).closest("li").toggleClass("checked");
	});
	
	$(".popout-icon").on("click", function(e){
		e.stopPropagation();
	});
	
});

$(function(){
	
	$(".schedule-dates-wrapper .schedule-dates > li").on("click", function(){
		
		var $date = $(this).data("date");
		
		$.ajax({
		  type: 'POST',
		  url: 'ajax/view/page/dashboard/day-breakdown.php',
		  data: { date: $date },
		  beforeSend:function(){
			// this is where we append a loading image
			$(".day-breakdown-wrapper").hide();
			
			$(".day-breakdown-wrapper-loading").remove();
			$(".day-breakdown-wrapper").before("<div class='day-breakdown-wrapper-loading'><h1>Loading...</h1></div>");
			
		  },
		  success:function(data){
			// successful request; do something with the data
			
			$(".day-breakdown-wrapper-loading").fadeOut(150, function(){
				$(this).remove();
				$(".day-breakdown-wrapper").fadeIn(150);
			});
			
			//console.log(data);
			
		  },
		  error:function(){
			// failed request; give feedback to user
		  }
		});
	});
	
});


/* 
 * Auto Focus Search Bar
 */
$(function(){
	$(document).keydown(function(e){
		// Enter was pressed without shift key
		var tag = e.target.tagName.toLowerCase();
	
		if ((e.keyCode == 32 && e.shiftKey) && tag != 'input' && tag != 'textarea')
		{
			// prevent default behavior
			e.preventDefault();
			$(".header-bar .search-wrapper .search-input").focus();
		}
	});
});

/* 
 * ESC Close Overlay
 */

$(function(){
	$(document).keypress(function(e) {
	  //if (e.keyCode == 13) $('.save').click();     // enter
	  if (e.keyCode == 27) $('.close').click();   // esc
	});
});


/* 
 *	Add a Staff Member: Overlay
 */

$(function(){
	$(".add-staff-member-btn").on("click", function(){
		
		$(".overlay-wrapper").fadeIn(100);
		
		$.ajax({
			type: 'POST',
			url: 'ajax/view/page/staff/add-staff-member-overlay.php',
			error: function() {
			 /* ERROR */
			},
			success: function(html) {
				
				var $title = $(html).filter("overlay-title").text();
				var $overlayClass = $(html).filter("overlay-class").text();
				
				$(".overlay-wrapper .title-bar .title").html($title);
				$(".overlay-wrapper .overlay-body").html(html);
				
				$(".overlay-wrapper overlay-title").remove();
				$(".overlay-wrapper overlay-class").remove();
			}
		});
		
	});
});


/* 
 * Add a Staff Member: form submit
 */
 
 $(document).on("submit", "form[name='add-staff-member']", function(e){
	
	e.preventDefault();
	var $form = $(this);
	var $serializedData = $form.serialize();
	
	$.ajax({
		url: "ajax/staff/create-staff.php",
		dataType: "json",
		type: "POST",
		data: $serializedData,
		success: function(json) {
			
			if(!json.error)
			{
				$(".overlay-wrapper").fadeOut(100);
			}
			else 
			{
				$(".error", $form).removeClass("error");
				$(".form-error").remove();
				
				$.each(json.error_messages, function(name, value){
					var $field = $($form).find("[name='"+name+"']");
					
					if($field.hasClass("fancySelect")) {
						$field = $field.closest(".fancy-select");	
					}
					
					$field.addClass("error");
					$field.before("<div class='form-error'>"+value+"</div>");
				});
			}
		}
	});
 });
	 

/* 
 *	Create a Class Overlay
 */

$(function(){
	$(".create-new-class-btn").on("click", function(){
		
		$(".overlay-wrapper").fadeIn(100);
		
		$.ajax({
			type: 'POST',
			url: 'ajax/view/page/classes/create-class-overlay.php',
			error: function() {
			 /* ERROR */
			},
			success: function(html) {
				
				$(".overlay-wrapper .title-bar .title").html("Create a Class");
				$(".overlay-wrapper .overlay-body").html(html);
			}
		});
		
	});
	
});


/* 
 *	Dashboard Classes Overlay
 */

$(function(){
	$(".dashboard-page .schedule .list > li").on("click", function(){
		
		$(".overlay-wrapper").fadeIn(100);
		
		$.ajax({
			type: 'POST',
			url: 'ajax/view/page/classes/class-overlay.php',
			data: {
			  id: '44'
			},
			error: function() {
			 /* ERROR */
			},
			success: function(html) {
				$(".overlay-wrapper .title-bar .title").html("Pilates Reformer");
				$(".overlay-wrapper .overlay-body").html(html);
			}
		});
		
	});
	
	$(".dashboard-page .schedule .list > li a").on("click", function(e){
		e.stopPropagation();
	});
});

/* 
 *	Classes Overlay
 */

$(function(){
	$(".classes-page .class-list > li").on("click", function(){
		
		$(".overlay-wrapper").fadeIn(100);
		
		$.ajax({
			type: 'POST',
			url: 'ajax/view/page/classes/class-overlay.php',
			data: {
			  id: '44'
			},
			error: function() {
			 /* ERROR */
			},
			success: function(html) {
				$(".overlay-wrapper .title-bar .title").html("Pilates Reformer");
				$(".overlay-wrapper .overlay-body").html(html);
			}
		});
		
	});
	
	
	$(".classes-page .class-list > li .button").on("click", function(e){
		e.stopPropagation();
	});
});


/* 
 * Create Class
 */
 
 $(document).on("click", ".submit.create-class-btn", function(event){
	
	event.preventDefault();
	var $form = $(this).closest("form.create-class-form");
	var $serializedData = $form.serialize();
	
	$.ajax({
		url: ROOT+"ajax/classes/create-class.php",
		dataType: "json",
		type: "POST",
		data: $serializedData,
		success: function(json) {
			
			if(!json.error)
			{
				alert("success");
			}
			else 
			{
				$(".error", $form).removeClass("error");
				$(".form-error").remove();
				
				$.each(json.error_messages, function(name, value){
					var $field = $($form).find("[name='"+name+"']");
					
					if($field.hasClass("fancySelect")) {
						$field = $field.closest(".fancy-select");	
					}
					
					$field.addClass("error");
					$field.before("<div class='form-error'>"+value+"</div>");
				});
			}
		}
	});
 });
	 

/* 
 *	Members Overlay
 */

$(function(){
	$(".members-page .members-list > li").on("click", function(){
		
		$(".overlay-wrapper").fadeIn(100);
		
		$(".overlay-wrapper .title-bar .title").html("Cythia Ricard");
		$(".overlay-wrapper .overlay-body").html("Member info here");
	});
	
	$(".members-page .members-list > li .button").on("click", function(e){
		e.stopPropagation();
	});
});


/* 
 *	Logout User
 */

$(function(){
	$('.logout-btn').on("click", function(e){
		e.preventDefault();
      	
		$.ajax({
			url: "ajax/logout/logout.php",
			dataType: "json",
			type: "POST",
			data: "",
			complete: function(json) {
				window.location = "../login.php";
			}
		});
	});
});

 $(document).on("click", ".submit", function(e){
	e.preventDefault(); 
	
	$(this).closest("form").trigger("submit");
 });
 
 
 /*
  * Format Phone Number Inputs
  */
  
$(function(){
	$(document).on("keyup", "input[data-format-type='phone']", function() {
		$(this).prop('maxLength', 13);
		$(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "($1)$2-$3"));
	});
});

/* 
 * Global Ajax Actions
 */

$(function(){
	
	$(document).ajaxComplete(function(event, xhr, settings) {
		/* 
		 * Fancy Select
		 */
		 
		$('.fancySelect').fancySelect();
		
		/*
		 * Air Datepicker
		 */
		 
		$('.air-datepicker').datepicker({
			language: "en",
			showOtherYears: false,
			selectOtherYears: false,
			moveToOtherYearsOnSelect: false,
			todayButton: true,
			autoClose: true,
			position: "left center"
		});
		 
	 });
	
	 /*
	 $(document).on("click", ".create-class-form  .action.create-class-type", function(e) {
		 
		 //alert("A");
		 
		 $(this).html("<input class='test' type='text'/>");
		 $("input.test").focus();
		 
	 });*/
});


$(function(){
	
	$(document).on("click", ".create-class-form .create-class-submit-btn", function(){
		var $serializedData = $(".create-class-form").serialize();

	});
	
});

/* TEST */


$(function(){
	$(".popout-icon").on("click", function(){
		$(".overlay-wrapper").fadeIn(100);
	});
	
	$(document).on("click", ".overlay-wrapper .close-overlay", function(){
		$(this).closest(".overlay-wrapper").fadeOut(200);
	});
});