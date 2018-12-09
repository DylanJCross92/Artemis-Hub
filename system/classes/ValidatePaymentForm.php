<?php 

class ValidatePaymentForm extends Validation {
	
	static public function planID($string) {
		$string = trim($string);
		
		$Stripe = new StripeAccessor;
		return $Stripe->validate_plan($string) && $string;
	}
	
	static public function stripeToken($string) {
		$string = trim($string);
		
		return isset($string);
	}
	
	static public function address_line1($string) {
		$string = trim($string);
		
		return self::address($string) && $string;	
	}
	
	static public function address_line2($string) {
		$string = trim($string);
		
		return !$string || (self::secondary_address($string) && $string);
	}
	
	static public function address_city($string) {
		$string = trim($string);
		
		return self::city($string) && $string;
	}
	
	static public function address_zip($string) {
		$string = trim($string);
		
		return self::zipcode($string) && $string;	
	}

}

?>