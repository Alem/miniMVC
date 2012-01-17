<!DOCTYPE html>
<html>
<head>
	<title> </title>
	<link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>

<body>
	<div id="header">
		HEADER
	</div>

	<div id="left_sidebar">
		LEFT SIDEBAR
	</div>

	<div id="content">
		<?php require_once( SERVER_ROOT . '/views/' . $view . '.php'); ?>
	</div>

	<div id="right_sidebar">
		<div id="right_top_sidebar">
			RIGHT - TOP SIDEBAR
		</div>

		<div id="right_bottom_sidebar">
			RIGHT - BOTTOM SIDEBAR
		</div>
	</div>

	<div id="footer">
		FOOTER
	</div>
</body>
<html>
