<?php require_once("../../../../../initialize.php");?>
<?php 
if(!User::is_logged_in())
{
	die();
}
?>
<overlay-title>Add a Staff Member</overlay-title>
<overlay-class>add-staff-member</overlay-class>

<form action="" name="add-staff-member">
	<div class="multi-col padded">
    	<div class="col cols-25">
        	Photo Placeholder
        </div>
        <div class="col cols-75">
        	<div class="multi-col padded">
            	<div class="col cols-50">
                    <input type="text" name="first_name" placeholder="First Name"/>
                </div>
                <div class="col cols-50">
                    <input type="text" name="last_name" placeholder="Last Name"/>
                </div>
            </div>
            
            <input type="text" name="job_title" placeholder="Job Title"/>
        
        	<div class="multi-col padded">
            	<div class="col cols-50">
                    <input type="text" name="home_phone" data-format-type="phone" placeholder="Home Phone"/>
                </div>
                <div class="col cols-50">
                    <input type="text" name="cell_phone" data-format-type="phone" placeholder="Cell Phone"/>
                </div>
            </div>
            
            <input type="text" name="email" placeholder="Email"/>
        </div>
    </div>
    
    <div class="footer">
    	<a class="button gray close-overlay">Cancel</a> <a class="button orange submit">Add Staff Member</a>
    </div>
</form>