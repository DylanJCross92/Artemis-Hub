<?php require_once("../../../initialize.php");?>
<?php 
if(!User::is_logged_in())
{
	die();
}
?>

<form class="create-class-form">
	
    <div class="multi-col padded">
        <div class="col cols-66">
            <label>Name</label>
            <input name="name" type="text" placeholder="Name" />
            
            <div class="multi-col padded">
                <div class="col cols-50">
                    <label>Instructor</label>
                    <select class="fancySelect" name="instructor">
                        <option value="">--</option>
                        <option value="1">John Grayer</option>
                        <option>Laura Reed</option>
                    </select> 
                </div>
                <div class="col cols-50">
                	<label>Pay Rate</label>
                    <select class="fancySelect" name="pay_rate">
                        <option value="">--</option>
                        <option value="1">Use Instructors Pay Rate</option>
                        <option>Custom Pay Rate</option>
                    </select> 
                </div>
            </div>
            
            <div class="multi-col padded">
                <div class="col cols-33">
                    
                            
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
            
    	</div>
    	<div class="col cols-33">
            <label>Class Type</label>
            <select class="fancySelect" name="type">
                <option value="">--</option>
                <option value="1">Mat Pilates</option>
                <option>Pilates Reformer</option>
                <option data-class="action create-class-type">Create Class Type</option>
            </select>
            
            <div class="multi-col padded">
                <div class="col">
                    <label>Capacity</label>
                    <select name="capacity" class="fancySelect">
                        <option value="">--</option>
                        <?php $i=1; while($i <= 25) {?>
                            <option><?php echo $i?></option>
                        <?php $i++;}?>
                    </select>  
                </div>
                <div class="col">
                    <label>Waitlist</label>
                    <select name="waitlist" class="fancySelect">
                        <option value="">--</option>
                        <?php $i=1; while($i <= 10) {?>
                            <option><?php echo $i?></option>
                        <?php $i++;}?>
                    </select>
                </div>
            </div>
            
            <label>Date</label>
            <input name="start_date" type="text" class="air-datepicker" placeholder="mm/dd/yyyy"/>
            
            <div class="multi-col padded">
                <div class="col">
                	<label>Start Time</label>
                    <input name="start_time" type="text" placeholder="hh:mm"/>
            	</div>
                <div class="col">
                	<label>End Time</label>
                    <input name="end_time" type="text" placeholder="hh:mm"/>
                </div>
       		</div>
            
            <!--
            <div class="multi-col padded">
                <div class="col">
                    <label>End Date</label>
                    <input name="end-date" type="text" class="air-datepicker" />
            	</div>
                <div class="col">
                	<label>Start Date</label>
                    <input name="start-date" type="text" class="air-datepicker" />
                </div>
       		</div>-->
            
            <label>Repeat</label>
            <select class="fancySelect" name="repeat">
                <option value="">--</option>
                <option value="1">Never</option>
                <option>Weekly</option>
                <option>Monthly</option>
                <option>Other</option>
            </select> 
            
        </div>
      
    </div>
    
    <div class="footer">
    	<a class="button gray close-overlay">Cancel</a> <a class="button orange submit create-class-btn">Create Class</a>
    </div>

</form>