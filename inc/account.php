<style>
.account-page {
	font-size: 16px;
	line-height: 16px;
	color: #6C7A89;		
	
}
.account-page .container {
	padding: 25px;
	background-color: #FFF;
	width: calc(75% - 300px);
	min-width: 650px;
}

.account-page .help-icon {
	font-size: 20px;
	line-height: 20px;
}

.account-page h3 .edit-form {
	font-size: 15px;
	line-height: 15px;
	cursor: pointer;
}

.verify-account-wrapper {
	margin-top: 15px;
	padding-bottom: 25px;
	margin-bottom: 25px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #eee;
}

.personal-information-wrapper {
	margin-top: 15px;
	padding-bottom: 25px;
	margin-bottom: 25px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #eee;
}


.password-wrapper {
	margin-top: 15px;
}

.account-page .table-row {
	
	margin-bottom: 15px;
}

.account-page .two-col {
	display: table;
	table-layout: fixed;
	width: 100%;

}
.account-page .two-col > [class$='-col'] {
	display: table-cell;
	vertical-align: middle;
	box-sizing: border-box;
}

.account-page .two-col > .left-col {
	padding-right: 25px;
	width: 150px;
	font-weight: 400;
}
.account-page .two-col > .right-col {
	padding-left: 25px;
}


</style>

<div class="top-page">
    <div class="center page-header-wrapper">
        <h4 class="icon-cog-5-">Account Settings</h4>    
    </div>
</div>

<div class="account-page">
    <div class="container center">
			
        <a href="#" class="button cancel-subscription">Cancel Subscription</a>
        <br><br>
        
       	  <h3>Personal Info <a class="icon-pencil edit-form">Edit</a></h3>
            
            <div class="personal-information-wrapper">
            	<div class="table-row two-col">
                	<div class="left-col">Email</div>
                    <div class="right-col">DylanJCross92@gmail.com</div>
                </div>
                
                <div class="table-row two-col">
                	<div class="left-col">First Name</div>
                    <div class="right-col">Dylan</div>
                </div>
                
                <div class="table-row two-col">
                	<div class="left-col">Last Name</div>
                    <div class="right-col">Cross</div>
                </div>
            </div>
            
            <h3>Password <a class="icon-pencil edit-form">Change</a></h3>
            
            <div class="password-wrapper">
            	<div class="table-row two-col">
                	<div class="left-col">Password</div>
                    <div class="right-col">********</div>
                </div>
            </div>
            
	</div>        
</div>        