<?php


$WPAccessor = new WPAccessor;
$posts = $WPAccessor->wp_posts();
print_r($posts);



//$WPDatabaseAccessor = new WPDatabaseAccessor;
//$query = $WPDatabaseAccessor->query("SELECT wp_posts.* FROM wp_posts WHERE wp_posts.post_type = 'classes' AND ((wp_posts.post_status = 'publish'))  ORDER BY wp_posts.post_date DESC ");






?>