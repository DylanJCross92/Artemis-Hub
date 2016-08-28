<?php require_once("../../initialize.php");?>
<?php 
if(User::is_logged_in())
{
	die();	
}

$data = $_POST;

foreach($data as $key => $value) {
	$data[$key] = trim($value);
}

$email = strtolower($data["email"]);
$password = $data["password"];
$last_login = time();

if(filter_var($email, FILTER_VALIDATE_EMAIL) && $password)
{
	$User = new User;
	
	if($User->is_email_registered($email))
	{	
		if($User->login_user($email, $password)) {
			$response = array(
				"error" => false
			);
		}
		else
		{
			$response = array(
				"error" => true
			);	
			
			$response["error_messages"]["email"] = "Email or Password incorrect";
			$response["error_messages"]["password"] = "Email or Password incorrect";
		}
		
	} 
	else
	{
		$response = array(
			"error" => true
		);	
		
		$response["error_messages"]["email"] = "Email or Password incorrect";
	}
	
}
else
{
	$response = array(
		"error" => true
	);	
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response["error_messages"]["email"] = "Invalid Email address";	
	}
	
	if(!$password) {
		$response["error_messages"]["password"] = "Invalid password";	
	}
	
}

$json_response = json_encode($response);	

echo $json_response;
?>