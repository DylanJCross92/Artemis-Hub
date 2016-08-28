<?php 

class User {
	
	protected $_Database;
	protected $_UserID;
	
	public function __construct(){
		$this->_Database = new Database;
		$this->_UserID = $this->user_id();
	}
	
	public static function user_id() {
		return isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : false;
	}
	
	public static function set_token($user_id) 
	{
		if($user_id)
		{
			$_Database = new Database;

			$token = hash('sha256', bin2hex(openssl_random_pseudo_bytes(32)));
			$expiration_date = time() + 60 * 60 * 24 * 7;
			$db_safe_user_id = $_Database->sanitize_db($user_id);
			$db_safe_token = $_Database->sanitize_db($token);
			$db_safe_expiration_date = $_Database->sanitize_db($expiration_date);
			$db_safe_date = $_Database->sanitize_db(time());
			
			//$_Database->query("DELETE FROM user_auth_tokens WHERE `userID` = $db_safe_user_id");
		
			$_Database->insert("user_auth_tokens", "isii", array(
				"userID" => $db_safe_user_id,
				"token" => $db_safe_token,
				"expires" => $db_safe_expiration_date,
				"date" => $db_safe_date
			));
			
			Session::set_cookie("auth", base64_encode($token), time() + 60*60*24*7);
		}
		
	}
	
	public static function verify_token()
	{
		if(isset($_COOKIE["auth"]))
		{
			$cookie_token = $_COOKIE["auth"];
			
			$Database = new Database;
			$db_safe_token = $Database->sanitize_db(base64_decode($cookie_token));
			
			//$query = $_Database->query("SELECT user_auth_tokens.* FROM user_auth_tokens WHERE `token` = $db_safe_token");
			$user_auth_token = $Database->select("user_auth_tokens", "s", array(
				"token" => $db_safe_token
			));

			if(count($user_auth_token) > 0)
			{
				$db_user_id = $user_auth_token[0]["userID"];
				$db_token = $user_auth_token[0]["token"];
				$db_expiration_date = $user_auth_token[0]["expires"];
				
				if($db_expiration_date > time())
				{
					Session::set_session("logged_in", true);
					Session::set_session("user_id", $db_user_id);
					
					//Create a login Token
					self::set_token($db_user_id);
					
					return true;
				}
				else 
				{
					//Token has expired
					return false;	
				}
			}
			else
			{
				//Token doesn't exist
				return false;	
			}
		
		}
		else
		{
			return false;	
		}
	}
	
	/* 
		Check if email is registered
	*/
	public function is_email_registered($email) {
		
		$is_email_registered = $this->_Database->select("users", "s", array(
			"email" => $email
		));	
		
		return count($is_email_registered) != 0;
	}
	
	/*
		Check if user is activated 
	*/
	public function is_user_activated($email) {
		
	}
	
	/*
		Add new user to the database
	*/
	public function create_user($email, $password = false) {
		
		if(!$this->is_email_registered($email))
		{
			$db_safe_email = strtolower($this->_Database->sanitize_db($email));
			
			if($password)
			{	
				$db_safe_encrypted_password = $this->_Database->sanitize_db(password_hash($password, PASSWORD_BCRYPT)); 
				$db_safe_status = $this->_Database->sanitize_db("registered");
				
				$this->_Database->insert("users", "ssi", array(
					"email" => $db_safe_email,
					"password" => $db_safe_encrypted_password,
					"user_status" => $db_safe_status,
					"join_date" => time()
				));
			}
			else
			{
				$db_safe_activation_key = $this->_Database->sanitize_db(substr(sha1(rand()), 0, 14));
				
				$this->_Database->insert("users", "ssi", array(
					"email" => $db_safe_email,
					"user_activation_key" => $db_safe_activation_key,
					"join_date" => time()
				));	
			}
			
			$this->_UserID = $this->_Database->getConnection()->insert_id;
			return isset($this->_UserID) ? $this->_UserID : false; 
		}
		else
		{
			return false;	
		}
	}
	/*
		If everything is legitimate, log the user in
	*/
	public function login_user($email, $password)
	{
		try
		{
			$user_data = $this->get_user_by_email($email);
			
			$this->_UserID = $user_data["ID"];
			$db_email = strtolower($user_data["email"]);
			$db_encrypted_password = $user_data["password"];
			
			if($db_email == strtolower($email) && password_verify($password, $db_encrypted_password))
			{
				//Create Sessions
				$this->logout_user();
				
				Session::set_session("logged_in", true);
				Session::set_session("user_id", $this->_UserID);
				
				//Create a login Token
				User::set_token($this->_UserID);
				
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(Exception $e)
		{
			return false;	
		}
	}
	
	
	/*
		Check if the user is logged in
	*/
	public static function is_logged_in() 
	{
		$session_logged_in = self::user_id() && isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
		
		if($session_logged_in)
		{
			return true;
		}
		else if(!$session_logged_in && self::verify_token())
		{
			return self::verify_token();
		}
		else
		{
			return false;	
		}
	}
	
	/*
		Log the user out
	*/
	public function logout_user()
	{
		$_SESSION['logged_in'] = false;
		$_SESSION['user_id'] = false;
		session_unset();
	  	session_destroy();
		
		$name = "auth";
		$value = "";
		$expire = time() - 1000;
		$path = "/";
		$domain = "";
		$secure = isset($_SERVER["HTTPS"]);
		$httponly = true;
			
		setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);	
		
		return true;
	}
	
	/*
		Get user info by email
	*/
	public function get_user_by_email($email) {
		
		$db_safe_email = strtolower($this->_Database->sanitize_db($email));
		$user_info = $this->_Database->select("users", "s", array("email" => $db_safe_email))[0];
				
		return isset($user_info) ? $user_info : false;
	}
	
	/*
		Get the user data from user's id
	*/
	public function get_user_info($userID = false)
	{
		$userID = $userID ? $userID : $this->_UserID;
		$db_safe_user_id = $this->_Database->sanitize_db($userID);
		$user_info = $this->_Database->select("users", "i", array("ID" => $db_safe_user_id));
				
		return count($user_info) > 0 ? $user_info[0] : false;
	}
	
	public function get_user_company_id($userID = false) 
	{
		$userID = $userID ? $userID : $this->_UserID;
		$db_safe_user_id = $this->_Database->sanitize_db($userID);
		
		$company_users_info = $this->_Database->select("company_users", "i", array("userID" => $db_safe_user_id));
		
		return count($company_users_info) > 0 ? $company_users_info[0]["companyID"] : false;
	}
	
	/* 
		Update User Meta Data
	*/
	public function update_usermeta($userID, array $data) 
	{	
		//TODO: If keys exist, update it
		
		foreach($data as $key => $value) {
			
			$db_safe_key = $this->_Database->sanitize_db($key);
			$db_safe_value = $this->_Database->sanitize_db($value);
			
			$this->_Database->insert("user_meta", "iss", array(
				"userID" => $userID,
				"meta_key" => $db_safe_key,
				"meta_value" => $db_safe_value
			));	
		}
	}
	
	/* 
		Get User Meta Data 
	*/
	public function get_usermeta($userID, $key) 
	{
		$db_safe_user_id = $this->_Database->sanitize_db($userID);
		$db_safe_key = $this->_Database->sanitize_db($key);
		
		$user_metadata = $this->_Database->select("user_meta", "is", array(
			"userID" => $db_safe_user_id,
			"meta_key" => $db_safe_key
		));
		
		return count($user_metadata) > 0 ? $user_metadata[0]["meta_value"] : false;
	}
	
}

?>