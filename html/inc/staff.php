<div class="members-page">
	
    <a class="button orange add-staff-member-btn">Add Staff</a>
    
    <ul class="members-list">
    	<?php 
			$previous = null;
			$names = array(
				array("first_name" => "Arielle", "last_name" => "Schoemaker"),
				array("first_name" => "Tommy", "last_name" => "Pedersen"),
				array("first_name" => "Leonor", "last_name" => "Buskirk"),
				array("first_name" => "Norris", "last_name" => "Hampshire"),
				array("first_name" => "Lane", "last_name" => "Mcclain"),
				array("first_name" => "Eden", "last_name" => "Rembert"),
				array("first_name" => "Kaylene", "last_name" => "Fielder"),
				array("first_name" => "Lavern", "last_name" => "Mustafa"),
				array("first_name" => "Crocia", "last_name" => "Fielder"),
				array("first_name" => "Gina", "last_name" => "Karlin"),
				array("first_name" => "Kaylene", "last_name" => "Fielder"),
				array("first_name" => "Lavern", "last_name" => "Mustafa"),
				array("first_name" => "Brooke", "last_name" => "Smith"),
				array("first_name" => "Gina", "last_name" => "Karlin"),
				array("first_name" => "Cythia", "last_name" => "Ricard"),
				array("first_name" => "Gina", "last_name" => "Karlin")
			);
			
			usort($names, function($a, $b)
			{
				return strcmp($a["first_name"], $b["first_name"]);
			});
		?>
    	<?php $count=0; while($count < 15) { $count++;?>
        <?php 
			$firstname = $names[$count]["first_name"];
			$lastname = $names[$count]["last_name"];
			$name = $firstname." ".$lastname;
			
			$firstLetter = substr($firstname, 0, 1);
			if($previous !== $firstLetter){
			?>
            <div class="item-break-label alphabetical-headers" data-datetime="<?php echo $firstLetter;?>"></div>
            <?php $previous = $firstLetter; } ?>
		<li class="<?php if($count % 2 == 0){?>instructor<?php } else {?>admin<?php }?>">
            <div class="multi-col">
                 <div class="col thumbnail-wrapper">
                    <?php if($count % 2 == 0){?>
                        <img class="thumbnail" src="assets/instructor_sq_thumb.png"/>
                    <?php } else {?>
                        <div class="name-initials"><?php echo substr($firstname, 0, 1);?><?php echo substr($lastname, 0, 1);?></div>
                    <?php }?>
                </div>
                
                <div class="col member-info">
                	<div class="name"><?php echo $name?></div>
                    <div class="role"><?php if($count % 2 == 0){?>Instructor<?php } else {?>Administrator<?php }?></div>
                </div>
                
                <div class="col phone-number">
                	(207) 691-5524
                </div>
                
                <div class="col email-address">email@artemis.com</div>
                
                <div class="col address">Brookline, MA</div>
                
                <div class="col edit">
                    <a class="button gray" href="staff/23">View/Edit</a>
                </div>
                
            </div>
        </li>
        <?php }?>

    </ul>

</div>