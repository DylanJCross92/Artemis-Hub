<?php require_once("../initialize.php");?>
<?php 
if(!User::is_logged_in())
{
	redirect(REDIRECT_ROOT."/login.php");
	die();
}
?>
<!doctype html>
<?php error_reporting(E_ALL);
ini_set('display_errors', 1);?>
<html>
<head>
<meta charset="UTF-8">
<title>Artemis Hub</title>
<base href="/WebWork/Artemis/Hub/Website/html/">
<link rel="stylesheet" href="styles.css" type="text/css" />
<link rel="stylesheet" href="icons/flaticon.css" type="text/css">
<link href="plugins/air-datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="plugins/air-datepicker/dist/js/datepicker.min.js"></script>
<script src="plugins/air-datepicker/dist/js/i18n/datepicker.en.js"></script>
<script src="js/fancySelect.js"></script>
<script src="js/global.js"></script>
</head>
<body>
<div class="loading-wrapper">
	<div class="loading-text left">Artemis</div>
	<div class="loading-icon"></div>
    <div class="loading-text right">Loading..</div>
</div>
<?php $page = isset($_GET["page"]) ? $_GET["page"] : "dashboard";?>
<?php include("functions.php");?>
<div class="dashboard-container">
    <div class="content-wrapper">
    	<div class="sidebar">
        	<div class="logo-wrapper">
        		<img class="logo" src="assets/artemis_logo2.png"/>
            </div>
            
        	<ul class="navigation-menu">
                <li <?php if($page == "dashboard"){?>class="current"<?php }?>><a href="dashboard"><span class="icon flaticon-calendar-icons no-margin"></span>Dashboard</a></li>
                <li <?php if($page == "classes"){?>class="current"<?php }?>><a href="classes"><span class="icon flaticon-list18 no-margin"></span>Classes</a></li>
                <li <?php if($page == "members"){?>class="current"<?php }?>><a href="members"><span class="icon flaticon-user252 no-margin"></span>Members</a></li>
                <li <?php if($page == "staff"){?>class="current"<?php }?>><a href="staff"><span class="icon flaticon-user252 no-margin"></span>Staff</a></li>
                <li <?php if($page == "reporting"){?>class="current"<?php }?>><a href="reporting"><span class="icon flaticon-stats79 no-margin"></span>Reporting</a></li>
                <li <?php if($page == "tools"){?>class="current"<?php }?>><a href="tools"><span class="icon flaticon-levels1 no-margin"></span>Tools</a></li>
            </ul>
            
            <div class="logout-wrapper">
            	<a class="button dark-gray logout-btn">Logout</a>
            </div>
        </div>
        <div class="content">
        	<div class="header-bar">
                <div class="search-wrapper">
                    <form class="search-form" method="post" onsubmit="return false;">
                        <input type="text" class="search-input" placeholder="Search"/>
                        <span class="search-submit flaticon-magnifying-glass34 no-margin"></span>
                    </form>
                </div>
            </div>
        
        	<div class="inner-content"><?php get_page($page);?></div>
        </div>
    </div>
    
</div>

<div class="overlay-wrapper">
    <div class="overlay-container">
        <div class="title-bar">
            <div class="title"></div>
            <div class="close flaticon-cross31 no-margin close-overlay"></div> 
        </div>
        <div class="overlay-body"></div>
    </div>
</div>

</body>
</html>