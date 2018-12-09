<?php require_once('../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	
?>
	<div class="overlay-wrapper window create-goal">
    <div class="container-wrapper">
        
        <div class="close close-icon icon-cancel"></div>
        
        <div class="create-goal-wrapper">
			<div class="overlay-label">Create a new Goal</div>       
            <div class="create-goal">
                <div class="form-wrapper">
                	<form action="javascript:void(0);" method="POST" id="create-goal-form">
                        
                        <div class="form-element-wrapper">
                            <label>Goal Type</label>
                            <select name="goal_type">
                            <?php 
								
								$goalArray = Dashboard::get_goal_type_menu();
								
								foreach($goalArray as $goal)
								{
									?>
                                    <option <?php if(Dashboard::is_goal_type_active($user_id, $goal["id"])){echo "disabled";}?> value="<?php echo $goal["id"]?>"><?php echo $goal["label"]?></option>
                                    <?php 	
								}
								?>
                            </select> 
                        </div>
                        
                        <div class="form-element-wrapper">
                            <label>Duration</label>
                            <select name="goal_duration">
                               <option value="1">1 Week</option>
                               <option value="2">2 Weeks</option>
                               <option value="4">1 Month</option>
                            </select> 
                        </div>
                        
                        <?php if(!Dashboard::is_goal_type_active($user_id, 1)){?>
                        <div class="form-element-wrapper target-weight" style="display: <?php if(Dashboard::is_goal_type_active($user_id, 1)){echo "none";}?>;" <?php if(Dashboard::is_goal_type_active($user_id, 1)){echo "disabled";}?>>
                        	<label for="target_weight">Target Weight</label>
                           	<input type="text" name="target" class="input" size="10" id="target_weight" placeholder="lbs."/>
                        </div>
                        
                        <?php }?>
                        
                   		<div style="text-align: center; margin-top: 25px;">
                        	<a class="button small submit-button submit-form" data-loading-text="Creating...">Create Goal</a>
                   		</div>
                   </form>
                </div>
            </div>
    	</div>
        
    </div>
</div>

<script>
$(function(){
	
	$(document).on("change", "[name='goal_type']", function(){
		
		var value = $(this).val();
		
		if(value == 1)
		{
			$(".form-element-wrapper.target-weight").show().removeAttr("disabled");	
		}
		else
		{
			$(".form-element-wrapper.target-weight").hide().attr("disabled", true);	
		}
		
	});
	
});
</script>