<?php require_once("initialize.php");?>
<?php 

	if(User::is_logged_in())
	{
		die(redirect("/portal/?page=dashboard"));
	}
	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>Artemis at Benefitness</title>
<link href='https://fonts.googleapis.com/css?family=Quicksand:400,300,700|Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
<link href="library/fontello.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="library/global.css">
<link href="styles.css" rel="stylesheet" type="text/css">
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/Validator.js"></script>
<script src="js/sitewide.js"></script>
<style>
body {
	background-color: #EEEEEE;
}
</style>
<script>
$(function(){
	$('form.signin-form input').on('change blur', function() {
		if($("form.signin-form").find(".error").length>0)
		{
			ValidateForm($(this).closest("form"));
		}
	});
});
</script>
</head>
<body>
<div class="login-wrapper">
	<div class="top-wrapper">
    	<a href="#" class="forgot-password">Forgot Password?</a>
        
       	<div class="sign-in-register-wrapper">
            <a href="/portal/register.php" class="register icon-user-add-1">Sign up</a>
        </div>
    </div>
    <a class="site-logo" href="/">
    	<img class="logo" src="assets/logo-retina.jpg"/>
    </a>
    
    <form class="signin-form" action="javascript:void(0);" method="POST" novalidate>
       	<div class="input-wrapper"><span class="icon-mail-1"></span><input type="email" name="email" class="input" placeholder="Email Address"  data-required="true" data-validation="email" data-min-length="10" data-max-length="50"/></div>
    	<div class="input-wrapper"><span class="icon-key-1"></span><input type="password" name="password" class="input" placeholder="Password"  data-required="true" data-validation="not_empty" data-min-length="6" data-max-length="20"/></div>
    
    	<a class="button login submit-form">Sign in</a>
    </form>
    
</div>

</body>
</html>