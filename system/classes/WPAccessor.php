<?php require_once("/var/www/wordpress/wp-config.php");

class WPAccessor {

	public function __construct(){
		
	}
	
	public static function WP_Query($args) {
		
		return new WP_Query($args);
			
	}
	
	public static function get_the_ID() {
		
		return get_the_ID();	
	}
	public static function get_the_title($post_id) {
		
		return get_the_title($post_id);
	}
	
	public static function get_field($field, $postID = NULL) {
		
		return get_field($field, $postID);
	}
	
	
}

?>