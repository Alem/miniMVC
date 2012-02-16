<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<title><?php echo SITE_NAME ?> <?php if( defined('SITE_TAG') ) echo ": " . SITE_TAG; ?> </title> 

		<?php if( defined('META_DESCRIPTION') ): ?>
		<meta name="description" content="<?php echo META_DESCRIPTION?>">
		<?php endif; ?>

		<?php if( defined('META_KEYWORDS') ): ?>
		<meta name="keywords" content="<?php echo META_KEYWORDS ?>">
		<?php endif; ?>

		<meta name="author" content="">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->


		<?php if( defined('DEFAULT_CSS')  ): ?>
		<?php foreach( explode( ",", DEFAULT_CSS ) as $src ): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo DEFAULT_MEDIA_PATH . 'css/' . $src . '.css'; ?>"/>
		<?php endforeach; ?>
		<?php endif; ?>

		<?php if( defined('DEFAULT_JAVASCRIPT')  ): ?>
			<?php foreach( explode( ",", DEFAULT_JAVASCRIPT ) as $src ): ?>
			<script type = "text/javascript" src ='<?php echo DEFAULT_MEDIA_PATH . 'js/' . $src . '.js'; ?>' /> </script>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if( isset( $this -> analytics) )  echo ($this -> analytics -> track()); ?>
</head>

<body>
	<div id="header">
		<a href="?"><?php echo SITE_NAME; ?></a>
	</div>

	<div id="wrapper">

		<?php if ( isset( $this -> menu -> nav ) ): ?>
		<div id="left_sidebar">
			<h4>NAVIGATION</h4>
			<?php foreach( $this -> menu -> nav as $name => $href): ?>
			<a href="?<?php echo $href ?>"><?php echo $name ?></a>
			<?php endforeach; ?>
		</div>
		<? endif;?>
	

		<div id="content">
			<?php require_once( SERVER_ROOT . DEFAULT_VIEW_PATH . $view . '.php'); ?>
		</div>

		<?php if ( isset( $this -> menu -> sidebar ) ): ?>
		<div id="right_sidebar">
			<div id="right_top_sidebar">
				<h4>TOP SIDEBAR</h4>
				<?php foreach( $this -> menu -> sidebar as $name => $href): ?>
				<a href="?<?php echo $href ?>"><?php echo $name ?></a> <br/>
				<?php endforeach; ?>
			</div>
		</div>
		<? endif;?>

	</div>

	<div id="footer">
		(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a>
	</div>
</body>
<html>
