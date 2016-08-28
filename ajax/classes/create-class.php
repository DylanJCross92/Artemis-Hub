<?php require_once("../../initialize.php");?>
<?php 
if(!User::is_logged_in())
{
	die();	
}

$data = $_POST;

foreach($data as $key => $value) {
	$data[$key] = trim($value);
}

$name = $data["name"];
$instructor = $data["instructor"];
$pay_rate = $data["pay_rate"];
$description = $data["description"];
$type = $data["type"];
$capacity = $data["capacity"];
$waitlist = $data["waitlist"];
$start_date = $data["start_date"];
$start_time = $data["start_time"];
$end_time = $data["end_time"];
$repeat = $data["repeat"];
$last_login = time();


if(!$name || !$pay_rate) {
	
	$response = array(
					"error" => true
				);
			
			
	foreach($data as $key => $value) {
		if(!$value) {
			$response["error_messages"][$key] = "Required";	
		}
	}
	
	$response["error_messages"]["name"] = "Class name is required";
	$response["error_messages"]["pay_rate"] = "A pay rate is required";
}
else
{
	$response = array(
					"error" => false
				);	
}

$json_response = json_encode($response);	

echo $json_response;

?>