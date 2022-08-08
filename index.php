<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Calendar Class Development</title>
	<link rel="stylesheet" href="./style/css/basic-calendar.css" />
</head>

<?php
	require_once("./BasicCalendar.php");
	$cal = new BasicCalendar(array(
		"month" => 5,
		"year" => 2022,
	));
	$cal->set_default_day_colors("black", "yellow");
	// $cal->set_default_day_text("Avail");
	$cal->set_day_colors(array(
		'2022-5-1' => array('pink', 'blue'),
		'2022-5-8' => array('pink', 'blue'),
		'2022-5-31' => array('pink', 'blue'),
	));
		$cal->set_day_text(array(
		'2022-5-1' => 'Avail',
		'2022-5-8' => 'Avail',
		'2022-5-8' => 'Avail',
	));

	book_days($cal, array('2022-5-14','2022-5-15'));

	function book_days($cal, $days_array) {
		$color_array = array();
		$text_array = array();
		foreach($days_array as $day) {
			$color_array[$day] = array('black', 'orange');
			$text_array[$day] = "Booked";
		}
		$cal->set_day_colors($color_array);
		$cal->set_day_text($text_array);
	}

?>
<body>
	<div>
		<h1>Calendar Class Development Page</h1>
	</div>
	<div class="main">
		<h2><?php echo $cal->display() ?></h2>
	</div>
</body>
</html>