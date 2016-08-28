<?php require_once("../../../initialize.php");?>
<?php 
if(!User::is_logged_in())
{
	die();
}
?>


<form class="create-class-form">
	
    <label>Name</label>
    <input name="class_name" type="text" placeholder="Class Name" />
    
    <div class="multi-col padded">
        <div class="col cols-50">
        	<label>Class Type</label>
            <select class="fancySelect" name="class-type">
                <option value="">Class Type:</option>
                <option value="1">Mat Pilates</option>
                <option>Pilates Reformer</option>
                <option data-class="action create-class-type">Create Class Type</option>
            </select>
                    
        </div>
        <div class="col cols-25">
        	  
        </div>
        <div class="col cols-25">
        	<label>Capacity</label>
            <select name="test" class="fancySelect">
                <option value="">Capacity:</option>
                <?php $i=1; while($i <= 25) {?>
                	<option><?php echo $i?></option>
                <?php $i++;}?>
            </select>  
        </div>
        <div class="col cols-25">
            <label>Waitlist</label>
            <select name="test" class="fancySelect">
                <option value="">Waitlist:</option>
                <?php $i=1; while($i <= 10) {?>
                	<option><?php echo $i?></option>
                <?php $i++;}?>
            </select>
        </div>
    </div>
    
    <div class="multi-col padded">
        <div class="col cols-33">
        	<label>Instructor</label>
            <select class="fancySelect" name="class-instructor">
                <option value="">Class Instructor:</option>
                <option value="1">John Grayer</option>
                <option>Laura Reed</option>
            </select>
                    
        </div>
        <div class="col cols-66">
        
        </div>
    </div>
    
    <div class="multi-col padded">
    	<div class="col">
        	<label>Description</label>
    		<textarea name="description" class="input" placeholder="Description"></textarea>
    	</div>
    </div>
    
    <div class="multi-col padded" style="margin-top: 25px">
        <div class="col"></div>
        <div class="col" style="text-align: right;">
            <a class="button gray close-overlay">Cancel</a> <a class="button orange create-class-submit-btn">Create Class</a>
        </div>
    </div>

</form>