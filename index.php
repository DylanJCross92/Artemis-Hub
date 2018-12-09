<?php require_once("initialize.php");?>
<?php 
	if(!User::is_logged_in())
	{
		die(redirect("/portal/login.php"));
	}
	
	$user_id = User::user_id();
	
	$page = isset($_GET["page"]) ? $_GET["page"] : "dashboard";
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>Artemis at Benefitness</title>
<link href='https://fonts.googleapis.com/css?family=Nunito:300,400,700|Mandali|Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
<link href="library/fontello.css" rel="stylesheet" type="text/css">
<link href="library/flaticon.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="library/selectordie.css">
<link rel="stylesheet" href="library/global.css">
<link rel="stylesheet" href="library/animation.css">
<link href="library/portal.css" rel="stylesheet" type="text/css">
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/plugins/jquery-ui.min.js"></script>
<script src="js/plugins/jquery.payment.js"></script>
<script src="js/plugins/jquery.mutations.data.js"></script>
<script src="js/plugins/selectordie.min.js"></script>
<!--<script src="js/chart.js"></script>-->
<script src="js/Validator.js"></script>
<script src="js/sitewide.js"></script>
</head>
<body>
<div class="portal-container">
	<div class="sidebar">
    	<div class="head-wrapper">
            <a class="site-logo" href="/"><img class="logo" src="assets/logo-retina.jpg"/></a>
        
            <div class="user-wrapper">
            	<div class="left">
            		<div class="user-thumb"><!--<div class="user-icon icon-user-3"></div>--> <img class="user-icon" src="assets/1425876105_boy.svg"/></div>
            	</div>
                <div class="right">
              		<div class="name"><?php echo User::user_view_name($user_id);?></div>
                   	<?php 
					
					if(User::subscription_active($user_id))
					{
					?>
                    <div class="membership-level icon-star-8">Premium Member</div>
                    <?php }
					else
					{?>
                    	<a class="membership-level" href="?page=select-plan">Go Premium</a>
                    <?php 
					}
					?>
                </div>
            </div>
            
            <ul class="navigation">
            	<li <?php if($page == "dashboard"){?>class="current"<?php }?>><a class="icon-gauge-1" href="?page=dashboard">Dashboard</a></li>
                <li <?php if($page == "classes" || $page == "class"){?>class="current"<?php }?>><a class="icon-video" href="?page=classes">Classes</a></li>
                <!--<li <?php if($page == "log"){?>class="current"<?php }?>><a class="icon-th-list" href="?page=log">Log</a></li>-->
                <li <?php if($page == "nutrition"){?>class="current"<?php }?>><a class="icon-help-circled-1" href="?page=nutrition">Nutrition Tips</a></li>
                <li <?php if($page == "account"){?>class="current"<?php }?>><a class="icon-cog-5" href="?page=account">Account</a></li>
            </ul>
            
        </div>
        
        <div class="sidebar-footer">
        	<img src="assets/artemis-updated-newcut-white_retina.png" style="width: 100%; margin-top: -275px;"/>
        
        	<a href="index.php?page=home" class="button small gray logout logout-button icon-logout">Log Out</a>
       	  	<div class="copyright-wrapper">© <?php echo date("Y");?> Benefitness. All rights reserved.</div>
        </div>
        
    </div>
    <div class="content">
		<?php
		include("inc/".$page.".php");
		?>
    </div>
</div>
</body>
</html>