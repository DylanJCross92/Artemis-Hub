<?php 

class Sanitize {
	
	static public function html_output($string){
		
		return htmlspecialchars($string, ENT_QUOTES);
	}
	
}

?>