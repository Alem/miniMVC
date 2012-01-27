<!DOCTYPE html>
<html>
<head>
	<title><?php echo SITE_NAME ?> <?php if( isset($this -> model -> data['title'])) echo " - " . $this -> model -> data['title']; ?> </title> 
	<link type="text/css" rel="stylesheet" href="media/css/bootstrap.css"/>
	<link type="text/css" rel="stylesheet" href="media/css/html5.css"/>
	<link type="text/css" rel="stylesheet" href="media/css/main.css"/>
</head>

<body>
	<div id="header">
		<a href="?"><?php echo SITE_NAME; ?></a>
	</div>

	<div id="wrapper">

		<?php if((isset($this -> model -> data['sidebar']) )): ?>
		<div id="left_sidebar">
			<h4>NAVIGATION</h4>
			<? echo $this -> model -> data['sidebar']; ?>
		</div>
		<? endif;?>
	

		<div id="content">
			<?php require_once( SERVER_ROOT . DEFAULT_VIEW_PATH . $view . '.php'); ?>
		</div>

		<?php if( (isset($this -> model -> data['r_top_sidebar']) ) || (isset($this -> model -> data['r_bot_sidebar']) ) ): ?>
		<div id="right_sidebar">

			<?php if((isset($this -> model -> data['r_top_sidebar']) )): ?>
			<div id="right_top_sidebar">
				<h4>TOP SIDEBAR</h4>
				<?php echo $this -> model -> data['r_top_sidebar']; ?>
			</div>
			<? endif;?>

			<?php if((isset($this -> model -> data['r_bot_sidebar']) )): ?>
			<div id="right_bottom_sidebar">
				<h4>BOTTOM SIDEBAR</h4>
				<?php echo $this -> model -> data['r_bot_sidebar']; ?>
			</div>
			<? endif;?>
		</div>
		<? endif;?>

	</div>

	<div id="footer">
		(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a>
	</div>
</body>
<html>
