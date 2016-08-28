<?php require_once("../../initialize.php");?>
<?php 
$data = $_POST;

foreach($data as $key => $value) {
	$data[$key] = trim($value);
}

$email = strtolower($data["email"]);
$password = $data["password"];
$password_retyped = $data["password_retyped"];
$join_date = time();

if(filter_var($email, FILTER_VALIDATE_EMAIL) && $password && ($password == $password_retyped))
{
	$User = new User;
	
	if(!$User->is_email_registered($email))
	{
		$user_id = $User->create_user($email, $password);
		
		$Company = new Company;
		$company_id = $Company->create_company();
		
		$Company->assign_user_to_company($company_id, $user_id);
		
		if($User->login_user($email, $password)) {
			$response = array(
				"error" => false
			);
		}
		
	} 
	else
	{
		$response = array(
			"error" => true
		);	
		
		$response["error_messages"]["email"] = "Email already in use";
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
	
	if($password && ($password != $password_retyped)) {
		$response["error_messages"]["password_retyped"] = "Passwords must match";	
	}
}

$json_response = json_encode($response);	

echo $json_response;
?>