<?php require_once('../../initialize.php');

$email = trim($_POST["email"]);
$password = trim($_POST["password"]);

$field_errors = array();

$User = new User;

if(ValidateSigninForm::not_empty($email) && ValidateSigninForm::not_empty($password))
{	
	if($User->check_user_by_email($email))
	{
		if(!count($field_errors))
		{
			if($User->login_user($email, $password))
			{
				echo ajax_success();
			}
			else
			{
				echo ajax_error(array("invalid_credentials" => ErrorMessage::$SYSTEM_USER_INVALID_CREDENTIALS));	
			}
		}
		else
		{
			echo ajax_error($field_errors);	
		}
	}
	else
	{
		echo ajax_error(array("invalid_credentials" => ErrorMessage::$SYSTEM_USER_INVALID_CREDENTIALS));	
	}
}
else
{
	echo ajax_error(array("invalid_credentials" => ErrorMessage::$SYSTEM_USER_INVALID_CREDENTIALS));	
}

ob_end_flush();
?>