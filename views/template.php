<!DOCTYPE html>
<html>
<head>
	<title> </title>
	<link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>

<body>
	<div id="header">
		ZA FRAMEWORK
	</div>

	<div id="left_sidebar">
		<h4>LEFT SIDEBAR</h4>
		<?php echo (isset($data['l_sidebar']) ) ? $data['l_sidebar'] : "Example"; ?>
	
	</div>

	<div id="content">
		<?php require_once( SERVER_ROOT . '/views/' . $view . '.php'); ?>
	</div>

	<div id="right_sidebar">
		<div id="right_top_sidebar">
			<h4>RIGHT - TOP SIDEBAR</h4>
			<?php echo (isset($data['r_top_sidebar']) ) ? $data['r_top_sidebar'] : "Example"; ?>
		</div>

		<div id="right_bottom_sidebar">
			<h4>RIGHT - BOTTOM SIDEBAR</h4>
			<?php echo (isset($data['r_bot_sidebar']) ) ? $data['r_bot_sidebar'] : "Example"; ?>
		</div>
	</div>

	<div id="footer">
		(c) ZA Framework - <?php echo date("Y"); ?>
	</div>
</body>
<html>
