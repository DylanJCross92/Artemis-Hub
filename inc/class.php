<style>

.video-player-wrapper {
	outline: none;
	border: none;	
	position: relative;
}
.video-player-wrapper .video-player {
	width: 100%;
	position:relative;
	z-index: 99;
	cursor: pointer;
}
.video-player-wrapper .video-controls {
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	background-color: rgba(0,0,0, 0.75);
	padding: 5px;
	z-index: 100;
	display: block;
	cursor: auto;
}
.video-player-wrapper .video-controls .progress-bar {
	height: 5px;
	margin-top: -5px;
	margin-left: -5px;
	margin-right: -5px;
	margin-bottom: 5px;
	background-color: #EEE;
	position: relative;
}
.video-player-wrapper .video-controls .progress-bar .loading-progress {
	
}

.video-player-wrapper .video-controls .progress-bar .seek-bar {
	margin: 0px;
	height: 5px;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	width: 100%;
	box-sizing: border-box;
	display: block;
	
}

.video-section-wrapper .video-wrapper {
	padding-top: 25px;
	padding-bottom: 25px;
	padding-left: 50px;
	padding-right: 50px;
}
.video-section-wrapper .video-wrapper .video-iframe {
	width: 950px;
	height: 534px;
	position: relative;
	z-index: 9;
}

</style>
<?php 
	$postID = $_GET["id"];

	$videoTitle = trim(WPAccessor::get_the_title($postID));
	$videoPreviewSRC = User::subscription_active($user_id) ? trim(WPAccessor::get_field("full_embed_url", $postID)) : trim(WPAccessor::get_field("preview_embed_url", $postID));
	$videoDescription = trim(WPAccessor::get_field("description", $postID));
	$videoCategory = WPAccessor::get_field("exercise_type", $postID);
	
	$Purchases = new Purchases;
	
	$full_access = User::subscription_active($user_id) || $Purchases->verify_purchase($user_id, "single_purchase", $postID) ? true : false;
	
	if(!$videoTitle || !$videoPreviewSRC || !$videoDescription)
	{
		echo "404 error";
		//include(get_query_template('404'));
	}
	else
	{
		if($full_access)
		{
			Video::add_view($postID);	
		}
?>
<div class="video-page">
    <div class="container center top-wrapper">
    	
        <div class="two-col">
            <div class="left-col">
                <h3 class="video-title"><?php echo $videoTitle?></h3>
            </div>
            <div class="right-col">
              <?php echo get_category_icon($videoCategory);?>
            </div>
        </div>
          
        <div class="video-section-wrapper">
        	
            <?php if(!$full_access) {?>
            <div class="notice">
            You are watching a preview. Please purchase a subscription or this single video to watch the full class.
            </div>
            
            <?php }?>
        
            <div class="video-wrapper">
                <iframe class="video-iframe" src="<?php echo $videoPreviewSRC?>?color=663399&title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
        </div>
        
        <div class="description-wrapper">
            <h3>Description</h3>
            <div class="body"><?php echo $videoDescription?></div>
        </div>
        
    </div>
</div>

<?php }?>