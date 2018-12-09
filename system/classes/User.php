<?php 

class User {
	
	protected $_db;
	protected $_user_id;
	
	public function __construct(){
		$this->_db = new DatabaseAccessor;
		$this->_user_id = $this->user_id();
	}
	
	public static function user_id() {
		return isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : false;
	}
	

	/*
		Checks if email exists in the database
	*/
	public function check_user_by_email($email) {
		
		$db_safe_email = strtolower($this->_db->sanitize_db($email));
		
		$sql = "SELECT users.* FROM users WHERE `email` = $db_safe_email";
		return $this->_db->query($sql)->num_rows > 0 ? $this->_db->query($sql)->fetch_assoc() : false;
	}
	
	/*
		Add new user to the database
	*/
	public function add_user($email, $password) {
		
		$db_safe_email = strtolower($this->_db->sanitize_db($email));
		$db_safe_encrypted_password = $this->_db->sanitize_db(password_hash($password, PASSWORD_BCRYPT)); 
		$last_login_date = time();
		$join_date = time();
		
		if(!$this->check_user_by_email($email))
		{
			$sql = "INSERT INTO users (`email`, `password`, `last_login_date`, `join_date`) VALUES ($db_safe_email, $db_safe_encrypted_password, $last_login_date, $join_date)";
			$query = $this->_db->query($sql);
			$user_id = $this->_db->getConnection()->insert_id;
			
			//Create a customer account in Stripe for later use
			$Stripe = new StripeAccessor;
			$customer_data = $Stripe->create_customer(array("email" => $email));
			$customer_id = $customer_data->id;
			
			$DBStripeAccessor = new DBStripeAccessor;
			$DBStripeAccessor->update_customer_id($user_id, $customer_id);
			
			return $query ? $query : false;
		}
	}
	/*
		If everything is legitimate, log the user in
	*/
	public function login_user($email, $password)
	{
		try
		{
			$user_data = $this->check_user_by_email($email);
			
			$this->_user_id = $user_data["id"];
			$db_email = strtolower($user_data["email"]);
			$db_encrypted_password = $user_data["password"];
			
			if($db_email == strtolower($email) && password_verify($password, $db_encrypted_password))
			{
				//Create Sessions
				$this->logout_user();
				
				Session::set_session("logged_in", true);
				Session::set_session("user_id", $this->_user_id);
				
				//Create a login Token
				
				User::set_token($this->_user_id);
				
				$DBStripeAccessor = new DBStripeAccessor; 
				$customer_id = $DBStripeAccessor->get_customer_id($this->_user_id);
				$DBStripeAccessor->sync_stripe_info($customer_id);
				
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
	
	public static function set_token($user_id) 
	{
		if($user_id)
		{
			$_db = new DatabaseAccessor;

			$token = hash('sha256', bin2hex(openssl_random_pseudo_bytes(32)));
			$expiration_date = time() + 60 * 60 * 24 * 7;
			$db_safe_user_id = $_db->sanitize_db($user_id);
			$db_safe_token = $_db->sanitize_db($token);
			$db_safe_expiration_date = $_db->sanitize_db($expiration_date);
			$db_safe_date = $_db->sanitize_db(time());
			
			$_db->query("DELETE FROM user_auth_tokens WHERE `user_id` = $db_safe_user_id");
			$query = $_db->query("INSERT INTO user_auth_tokens SET `user_id` = $db_safe_user_id, `token` = $db_safe_token, `expires` = $db_safe_expiration_date, `date` = $db_safe_date");
			
			Session::set_cookie("auth", base64_encode($token), time() + 60*60*24*7);
		}
		
	}
	
	public static function verify_token()
	{
		if(isset($_COOKIE["auth"]))
		{
			$cookie_token = $_COOKIE["auth"];
			
			$DatabaseAccessor = new DatabaseAccessor;
			$db_safe_token = $DatabaseAccessor->sanitize_db(base64_decode($cookie_token));
			
			$query = $DatabaseAccessor->query("SELECT user_auth_tokens.* FROM user_auth_tokens WHERE `token` = $db_safe_token");
			
			if($query->num_rows > 0)
			{
				$row = $query->fetch_assoc();
				
				$db_user_id = $row["user_id"];
				$db_token = $row["token"];
				$db_expiration_date = $row["expires"];
				
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
		Check if the user is logged in
	*/
	public static function is_logged_in() 
	{
		$User = new User;
		$session_logged_in = $User->get_user(User::user_id()) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
		
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
	
	public static function subscription_status($user_id){
		
		$DBStripeAccessor = new DBStripeAccessor;
		$subscription_status = $DBStripeAccessor->get_subscription_status($user_id);
	
		return $subscription_status;
	}
	
	public static function subscription_active($user_id){
	
		return User::subscription_status($user_id) == "active" ? true : false;
	}
	
	/*
		Get the user data from user's id
	*/
	public static function get_user($user_id)
	{
		$User = new User;
		$db_safe_user_id = $User->_db->sanitize_db($user_id);
		
		$sql = "SELECT users.* FROM users WHERE `id` = $db_safe_user_id";
		return $User->_db->query($sql)->num_rows > 0 ? $User->_db->query($sql)->fetch_assoc() : false;	
	}
	
	public static function user_email($user_id) {
		
		$user_data = self::get_user($user_id);
		$user_email = $user_data["email"];
		
		return isset($user_email) ? $user_email : false;	
	}
	
	public static function last_login_date($user_id) {
		
		$user_data = self::get_user($user_id);
		$last_login_date = $user_data["last_login_date"];
		
		return isset($last_login_date) ? $last_login_date : false;	
	}
	
	public static function join_date($user_id) {
		
		$user_data = $this->get_user($user_id);
		$join_date = $user_data["join_date"];
		
		return isset($join_date) ? $join_date : false;	
	}
	
	public static function user_full_name($user_id) {
		return false;	
	}
	
	public static function user_view_name($user_id) {
		
		if(!$username = self::user_full_name($user_id))
		{
			$username = ucwords(explode("@", self::user_email($user_id))[0]);	
		}
		
		return $username;
			
	}
	
	
	
}

?>