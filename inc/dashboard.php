<script>
$(function(){
	
	$(document).on("click", ".dashboard-page .top-page .tracking-section > li .inner-wrapper .input-wrapper .close", function(e) {
		
		var $this = $(this).closest(".tracking-section > li");
		$this.removeClass("active");
	});
	
	$(document).on("click", ".dashboard-page .widget-wrapper .week-view-wrapper > li .events", function(e){
		$(this).toggleClass("complete");
	});
	
	$(document).on("click", ".dashboard-page .top-page .tracking-section > li .inner-wrapper .icon-outer-wrapper", function(e) {
		
		var $this = $(this).closest("li");
		
		$this.toggleClass("active").siblings().removeClass('active');
	});

});
</script>
<div class="dashboard-page">
  	<div class="top-page">
        <div class="center">
            <ul class="tracking-section">
                <li class="track-weight">
                	<div class="inner-wrapper">
                    	<div class="icon-outer-wrapper">
                            <div class="icon-wrapper">
                                <div class="flaticon-scale16"></div>
                            </div>
                            <div class="label">
                                Log Weight
                            </div>
                        </div>
                		
                    	<div class="progress-wrapper">
                            <div class="progress-label" style="font-size: 15px; font-weight: 400;"><?php Views::log_label(1); ?></div>
                        </div>
                    	
                        <div class="input-wrapper">
                        	<div class="close close-icon icon-cancel-circled"></div>
                            
                            <div class="header">Log today's weight</div>
                            
                            <form class="update-log-form">
                            	<input type="hidden" name="log_type_id" value="1">
                        		<input type="text" name="value" class="input small" style="margin-bottom: 10px;" placeholder="Enter your weight (lbs.)"/>
                        		<a class="button small submit-form" data-type-loading-text="Logging...">Log Weight</a>
                            </form>
                          	
                      	</div>
                        
                        <div class="help-wrapper">
                            <a class="help-icon icon-help-circled-1"></a>
                            <div class="help-box">
                            Nullam non congue sem. Sed molestie id turpis non consequat. Cras et quam massa. </p>Nullam sed mi sit amet turpis ultrices consectetur vel quis diam. Aenean aliquam porttitor neque, quis fringilla orci accumsan quis. 
                            </div>
                        </div>
                        
                    
                    </div>
                </li>
                <li class="track-meal">
                	<div class="inner-wrapper">
                    	<div class="icon-outer-wrapper">
                          <div class="icon-wrapper">
                                <div class="flaticon-restaurant23"></div>
                          </div>
                          <div class="label">
                                Log Meal
                          </div>
                    	</div>
                        
                        <div class="progress-wrapper">
                            <div class="progress-label" style="font-size: 15px; font-weight: 400;"><?php Views::log_label(2); ?></div>
                        </div>
                        
                      <div class="input-wrapper">
                        	<div class="close close-icon icon-cancel-circled"></div>
                            
                            <div class="header">Log today's meal</div>
                            
                            <form class="update-log-form">
                            	<input type="hidden" name="log_type_id" value="2">
                               
                               <div>
                                   <select name="value">
                                       <option value="0">Healthy</option>
                                       <option value="1">Unhealthy</option>
                                   </select>
                               </div>
                               
                               <a class="button small submit-form" data-type-loading-text="Logging...">Log Meal</a>
                            </form>
                        </div>
                        
                        <div class="help-wrapper">
                            <a class="help-icon icon-help-circled-1"></a>
                            <div class="help-box">
                            Nullam non congue sem. Sed molestie id turpis non consequat. Cras et quam massa. </p>Nullam sed mi sit amet turpis ultrices consectetur vel quis diam. Aenean aliquam porttitor neque, quis fringilla orci accumsan quis. 
                            </div>
                        </div>
                        
                    </div>
                </li>
                <li class="track-activity">
                	<div class="inner-wrapper">
                    	<div class="icon-outer-wrapper">
                          <div class="icon-wrapper">
                                <div class="flaticon-runer"></div>
                          </div>
                          <div class="label">
                                Log Workout
                          </div>
                        </div>
                        
                        <div class="progress-wrapper">
                            <div class="progress-label" style="font-size: 15px; font-weight: 400;"><?php Views::log_label(3); ?></div>
                        </div>
                    
                        <div class="input-wrapper">
                        	<div class="close close-icon icon-cancel-circled"></div>
                           
                           <div class="header">Log today's workout</div>
                            
                            <form class="update-log-form">
                            	<input type="hidden" name="log_type_id" value="3">
                               
                               <div>
                                   <select name="value">
                                       <option value="0">Cardio</option>
                                       <option value="1">Strength</option>
                                       <option value="2">Mind/Body</option>
                                   </select>
                               </div>
                               
                               <a class="button small submit-form" data-type-loading-text="Logging...">Log Exercise</a>
                            </form>
                            
                        </div>
                        
                        <div class="help-wrapper">
                            <a class="help-icon icon-help-circled-1"></a>
                            <div class="help-box">
                            Nullam non congue sem. Sed molestie id turpis non consequat. Cras et quam massa. </p>Nullam sed mi sit amet turpis ultrices consectetur vel quis diam. Aenean aliquam porttitor neque, quis fringilla orci accumsan quis. 
                            </div>
                        </div>
                        
                    </div>
                </li>
            </ul>
        </div>
 	</div>
    
    <script>
	$(function(){
	
		$(document).on("submit", ".update-log-form", function(e){
		e.preventDefault();
		
		var $this = $(this);
		var submitBtn = $this.find(".submit-button");
		
		var defaultText = "Log Weight";
		var loadingText = submitBtn.data("loading-text");
		
		var value = $.trim($this.find("[name='value']").val());
		var log_type_id = $.trim($this.find("[name='log_type_id']").val());
		
		if($.isNumeric(value) && $.isNumeric(log_type_id))
		{
			submitBtn.html(loadingText).addClass("disabled");
			
			var formData = $this.serialize();
			
			$.ajax({
				type: "POST",
				url: "ajax/tracking/log/update_log.php",
				data: formData,
				dataType: "json",
				success: function(json) {
					if("success" in json)
					{
						submitBtn.html("Logged!");
						$this.find("input[name='value']").val("");
						$this.find("[name='value']").prop('selectedIndex',0);
						
						$thisParent = $this.closest("li");
						$thisParent.find(".close").trigger("click");
						
						if("log_type_id" in json)
						{
							var log_type_id = json.log_type_id;
							
							$.ajax({
								type: "POST",
								url: "ajax/tracking/log/view/log_label.php",
								data: "log_type_id="+log_type_id,
								success: function(html) {
									
									$thisParent.find(".progress-label .log_label").fadeOut("fast", function(){
										var div = $(html).hide();
										$(this).replaceWith(div);
										$thisParent.find(".progress-label .log_label").fadeIn(100);
									});	
								}
							});
							
							submitBtn.html(defaultText).removeClass("disabled");
							
						}
						
						if("goal_id" in json)
						{
							var goal_id = json.goal_id;
						
							$.ajax({
								type: "POST",
								url: "ajax/tracking/goal/view/goal_box.php",
								data: "goal_id="+goal_id,
								success: function(html) {
									
									$(".goals > li[data-id='"+goal_id+"']").fadeOut(0, function(){
										var div = $(html).hide();
										$(this).replaceWith(div);
										$(".goals > li[data-id='"+goal_id+"']").fadeIn(0);
									});	
								}
							});
						}
						
						
					}
					else
					{
						submitBtn.html(defaultText).removeClass("disabled");
					}
				}
			});
		
		}
		else
		{
			submitBtn.html(defaultText).removeClass("disabled");	
		}
		
	});
		
	});
	</script>
  	<div class="bottom-page center"> 
        <div class="top-row">
            <ul class="goals">
            
            	<?php 
				//TEST CODE ONLY
				$_db = new DatabaseAccessor;
				$db_safe_user_id = $_db->sanitize_db($user_id);
				$db_safe_date = $_db->sanitize_db(time());
				
				$query = $_db->query("SELECT user_goals.* FROM user_goals WHERE `user_id` = $db_safe_user_id AND `end_date` > $db_safe_date ORDER BY `date` DESC LIMIT 3");
				
				while($goal = $query->fetch_assoc())
				{
					$goal_id = $goal["id"];
					
					echo Views::goal_box($goal_id);
				}
				?>
                
				<?php if($query->num_rows < 3)
				{?>
                <li class="add-new">
                	<div class="add-icon-wrapper">
                		<div class="icon-plus"></div>
                   	</div>
                	<div class="label">Create a Goal</div>
                </li>
                <?php }?>
            </ul>
        </div>
    
	<?php 
	$videos = Video::recently_viewed($user_id);	
	if($videos && count($videos) > 0)
	{	
	?>
    <div class="section-header-wrapper">
        <h4 class="icon-history">Recently Watched</h4>   
    </div>
    
    <div class="subscription-updates container">
    	<ul class="video-list verticals"> 
		<?php
        foreach($videos as $video)
        {
            $args = array(
              'post_type' => "classes",
              'post_status' => 'publish',
              'p' => $video,
              'posts_per_page' => -1
              );
            
            $classes = WPAccessor::WP_Query($args);
            if($classes->have_posts()) 
            {
                while ($classes->have_posts()) : $classes->the_post(); 
                    
                    $postID = WPAccessor::get_the_ID();
                    $title = WPAccessor::get_the_title($postID);
                    $description = WPAccessor::get_field("description");
                    $thumbnail = WPAccessor::get_field("thumbnail");
                    $thumbnailURL = $thumbnail["sizes"]["class-thumbnail-retina"];
                    $permalink = "?page=class&id=".$postID;
                    $category = WPAccessor::get_field("exercise_type");
                ?>
                    <li>   
                        <div class="left-col thumbnail-wrapper">
                            <a class="thumb-overlay" href="<?php echo $permalink?>"><div class="watch-button icon-play-circled"></div></a>
                            <img class="thumbnail" src="<?php echo $thumbnailURL?>"/>
                        </div>
                         
                        <div class="right-col info-wrapper">
                            <a class="title" href="<?php echo $permalink?>"><?php echo $title;?></a>
                            <div class="description body"><?php echo $description?></div>
                            
                            <ul class="video-info">
                                <li>
                                    <?php echo get_category_icon($category);?>
                                </li>
                            </ul>    
                        </div>
                                    
                      </li>
                     
                <?php
                endwhile;
            }
            wp_reset_query();
        }
        
    ?> 
        </ul>
    </div>
    
    <?php 
	}
	else
	{
	?>
    
    <div class="section-header-wrapper">
        <h4 class="icon-lightbulb">Recommended Classes</h4>   
    </div>
    
    <div class="subscription-updates container">

    	<ul class="video-list verticals"> 
		<?php
        
            $args = array(
              'post_type' => "classes",
              'post_status' => 'publish',
              'posts_per_page' => 4
              );
            
            $classes = WPAccessor::WP_Query($args);
            if($classes->have_posts()) 
            {
                while ($classes->have_posts()) : $classes->the_post(); 
                    
                    $postID = WPAccessor::get_the_ID();
                    $title = WPAccessor::get_the_title($postID);
                    $description = WPAccessor::get_field("description");
                    $thumbnail = WPAccessor::get_field("thumbnail");
                    $thumbnailURL = $thumbnail["sizes"]["class-thumbnail-retina"];
                    $permalink = "?page=class&id=".$postID;
                    $category = WPAccessor::get_field("exercise_type");
                ?>
                    <li>   
                        <div class="left-col thumbnail-wrapper">
                            <a class="thumb-overlay" href="<?php echo $permalink?>"><div class="watch-button icon-play-circled"></div></a>
                            <img class="thumbnail" src="<?php echo $thumbnailURL?>"/>
                        </div>
                         
                        <div class="right-col info-wrapper">
                            <a class="title" href="<?php echo $permalink?>"><?php echo $title;?></a>
                            <div class="description body"><?php echo $description?></div>
                            
                            <ul class="video-info">
                                <li>
                                    <?php echo get_category_icon($category);?>
                                </li>
                            </ul>    
                        </div>
                                    
                      </li>
                     
                <?php
                endwhile;
            }
            wp_reset_query();
             
    	?> 
        </ul>
    </div>
    
    <?php }?>
  </div>
    
</div>