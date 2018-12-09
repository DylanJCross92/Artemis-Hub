<?php require_once('../../initialize.php');
	
	if(!User::is_logged_in()){
		die();	
	}
	
	$user_id = User::user_id();
	$goal_id = $_POST["goal_id"];
	
	$goal_data = Dashboard::get_goal($goal_id);
	
	$goal_id = $goal_data["id"];
	$goal_type_id = $goal_data["goal_type_id"];
	$goal_type_obj = Dashboard::get_goal_type_obj($goal_type_id);
	$goal_duration = $goal_data["duration"];
	$goal_target = $goal_data["target"];
	
?>
	<div class="overlay-wrapper window edit-goal">
    <div class="container-wrapper">
        
        <div class="close close-icon icon-cancel"></div>
        
        <div class="create-goal-wrapper">
			<div class="overlay-label">Editing Your "<?php echo $goal_type_obj["label"];?>" Goal</div>       
            <div class="create-goal">
                <div class="form-wrapper">
                	<form action="javascript:void(0);" method="POST" id="edit-goal-form">
                        <input type="hidden" name="goal_id" value="<?php echo $goal_id;?>"/>
                        
                        <div class="form-element-wrapper">
                            <label>Goal Type</label>
                            <select name="goal_type">
                            <?php 
								
								$goalArray = Dashboard::get_goal_type_menu();
								$selected_id = false;
								
								foreach($goalArray as $goal_type)
								{
									if($goal_type["id"] == $goal_type_id)
									{
										$selected_id = $goal_type_id;
									?>
										<option selected value="<?php echo $goal_type["id"]?>"><?php echo $goal_type["label"]?></option>
									<?php 
									}
									else
									{
									?>
                                    	<option disabled value="<?php echo $goal_type["id"]?>"><?php echo $goal_type["label"]?></option>
                                    <?php
									}
								}
								?>
                            </select> 
                        </div>
                        
                        <div class="form-element-wrapper">
                            <label>Duration</label>
                            <select name="goal_duration">
                            	<?php 
								$durationArray = array(array("id" => 1, "label" => "1 Week"), array("id" => 2, "label" => "2 Weeks"), array("id" => 4, "label" => "1 Month"));
								
								foreach($durationArray as $duration)
								{
								?>
									<option <?php if($duration["id"] == $goal_duration) {?>selected<?php }?> value="<?php echo $duration["id"]?>"><?php echo $duration["label"]?></option>
								<?php 	
								}
								?>
                            </select> 
                        </div>
                        
                        <div class="form-element-wrapper target-weight" style="display:<?php if($selected_id == 1){echo "block";}else{echo "none";}?>;" <?php if($selected_id != 1){echo "disabled";}?>>
                        	<label for="target">Target Weight</label>
                           	<input type="text" name="target" class="input" size="10" id="target" disabled placeholder="lbs." value="<?php echo $goal_target?>"/>
                        </div>
                              
                   		<div style="text-align: center; margin-top: 25px;">
                        	<!--<a class="button small submit-button submit-form" data-loading-text="Deleting...">Delete Goal</a>-->
                           <a class="button small submit-button submit-form" data-loading-text="Updating...">Update Goal</a>
                   		</div>
                   </form>
                </div>
            </div>
    	</div>
        
    </div>
</div>