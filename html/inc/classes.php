<div class="classes-page">

	<a class="button orange create-new-class-btn">Create a Class</a>

    <!--<h1>Upcoming Classes</h1>-->
    <!--<ul class="upcoming-class-list">
    
    <?php $count=0; while($count < 3) { $count++;?>
        <?php 
           if($count % 2 == 0)
           {
                $location_label = "On Site";
                $location_value = "on-site";
                $location_icon = "flaticon-facebook30";
                $the_location_label = "Benefitness Health Club";
            }
            else
            {
                $location_label = "Webcam";
                $location_value = "webcam";
                $location_icon = "flaticon-cameras11";
                $the_location_label = "SkypeName_84";
            }
        ?>
        <li class="<?php echo $location_value?>">
            <div class="multi-col">
                <div class="col date-time">
                    <span class="time">10:00AM</span>   
                    <span class="length">60 min</span>
                </div>
                <div class="col thumbnail-wrapper">
                    <?php if($count % 2 == 0){?>
                        <img class="thumbnail" src="assets/instructor_sq_thumb.png"/>
                    <?php } else {?>
                        <div class="name-initials">KR</div>
                    <?php }?>
                </div>
                <div class="col class-info">
                    <div><span class="title">Pilates Reformer</span> <span class="with">with</span> <span class="instructor">Kaitlyn Ray</span></div>
                    <div class="location-wrapper">
                        <div class="location-label <?php echo $location_value." ".$location_icon?>" title="<?php echo $location_label?>"></div><div class="location"><?php echo $the_location_label?></div>
                    </div>
                </div>
                <div class="col students">
                    <ul class="students-list">
                        <?php $countD=0; $rand = rand(1, 10) + 5; while($countD < 3) { ?>
                            <li>
                                <?php if($countD % 2 == 0){?>
                                    <img class="thumbnail" src="assets/instructor_sq_thumb.png"/>
                                <?php } else if($countD % 3 == 0){?>
                                    <img class="thumbnail" src="assets/instructor_sq_thumb_2.png"/>
                                <?php } else {?>
                                    <div class="name-initials">DC</div>
                                <?php }?>
                                    
                            </li>
                        <?php $countD++; } ?>
                        
                        <?php if($rand > 4){?>
                            <li class="more-students">
                                <div class="show-more">+<?php echo $rand - 3;?></div>
                            </li>
                        <?php }?>
                    </ul>
                </div>
                <div class="col booked-capacity">
                    <div class="label"><?php echo $rand?> of 20 Booked</div>
                    
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar" style="width: <?php echo (($rand/20)*100)."%"; ?>;"></div>
                    </div>
                    
                </div>
                
                <div class="col edit">
                    <a class="button gray" href="#">View/Edit</a>
                </div>
            
            </div>
        </li>
     <?php }?>
    </ul>
    
    <!--
    <div class="class-reminders">
        
        Number of classes today<br>
        Number of classes with openings<br>
        Other Classes related details...
      
    </div>
    -->

    <ul class="class-list">
    
    	<div class="item-break-label" data-datetime="Tomorrow"></div>
    
    	<?php $count=0; while($count < 25) { $count++;?>
        <?php 
            if($count % 2 == 0)
            {
                $location_label = "On Site";
                $location_value = "on-site";
                $location_icon = "flaticon-facebook30";
                $the_location_label = "Benefitness Health Club";
            }
            else
            {
                $location_label = "Webcam";
                $location_value = "webcam";
                $location_icon = "flaticon-cameras11";
                $the_location_label = "SkypeName_84";
            }
            
            $timestamp = time()+ ((60*60*24) * ($count / 4));
        ?>
        
        <?php if($count % 4 == 0) {?>
            <div class="item-break-label" data-datetime="<?php echo date("l, F jS", $timestamp + (60 * 60 * 24));?>"><?php //echo date("l, F jS", $timestamp);?></div>
        <?php }?>
        
        <li class="<?php echo $location_value?>">
            <div class="multi-col">
                <div class="col date-time">
                    <div class="time">10:00AM</div>   
                    <div class="length">60 min</div>
                </div>
                <div class="col thumbnail-wrapper">
                    <?php if($count % 2 == 0){?>
                        <img class="thumbnail" src="assets/instructor_sq_thumb.png"/>
                    <?php } else {?>
                        <div class="name-initials">KR</div>
                    <?php }?>
                </div>
                <div class="col class-info">
                    <div><span class="title">Pilates Reformer</span> <span class="with">with</span> <span class="instructor">Kaitlyn Ray</span></div>
                    <div class="location-wrapper">
                        <div class="location-label <?php echo $location_value." ".$location_icon?>" title="<?php echo $location_label?>"></div><div class="location"><?php echo $the_location_label?></div>
                    </div>
                </div>
                <div class="col students">
                    <!--<ul class="students-list">
                        <?php $countD=0; $rand = rand(1, 5) + 5; while($countD < 2) { ?>
                            <li>
                                <?php if($countD % 2 == 0){?>
                                    <img class="thumbnail" src="assets/instructor_sq_thumb.png"/>
                                <?php } else if($countD % 3 == 0){?>
                                    <img class="thumbnail" src="assets/instructor_sq_thumb_2.png"/>
                                <?php } else {?>
                                    <div class="name-initials">DC</div>
                                <?php }?>
                                    
                            </li>
                        <?php $countD++; } ?>
                        
                        <?php if($rand > 3){?>
                            <li class="more-students">
                                <div class="show-more">+<?php echo $rand - 3;?></div>
                            </li>
                        <?php }?>
                    </ul>-->
                </div>
                <div class="col booked-capacity">
                    <div class="label"><?php echo $rand?> of 20 Booked</div>
                    
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar" style="width: <?php echo (($rand/20)*100)."%"; ?>;"></div>
                    </div>
                    
                </div>
                
                <div class="col edit">
                    <a class="button gray" href="classes/55">View/Edit</a>
                </div>
            
            </div>
        </li>
    	<?php }?>
    </ul>

</div>