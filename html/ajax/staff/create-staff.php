<?php require_once("../../../initialize.php");?>
<?php 
if(!User::is_logged_in())
{
	die();
}

$data = $_POST;

foreach($data as $key => $value) {
	$data[$key] = trim($value);
}

$first_name = $data["first_name"];
$last_name = $data["last_name"];
$job_title = $data["job_title"];
$home_phone = $data["home_phone"];
$cell_phone = $data["cell_phone"];
$email = $data["email"];

//TODO: MOVE TO FORM
//TODO: Create code for user types and permissions to store in database as numericals, but reference as "staff", "admin", etc.
$data["user_permissions"] = "admin";

$response = array(
	"error" => false
);	
	
if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	$response["error"] = true;
	$response["error_messages"]["email"] = "This is not a valid Email Address";	
}

if(!$first_name)
{
	$response["error"] = true;
	$response["error_messages"]["first_name"] = "Please enter a valid First Name";		
}

if(!$last_name)
{
	$response["error"] = true;
	$response["error_messages"]["last_name"] = "Please enter a valid Last Name";		
}

if(!$response["error"])
{
	$Staff = new Staff;
	if(!$Staff->create_staff($data))
	{
		$response["error"] = true;
		$response["error_messages"]["email"] = "This email is already registered";			
	}
}

$json_response = json_encode($response);	

echo $json_response;
?>
