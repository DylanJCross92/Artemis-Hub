<?php
$id = $_POST["id"];
?>

<div class="multi-col datetime">
	<div class="col left">
        <div class="date-wrapper">
        	<div class="day">02</div>
        	<div class="month">Dec</div>
        </div>
    </div>
    <div class="col middle">
    	<div class="time">Tuesday <span class="at">at</span> 10:00am - 11:00am</div>
    </div>
    
    <div class="col instructor">
        <img class="thumbnail" src="assets/instructor_sq_thumb.png"/>   
    </div>
    <div class="col instructor-info">
    	<div class="name">Kaitlyn Raymos</div>
    	<div class="phone flaticon-telephone60 no-margin">(207) 555-5332</div>
        <div class="email flaticon-envelope97 no-margin"><a href="mailto:kaitlyn@artemis.com">kaitlyn@artemis.com</a></div>
    </div>
   
</div>

<div class="booked-capacity">
   
    <div class="progress-bar-wrapper">
        <div class="progress-bar" style="width: <?php echo ((5/10)*100)."%"; ?>;"></div>
    </div>
    <div class="label-wrapper">
    	 <div class="label">5 of 10 Booked</div>
    </div>
</div>

<div class="book-member">
	<a class="button gray">Book Member</a>
</div>

<ul class="attendees-list">
	<?php 
	$names = array(
		array("first_name" => "Arielle", "last_name" => "Schoemaker"),
		array("first_name" => "Tommy", "last_name" => "Pedersen"),
		array("first_name" => "Leonor", "last_name" => "Buskirk"),
		array("first_name" => "Norris", "last_name" => "Hampshire")
	);
	
	usort($names, function($a, $b)
	{
		return strcmp($a["first_name"], $b["first_name"]);
	});
	?>
	<?php $count=0; while($count < count($names)) { ?>
	<?php 
		$firstname = $names[$count]["first_name"];
		$lastname = $names[$count]["last_name"];
		$name = $firstname." ".$lastname;
	?>
	<li class="<?php if($count < 2){?>checked-in<?php } else if($count == 3){?>cancelled<?php }?>">
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
			</div>
			
			<div class="col phone-number">
				(207) 691-5524
			</div>
			
            <div class="col email-address">
				email@artemis.com
			</div>
            
          	<div class="col options">
                <a class="check-in flaticon-right16 no-margin"></a>
                <a class="cancel flaticon-maths86 no-margin"></a>
                <a class="late-cancel flaticon-cross31 no-margin"></a>
            </div>
			
		</div>
	</li>
	<?php $count++; }?>

</ul>