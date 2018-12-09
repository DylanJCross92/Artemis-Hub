<?php require_once('../../initialize.php');
	
if(!User::is_logged_in()){
	die();	
}
	
$user_id = User::user_id();

$Purchases = new Purchases; 
?>
<style>
.video-purchase-list {
	max-height: 500px; 
	overflow-y: auto;	
	padding: 0px;
	margin: 0px;
}
.video-purchase-list > li {
	list-style: none;
	display: table;
	width: 100%;
	box-sizing: border-box;
	padding: 15px;
	border-bottom: solid 1px #eee;
	cursor: pointer;
}

.video-purchase-list > li:hover {
	background-color: #eee;
}

.video-purchase-list > li:hover * {
	/*color: #FFF !important;	*/
}

.video-purchase-list > li:last-child {
	border-bottom: none;
}

.video-purchase-list > li .left-col {
	display: table-cell;
	vertical-align: top;	
	font-size: 0px;
	line-height: 0px;
}
.video-purchase-list > li .right-col {
	display: table-cell;
	vertical-align: top;
	padding-left: 25px;	
}

.video-purchase-list > li .thumbnail-wrapper {
	position: relative;
	z-index: 0;
	height: 75px;
	width: 125px;
	overflow: hidden;
	cursor: pointer;
}

.video-purchase-list > li .thumbnail-wrapper .thumbnail {
	height: 75px;
	width: 125px;  
}

.video-purchase-list > li .thumbnail-wrapper .thumb-overlay {
	position: absolute;
	left: 0px;
	top: 0px;
	right: 0px;
	bottom: 0px;
	line-height: 75px;
	text-align: center;
	z-index: 1;
	display:block;
}
.video-purchase-list > li .thumbnail-wrapper:hover .thumb-overlay {
	background-color: rgba(0,0,0,0.25);
}
.video-purchase-list > li .thumbnail-wrapper .thumb-overlay .watch-button {
	padding: 0px;
	color: rgba(255,255,255, 0.85);
	text-decoration: none;
	display: inline-block;
	cursor: pointer;
	vertical-align: middle;
	box-sizing: border-box;
	font-size: 0px;
	line-height: 0px;
	height: 35px;
	width: 35px;
	text-align: center;
	border-radius: 50%;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
}

.video-purchase-list > li .thumbnail-wrapper .thumb-overlay .watch-button:before {
	margin: 0px;
	padding: 0px;
	font-size: 35px;
	line-height: 35px;
}

.video-purchase-list > li .thumbnail-wrapper:hover .thumb-overlay .watch-button {
	color: rgba(255,255,255, 0.95);
}

.video-purchase-list > li .title {
	font-size: 18px;	
	font-weight: 400;
	color: #663399;
}
.video-purchase-list > li .category-name {
	float: right;
}
.video-purchase-list > li .description {
	margin-top: 5px;
	font-size: 16px;
	line-height: 20px;	
	font-weight: 300;
}
.video-purchase-list > li .description p {
	margin: 0px;	
}
</style>
<div class="overlay-wrapper window large select-video-purchase">
    <div class="container-wrapper">
        
        <div class="close close-icon icon-cancel"></div>
        
        <div class="account-verification-wrapper">
			<div class="overlay-label">Select a Video to Purchase</div>     
            
            <ul class="video-purchase-list">  
            <?php
				/*
				$purchaseArr = $Purchases->get_single_purchases() ? $Purchases->get_single_purchases() : array();
				
				$WPAccessor = new WPAccessor;
				$posts = $WPAccessor->wp_posts(array("post__not_in" => $purchaseArr));
				*/
				
				$purchaseArr = $Purchases->get_single_purchases() ? $Purchases->get_single_purchases() : array();
				
				$args = array(
				  'post_type' => "classes",
				  'post_status' => 'publish',
				  'posts_per_page' => -1,
				  'post__not_in' => $purchaseArr
				  );
				
				$classes = WPAccessor::WP_Query($args);
				
				
				if($classes->have_posts()) 
				{
					while ($classes->have_posts()) : $classes->the_post(); 
						
						$postID = WPAccessor::get_the_ID();
						$title = WPAccessor::get_the_title($postID);
						$description = WPAccessor::get_field("description", $postID);
						$thumbnail = WPAccessor::get_field("thumbnail", $postID);
						$thumbnailURL = $thumbnail["sizes"]["class-thumbnail-retina"];
						$permalink = "?page=class&id=".$postID;
						$category = WPAccessor::get_field("exercise_type", $postID);
					?>
						<li data-video-id="<?php echo $postID?>" data-plan-id="<?php echo base64_encode("single_purchase")."_".$postID;?>">   
							<div class="left-col thumbnail-wrapper">
								<a class="thumb-overlay"><div class="watch-button icon-play-circled"></div></a>
								<img class="thumbnail" src="<?php echo $thumbnailURL?>"/>
							</div>
							 
							<div class="right-col info-wrapper">
								<?php echo get_category_icon($category);?>
								<div class="title"><?php echo $title;?></div>
								<div class="description body"><?php echo substr($description,0, 140).'...';?></div> 
							</div>
										
						  </li>
						 
					<?php
					endwhile;
				}
				wp_reset_query();
				
			?> 
    		</ul>
        </div>
        
    </div>
</div>