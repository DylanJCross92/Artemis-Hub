<?php

class ErrorMessage {
	
	/*
		System Errors
	*/
	static public $SYSTEM_USER_EXISTS = "User already registered";
	static public $SYSTEM_USER_NOT_EXISTS = "User does not exist";
	static public $SYSTEM_USER_INVALID_CREDENTIALS = "Invalid Credentials Provided";
	static public $SYSTEM_DATABSE_ERROR = "Unexpected erorr occured";
	
	static public $SYSTEM_UNEXPECTED_ERROR = "Unexpected erorr occured";
	static public $SYSTEM_NOT_ALLOWED = "You are unable to perform this action";
	static public $SYSTEM_INVALID_COUPON = "This is not a valid coupon code";
	static public $SYSTEM_ALREADY_SUBSCRIBED = "You currently have an active subscription";
	static public $SYSTEM_ERROR_CREATING_SUBSCRIPTION = "There was an error creating your subscription";
	static public $SYSTEM_ERROR_CANCELING_SUBSCRIPTION = "There was an error canceling your subscription";
	static public $SYSTEM_TOKEN_USED = "There was an error";
	/*
		Signup Page Errors
	*/
	static public $FORM_EMAIL = "Please enter a valid Email address";
	//static public $FORM_PASSWORD = "Please enter a valid Passsword containing: <ul><li>Between 6 and 20 characters</li><li>At least 1 uppercase character</li><li>At least 1 lowercase character</li><li>At least one number</li></ul>";
	static public $FORM_PASSWORD = "Please enter a valid Passsword";
	static public $FORM_PASSWORD_VERIFICATION = "Passwords do not match";
	
	/*
		Payment Page Errors
	*/
	static public $FORM_PLANID = "Unexpected Error";
	static public $FORM_STRIPE_TOKEN = "Invalid Token";
	static public $FORM_NAME = "Invalid name";
	static public $FORM_ADDRESS_LINE1 = "Invalid address";
	static public $FORM_ADDRESS_LINE2 = "Invalid address";
	static public $FORM_ADDRESS_CITY = "Invalid city";
	static public $FORM_ADDRESS_ZIP = "Invalid zipcode";
}

?>