<?php 
if(!User::is_logged_in())
{
	die(redirect("login.php"));
}
$user_id = User::user_id();
if(User::subscription_active($user_id))
{
	//die(redirect("portal.php?page=dashboard"));	
}

$b64_planID = Session::get_session("plan_id");
$fullPlanID = base64_decode($b64_planID);

if($parts = explode("_", $fullPlanID))
{
	$b64_planID = $parts[0];
	$planID = base64_decode($b64_planID);
	$videoID = isset($parts[1]) ? $parts[1] : false;
}
else
{
	$planID = $fullPlanID;
}

?>
<style>
body  {
	background-color: #FFF;
}
</style>
<?php
	$DBStripeAccessor = new DBStripeAccessor;
	$customer_id = $DBStripeAccessor->get_customer_id($user_id);
	$current_plan_id = $b64_planID ? $b64_planID : base64_encode($DBStripeAccessor->get_customer_plan_id($customer_id));
	$coupon_code = $DBStripeAccessor->get_customer_coupon_id($customer_id);
	
	$single_video_plan_id = $videoID ? base64_encode("single_purchase")."_".$videoID : base64_encode("single_purchase");
?>
<script>
$(function(){
	$(window).load(function(){
		
		if($(".plan-select > li.selected").length > 0 && $(".plan-select > li.selected.unverified").length==0)
		{
			$(".continue-payment").removeClass("disabled");	
		}
		else
		{
			$(".continue-payment").addClass("disabled");	
		}
	});
});

$(function(){
	$(".plan-select> li[data-plan-id^='<?php echo $current_plan_id?>']").find(".inner-wrapper").trigger("click");
});

$(function(){
	$(document).on("click", ".plan-select li .selected-video .remove-selected-video", function(){
		$(this).closest(".selected-video").closest("li").removeClass("verified").addClass("unverified").find(".select-video-info").show();
	});
});
</script>
<style>

/*
.plan-select li .selected-video {
	
}

.plan-select li .selected-video .video-wrapper {
	min-width: 100%;
	width: 333px;
	box-sizing:border-box;
	display: table;
	table-layout: fixed;
	padding: 15px;
	background: #562f86;	
	margin-bottom: 15px;
	margin-left: -25px;
	margin-right: -25px;
	border-radius: 3px;
	border-bottom: solid 2px rgba(0,0,0,0.15);
}
.plan-select li .selected-video .video-wrapper .left {
	display: table-cell;
	vertical-align: middle;
	width: 100px;
}
.plan-select li .selected-video .video-wrapper .right {
	display: table-cell;
	vertical-align: middle;	
	padding-left: 15px;
}
.plan-select li .selected-video .video-wrapper .left .thumbnail-wrapper {
	position: relative;
}
.plan-select li .selected-video .video-wrapper .left .thumbnail-wrapper .thumbnail {
	width: 100%;
}
.plan-select li .selected-video .video-wrapper .left .thumbnail-wrapper .icon {
	font-size: 15px;
	line-height: 15px;
	position: absolute;
	color: #666;
	bottom: 5px;
	right: 5px;
}
.plan-select li .selected-video .video-wrapper .right .title {
	color: #FFF;
	font-size: 18px;
	line-height: 18px;
}
*/

.plan-select li .selected-video {
	
}

.plan-select li .selected-video .video-wrapper {
	min-width: 100%;
	box-sizing:border-box;
	display: table;
	table-layout: fixed;
	text-align: center;
}
.plan-select li .selected-video .video-wrapper  {
	
}
.plan-select li .selected-video .video-wrapper .right {
	text-align: center;
	padding-left: 15px;
}
.plan-select li .selected-video .video-wrapper .thumbnail-wrapper {
	position: relative;
	width: 100%;
	margin: auto;
	cursor: pointer;
	overflow: hidden;
}
.plan-select li .selected-video:hover .video-wrapper .thumbnail-wrapper .video-info {
	display: none;
}
.plan-select li .selected-video .video-wrapper .thumbnail-wrapper .remove-selected-video {
	font-size: 50px;
	line-height: 175px;
	background: rgba(239, 71, 35, 0.75);
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	display: none;
}

.plan-select li .selected-video:hover .video-wrapper .thumbnail-wrapper .remove-selected-video{
	display: block;
}

.plan-select li .selected-video .video-wrapper .thumbnail-wrapper .thumbnail {
	width: 100%;
}
.plan-select li .selected-video .video-wrapper .video-info {
	position: absolute;
	bottom: 0;
	right: 0;
	left:0;
	background: rgba(0,0,0,0.5);	
	padding: 10px;
}
.plan-select li .selected-video .video-wrapper .video-info .icon {
	font-size: 15px;
	line-height: 15px;
	color: #FFF;
	margin-right: 5px;
}
.plan-select li .selected-video .video-wrapper .video-info .title {
	color: #FFF;
	font-size: 15px;
	line-height: 15px;
}


@keyframes flipInY { 
    0% { 
        transform: perspective(400px) rotateY(90deg); 
        opacity: 0; 
    } 
    
    100% { 
        transform: perspective(400px) rotateY(0deg); 
        opacity: 1; 
    } 
} 

.plan-select li .selected-video .video-wrapper .thumbnail-wrapper .remove-selected-video {
	-webkit-animation-duration: 0.5s; 
	-moz-animation-duration: 0.5s; 
    animation-duration: 0.5s; 
    -webkit-animation-fill-mode: both; 
	-moz-animation-fill-mode: both; 
    animation-fill-mode: both; 
}


.plan-select li .selected-video .video-wrapper .thumbnail-wrapper:hover .remove-selected-video {
	-webkit-backface-visibility: visible !important; 
	-moz-backface-visibility: visible !important; 
	backface-visibility: visible !important; 
	
    -webkit-animation-name: flipInY; 
    -moz-animation-name: flipInY; 
    animation-name: flipInY; 	
}

</style>
<div class="select-plan-page">
    <div class="container center">
    
            <div class="header-wrapper">
              <h2>Select a plan</h2>
            </div>
          	<div class="sub-header-wrapper">
            	<h4>Something about selecting a plan goes here</h4>
            </div>
            
   	  	 	<ul class="plan-select">
                <li class="single-video-price <?php if(!$videoID){echo "unverified";}else{ echo "verified";}?>" data-plan-id="<?php echo $single_video_plan_id?>">
                    <div class="inner-wrapper">
                    	<div class="header">Single Video</div>
                        
                        <div class="selected-video">
						<?php if($videoID)
						{
							Views::selected_video($videoID);
						}
						?>
                        </div>
                        
                        <ul class="body list select-video-info">
                            <li class="icon-ok">Full access to features and videos</li>
                            <li class="icon-ok">Morbi euismod quam</li>
                            <li class="icon-ok">Quisque nibh eros</li>
                        </ul>
                        <div class="price">
                            $9.99<span class="text">/ one time</span>
                        </div>
                        
                        <a class="button small additional-action select-video-purchase">Select a Video</a>
                       
                    </div>
                    <div class="bottom-notice">
                    	
                        
                        You will have access to this video forever.
                    </div>
                </li>
                
                <li class="member-price <?php if(!$coupon_code){echo "unverified";}else{ echo "verified";}?>" data-plan-id="<?php echo base64_encode("monthly_members")?>">
                    <div class="inner-wrapper">
                    	<div class="header">Monthly for Members</div>
                        <ul class="body list">
                            <li class="icon-ok">Full access to features and videos</li>
                            <li class="icon-ok">Morbi euismod quam</li>
                            <li class="icon-ok">Quisque nibh eros</li>
                        </ul>
                        <div class="price">
                            $12.99<span class="text">/ mo.</span>
                        </div>
                        
                        <?php if(!$coupon_code){?>
                        <a class="button small additional-action verify-account">Verify Account</a>
                        <?php }?>
                	</div>
                    
                    <div class="bottom-notice">
                    	<?php if(!$coupon_code){?>
                    	<div class="notice-container">
                        	<strong><a href="#" target="_blank">Benefitness</a></strong>  Customer ID required to verify membership
                        </div>
                        
                        <?php }else {?>
                        	<div class='icon-ok account-verified' style='color: #3FC380;'>Account verified</div>
                        <?php }?>
                    </div>
                </li>
                
                <li class="non-member-price <?php if($coupon_code){echo "disabled";}?>" data-plan-id="<?php echo base64_encode("monthly_nonmembers")?>">
                    <div class="inner-wrapper">
                   		<div class="header">Monthly for Non-Members</div>
                        <ul class="body list">
                            <li class="icon-ok">Full access to features and videos</li>
                            <li class="icon-ok">Morbi euismod quam</li>
                            <li class="icon-ok">Quisque nibh eros</li>
                        </ul>
                        <div class="price">
                            $24.99<span class="text">/ mo.</span>
                      	</div>
                    </div>
                    <div class="bottom-notice">
                    	<?php if($coupon_code){?>
                        	You are a verified Benefitness member
                        <?php } else {?>
                    		Enroll at <strong><a href="#" target="_blank">Benefitness</a></strong> for a discounted price
                        <?php }?>
                    </div>
                </li>
            
            </ul>
            
          <div class="button-bar-wrapper">
              	<a href="?page=dashboard" class="button gray small">Skip for now<span class="icon-right-open-big"></span></a><a href="/portal/?page=payment" class="button small continue continue-payment disabled" style="float:right;">Continue with selected plan<span class="icon-right-open-big"></span></a>
          </div>
        
        </div>  
</div>