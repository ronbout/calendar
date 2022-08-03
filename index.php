<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Calendar Class Development</title>
</head>

<?php
	require_once("./BasicCalendar.php");
	$cal = new BasicCalendar();

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