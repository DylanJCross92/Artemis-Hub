<?php 
	
class Session {
	
	public static function set_session($name, $value) 
	{
		session_regenerate_id(true);
		$_SESSION[$name] = $value;
		
		return true;
	}
	
	public static function get_session($name)
	{
		return isset($_SESSION[$name]) ? $_SESSION[$name] : false;	
	}
	
	public static function set_cookie($name, $value, $expire) 
	{
		//Defaults
		$path = "/";
		$domain = "";
		$secure = isset($_SERVER["HTTPS"]);
		$httponly = true;
			
		return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
	}
	
	
}

?>