<?php require_once("../wordpress/wp-config.php");?>
<div class="top-page">
	<div class="center page-header-wrapper">
        <h4 class="icon-video-">Classes</h4>
        <!--<div class="filter-wrapper">
                <span class="filter-options">
                    <ul class="select-menu">
                        <span class="selected icon-asterisk">All Workouts</span>
                        <div class="dropdown">
                          <li class="icon-asterisk">All Workouts</li>
                          <li class="cat-1 flaticon-heart288">Cardio</li>
                          <li class="cat-2 flaticon-gym7">Strength</li>
                          <li class="cat-3 flaticon-bald37">Mind/Body</li>
                        </div>
                    </ul>
                    <span style="margin-left: 25px;"></span>
                    <ul class="select-menu">
                        <span class="selected icon-users-2">All Instructors</span>
                        <div class="dropdown">
                          <li class="icon-users-2">All Instructors</li>
                          <li class="icon-user-female">Lucia</li>
                          <li class="icon-user-female">Kathleen</li>
                          <li class="icon-user-female">Michelle</li>
                          <li class="icon-user-female">CC and Jessie</li>
                        </div>
                        
                    </ul>
              </span>
        </div>-->
        
    </div>
</div>

<div class="container center">
    <ul class="video-list"> 
        <?php
			$args = array(
			  'post_type' => "classes",
			  'post_status' => 'publish',
			  'posts_per_page' => -1
			  );
			
			$classes = new WP_Query($args);
			if($classes->have_posts()) 
			{
				while ($classes->have_posts()) : $classes->the_post(); 
					
					$postID = get_the_ID();
					$title = get_the_title();
					$description = get_field("description");
					$thumbnail = get_field("thumbnail");
					$thumbnailURL = $thumbnail["sizes"]["class-thumbnail-retina"];
					$permalink = "?page=class&id=".$postID;//get_the_permalink();
					$category = get_field("exercise_type");
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
                                <!--<li>
                                    <a class="instructor icon-user-female" href="?page=instructor">Jessica Parker</a>
                                </li>-->
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