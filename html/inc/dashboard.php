<?php 
	$schedule_list = array(
		array(
			"date" => strtotime("now"),
			"list" => array(
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7AM"), "end_datetime" => strtotime("8:30AM"), "color" => "dark-yellow"),
				array("title" => "Cardio Heartio", "instructor" => "Bethany Heart", "begin_datetime" => strtotime("12PM"), "end_datetime" => strtotime("1:30PM"), "color" => "blue"),
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7PM"), "end_datetime" => strtotime("8:30PM"), "color" => "red")
			),
		),
		array(
			"date" => strtotime("24 hours"),
			"list" => array(
				array("title" => "Pilates Reformer", "instructor" => "Jane Richards", "begin_datetime" => strtotime("9AM"), "end_datetime" => strtotime("10:30AM"), "color" => "blue"),
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("12PM"), "end_datetime" => strtotime("1:30PM"), "color" => "green", "capacity" => "full"),
				array("title" => "Cardio Heartio", "instructor" => "Bethany Heart", "begin_datetime" => strtotime("5PM"), "end_datetime" => strtotime("6:30PM"), "color" => "purple"),
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7PM"), "end_datetime" => strtotime("8:30PM"), "color" => "dark-gray")
			),
		),
		array(
			"date" => strtotime("48 hours"),
			"list" => array(
				array("title" => "Cardio Heartio", "instructor" => "Bethany Heart", "begin_datetime" => strtotime("8AM"), "end_datetime" => strtotime("9:30AM"), "color" => "dark-gray"),
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7PM"), "end_datetime" => strtotime("8:30PM"), "color" => "green")
			),
		),
		array(
			"date" => strtotime("72 hours"),
			"list" => array(
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7PM"), "end_datetime" => strtotime("8:30PM"), "color" => "blue", "capacity" => "full")
			),
		),
		array(
			"date" => strtotime("120 hours"),
			"list" => array(
				array("title" => "Cardio Heartio", "instructor" => "Bethany Heart", "begin_datetime" => strtotime("8AM"), "end_datetime" => strtotime("9:30AM"), "color" => "red"),
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7PM"), "end_datetime" => strtotime("8:30PM"), "color" => "dark-gray")
			),
		),
		/*array(
			"date" => strtotime("144 hours"),
			"list" => array(
				array("title" => "Pilates", "instructor" => "Jane Richards", "begin_datetime" => strtotime("7PM"), "end_datetime" => strtotime("8:30PM"), "color" => "green")
			),
		),*/
	
	);

?>
<!-- Generate invisible blocks for every 30 minutes to click and create new -->
<div class="dashboard-page">
    <ul class="schedule">
    
    	<?php foreach($schedule_list as $key => $item) {?>
    	<li class="<?php if($key==0){ echo "today";}?>">
        	<div class="heading">
            	<div class="date"><?php echo date("j", $item["date"]);?></div>
                <div class="day"><?php echo date("l", $item["date"]);?></div>
            </div>
            
            <?php
			if(array_key_exists("list", $item)) {?>
				<ul class="list">
				<?php foreach($item["list"] as $list) {?>
                    <li class="<?php echo $list["color"];?> <?php if(isset($list["capacity"])){echo $list["capacity"];}?>">
                    	<div class="inner-wrapper">
                            <div class="datetime"><?php echo date("g:iA", $list["begin_datetime"])?> - <?php echo date("g:iA", $list["end_datetime"])?></div>
                            <div class="details">
                                <a href="#" class="title"><?php echo $list["title"]?></a> with <a href="#" class="instructor"><?php echo $list["instructor"]?></a>
                            </div>
                        </div>
                    </li>
                <?php }?>
            	</ul>
			<?php }?>
       	
        </li>
        <?php }?>
    </ul>
</div>