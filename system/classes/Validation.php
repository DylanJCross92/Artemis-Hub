<?php 

class Validation {
	
	public static function not_empty($string) {
		
		return trim($string)!="";
	}
	
	public static function name($string) {
		
		return preg_match('/^(\w+ )*\w+$/', trim($string));
	}
	
	public static function address($string) {
		
		//return preg_match('/^[a-zA-Z0-9-\/] ?([a-zA-Z0-9-\/]|[a-zA-Z0-9-\/] )*[a-zA-Z0-9-\/]$/', trim($string));	
		return self::not_empty($string);
	}
	
	public static function secondary_address($string) {
		
		//return preg_match('/^(?:[a-zA-Z]+(?:[.\'\-,])?\s?)+$/', trim($string));
		return self::not_empty($string);
	}
	
	public static function city($string) {
		
		//return preg_match('/^[a-zA-Z]+(?:(?:\\s+|-)[a-zA-Z]+)*$/', trim($string));
		return self::not_empty($string);
	}
	
	public static function zipcode($string) {
		
		return preg_match('/\d{5}-?(\d{4})?$/', trim($string));	
	}
	
	public static function email($string) {
		
		return preg_match("/.+@.+\..+/i", trim($string));
	}
	
	public static function password($string) {
		
		return preg_match("/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20})/", trim($string));
	}
	
	public static function password_verification($password1, $password2) {
		
		return $password1 == $password2;
	}
		
}


?>