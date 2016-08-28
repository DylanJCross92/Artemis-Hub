<?php require_once("initialize.php");

	$step = isset($_GET["step"]) ? $_GET["step"] : 1;
	
	if(!User::is_logged_in()) {
		
		if($step != 1)
		{
			redirect("?step=1");
		}
	}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Register | Artemis Hub</title>
<link rel="stylesheet" href="css/resets.css" type="text/css" />
<link rel="stylesheet" href="css/register.css" type="text/css" />
<link rel="stylesheet" href="html/icons/flaticon.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/register.js"></script>
</head>
<body>
<?php 
	
	$stepTitle = array(1 => "Account", 2 => "Business Info", 3 => "Review");
?>

<div class="register-wrapper">
	
    <div class="multi-col header">
    	<div class="col left logo">
        	<img class="asterisk-logo" src="assets/artemis-logo.svg"/>
        </div>
        <div class="col right tagline">
        	Step <?php echo $step?> of 3 — <span class="highlight"><?php echo $stepTitle[$step];?></span>
        </div>
    </div>
    
	<div class="register-container">
    	
        <?php if($step == 1){?>
    	<h2>Create your Account</h2>
        <div class="subheader">This will be the master account</div>
        <?php 
			if(User::is_logged_in()) {
				redirect("?step=2");
			}
		?>
    	<form class="create-account-form">
        	<fieldset>
                <input name="email" type="text" placeholder="Email address"/>
                <input name="password" type="password" placeholder="Password"/>
                <input name="password_retyped" type="password" placeholder="Confirm Password"/>
            </fieldset>
        </form>
        
        <div class="multi-col prev-next-wrapper">
        	<div class="col left">
            
            </div>
            <div class="col right">
            	<a href="?step=2" class="submit create-account-btn button orange"/>Next Step</a>
            </div>
        </div>
        
		<?php } else if($step == 2){?>
        
        <h2>Business Information</h2>
        <div class="subheader">All Information is required</div>
        <?php 
			//$User = new User;
			//$User->logout_user();
			
			if(User::is_logged_in()) {
				
				$User = new User;
				$CompanyID = $User->get_user_company_id();
				
				$Company = new Company;
				$company_info = $Company->get_company_info($CompanyID);
				$business_info = $Company->get_business_info($CompanyID);
			}
		?>
    	<form class="business-info-form">
            <input name="business_name" type="text" placeholder="Business Name" value="<?php echo $company_info["name"]?>"/>
            <input name="address" type="text" placeholder="Address" value="<?php echo $business_info["address"]?>"/>
            <div class="multi-col">
            	<div class="col cols-66">
                	<input name="city" type="text" placeholder="City" value="<?php echo $business_info["city"]?>"/>
                </div>
                <div class="col cols-33">
                	<input name="zipcode" type="text" placeholder="Zipcode" value="<?php echo $business_info["zipcode"]?>"/>
                </div>
            </div>
            <input name="phone_number" type="text" placeholder="Phone Number" value="<?php echo $business_info["phone_number"]?>"/>
        </form>
        
        <div class="multi-col prev-next-wrapper">
        	<div class="col left">
            	<!--<a href="?step=1" class="button gray"/>Previous Step</a>-->
            </div>
            <div class="col right">
            	<a href="?step=3" class="submit business-info-btn button orange"/>Next Step</a>
            </div>
        </div>
        
        <?php } else if($step == 3){?>
        
        <h2>Review</h2>
        <div class="subheader">Review the information provided</div>
        
        <h3>Account Information</h3>
        
        <?php 
			
			if(User::is_logged_in()) {
				$User = new User;
				$user_info = $User->get_user_info();
				$CompanyID = $User->get_user_company_id();
				
				$Company = new Company;
				$company_info = $Company->get_company_info($CompanyID);
				$business_info = $Company->get_business_info($CompanyID);
			}
		?>
        <div class="account-info">
        	<div class="row email">
            	<div class="multi-col">
                	<div class="col cols-33">
                    	<span class="label">Email:</span> 
                    </div>
                    <div class="col cols-66">
                    	<span class="value"><?php echo $user_info["email"]?></span>
                    </div>
                </div>
            </div>
            <!--<div class="row password">
            	<div class="multi-col">
                	<div class="col cols-33">
                    	<span class="label">Password:</span> 
                    </div>
                    <div class="col cols-66">
                    	<span class="value">********</span>
                    </div>
                </div>
            </div>-->
        </div>
        
        <h3>Business Information</h3>
        
        <div class="business-info">
            <div class="row business-name">
            	<div class="multi-col">
                	<div class="col cols-33">
                    	<span class="label">Business Name:</span> 
                    </div>
                    <div class="col cols-66">
                    	<span class="value"><?php echo $company_info["name"]?></span>
                    </div>
                </div>
            </div>
            
            <div class="row business-address">
            	<div class="multi-col">
                	<div class="col cols-33">
                    	<span class="label">Address:</span> 
                    </div>
                    <div class="col cols-66">
                    	<span class="value"><?php echo $business_info["address"]?>,<br><?php echo $business_info["city"]?>, <?php echo $business_info["zipcode"]?></span>
                    </div>
                </div>
            </div>
            
            <div class="row business-address">
            	<div class="multi-col">
                	<div class="col cols-33">
                    	<span class="label">Phone Number:</span> 
                    </div>
                    <div class="col cols-66">
                    	<span class="value"><?php echo $business_info["phone_number"]?></span>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="multi-col prev-next-wrapper">
        	<div class="col left">
            	<a href="?step=2" class="button gray"/>Edit Information</a>
            </div>
            <div class="col right">
            	<a href="html/dashboard" class="submit button orange"/>Complete Setup</a>
            </div>
        </div>
        <?php }?>
        
        
    </div>
	<div class="copyright">
    	© 2015 Benefitness. All rights reserved.
    </div>
</div>

</body>
</html>