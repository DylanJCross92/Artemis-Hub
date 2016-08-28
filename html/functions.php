<?php 
function get_page($page, $action_bar = false) {
	
	$path = $action_bar ? "inc/sidebar/" : "inc/";
	
	if(isset($_GET["id"])){
		$file = $path."single-".$page.".php";	
	}
	else {
		$file = $path.$page.".php";	
	}

	return file_exists($file) ? include($file) : false;
}

function is_page($page, $action_bar = false) {
	
	$path = $action_bar ? "inc/sidebar/" : "inc/";
	
	if(isset($_GET["id"])){
		$file = $path."single-".$page.".php";	
	}
	else {
		$file = $path.$page.".php";	
	}

	return file_exists($file);
}


?>