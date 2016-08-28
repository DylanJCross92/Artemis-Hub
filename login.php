<?php require_once("initialize.php");
if(User::is_logged_in())
{
	redirect("html/dashboard");
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Login | Artemis Hub</title>
<link rel="stylesheet" href="css/resets.css" type="text/css" />
<link rel="stylesheet" href="css/login.css" type="text/css" />
<link rel="stylesheet" href="html/icons/flaticon.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/login.js"></script>
</head>
<body>

<div class="login-wrapper">
	
	<div class="login-container">
        <div class="header">
            <img class="asterisk-logo" src="assets/asterisk.svg"/>
            <div class="login-tagline">Artemis <span class="arrow flaticon-direction202 no-margin"></span> <span class="highlight">Club Login</span></div>
        </div>
    	<form class="signin-form">
            <input name="email" type="text" placeholder="Email address"/>
            <input name="password" type="password" placeholder="Password"/>
        </form>
        <a class="submit signin-btn button orange"/>Login</a>
        <a class="forgot-password" href="#">Forgot Password?</a>
    </div>
	<div class="copyright">
    	© 2015 Benefitness. All rights reserved.
    </div>
</div>

</body>
</html>