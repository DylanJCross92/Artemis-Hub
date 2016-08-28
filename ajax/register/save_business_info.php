<?php require_once("../../initialize.php");?>
<?php 
$data = $_POST;

foreach($data as $key => $value) {
	$data[$key] = trim($value);
}

$business_name = $data["business_name"];
$address = $data["address"];
$city = $data["city"];
$zipcode = $data["zipcode"];
$phone_number = $data["phone_number"];

if($business_name && $address && $city && $zipcode && $phone_number)
{
	$User = new User;
	$CompanyID = $User->get_user_company_id();
	
	$Company = new Company;
	$Company->update_company_name($CompanyID, $business_name);
	
	$Company->update_business_info($CompanyID, array(
		"address" => $address,
		"city" => $city,
		"zipcode" => $zipcode,
		"phone_number" => $phone_number
	));
	
	$response = array(
		"error" => false
	);	
	
}
else
{
	$response = array(
		"error" => true
	);	
	
	if(!$business_name) {
		$response["error_messages"]["business_name"] = "Invalid Business Name";	
	}
	
	if(!$address) {
		$response["error_messages"]["address"] = "Invalid Address";	
	}
	
	if(!$city) {
		$response["error_messages"]["city"] = "Invalid City";	
	}
	
	if(!$zipcode) {
		$response["error_messages"]["zipcode"] = "Invalid Zipcode";	
	}
	
	if(!$phone_number) {
		$response["error_messages"]["phone_number"] = "Invalid Phone Number";	
	}
	
}

$json_response = json_encode($response);	

echo $json_response;
?>