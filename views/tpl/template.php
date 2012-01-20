<!DOCTYPE html>
<html>
<head>
	<title> </title>
	<link type="text/css" rel="stylesheet" href="css/style.css"/>
</head>

<body>
	<div id="header">
		<?php echo SITE_NAME; ?>
	</div>

	<div id="wrapper">

		<?php if((isset($data['l_sidebar']) )): ?>
		<div id="left_sidebar">
			<h4>NAVIGATION</h4>
			<? echo $data['l_sidebar']; ?>
		</div>
		<? endif;?>
	

		<div id="content">
			<?php require_once( SERVER_ROOT . '/views/' . $view . '.php'); ?>
		</div>

		<div id="right_sidebar">

			<?php if((isset($data['r_top_sidebar']) )): ?>
			<div id="right_top_sidebar">
				<h4>TOP SIDEBAR</h4>
				<?php echo $data['r_top_sidebar']; ?>
			</div>
			<? endif;?>

			<?php if((isset($data['r_bot_sidebar']) )): ?>
			<div id="right_bottom_sidebar">
				<h4>BOTTOM SIDEBAR</h4>
				<?php echo $data['r_bot_sidebar']; ?>
			</div>
			<? endif;?>
		</div>

	</div>

	<div id="footer">
		(c) <?php echo COMPANY . ' - ' . date("Y"); ?>
	</div>
</body>
<html>