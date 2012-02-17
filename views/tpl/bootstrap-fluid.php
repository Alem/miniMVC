<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title><?php echo SITE_NAME ?> <?php if( defined('SITE_TAG') ) echo ": " . SITE_TAG; ?> </title> 

		<?php if( defined('META_DESCRIPTION') ): ?>
		<meta name="description" content="<?php echo META_DESCRIPTION?>"/>
		<?php endif; ?>

		<?php if( defined('META_KEYWORDS') ): ?>
		<meta name="keywords" content="<?php echo META_KEYWORDS ?>"/>
		<?php endif; ?>

		<meta name="author" content="">

		<?php if( defined('DEFAULT_CSS')  ): ?>
		<?php foreach( explode( ",", DEFAULT_CSS ) as $src ): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo DEFAULT_MEDIA_PATH . 'css/' . $src . '.css'; ?>"/>
		<?php endforeach; ?>
		<?php endif; ?>

		<?php if( defined('DEFAULT_JAVASCRIPT')  ): ?>
		<?php foreach( explode( ",", DEFAULT_JAVASCRIPT ) as $src ): ?>
		<script type = "text/javascript" src ='<?php echo DEFAULT_MEDIA_PATH . 'js/' . $src . '.js'; ?>'> 
		</script>
		<?php endforeach; ?>
		<?php endif; ?>

		<?php if( isset( $this -> analytics) )  echo ($this -> analytics -> track()); ?>

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<style type="text/css">
			body {
				padding-top: 60px;
			}
		</style>

		<link rel="shortcut icon" href="media/img/favicon.ico">
		<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	</head>

	<body>

		<div class="topbar">
			<div class="topbar-inner">
				<div class="container-fluid">
					<?php if ( defined('DEFAULT_LOGO_PATH') ): ?>
					<img class="brand" src="<?php echo DEFAULT_LOGO_PATH?>"/>
					<?php endif; ?>
					<a class="brand" href="?<?php echo DEFAULT_CONTROLLER?>"><?php echo SITE_NAME; ?></a>
					<ul class="nav">
						<?php if ( isset( $this -> menu -> nav ) ): ?>
						<?php foreach( $this -> menu -> nav as $name => $href): ?>
						<li class ="<?php if($href == $_SERVER['QUERY_STRING']) echo 'active';?>">
						<a href="?<?php echo $href ?>"><?php echo $name ?></a>
						</li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
					<?php if ( isset( $_SESSION['logged_in'] ) ): ?>
					<p class="pull-right">Logged in as <a href="?user"><?php echo $_SESSION['username'] ?></a></p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<?php if((isset($this -> menu -> sidebar) )): ?>
			<div class="sidebar">
				<div class="well">
					<ul>
						<h5>NAVIGATION</h5>
						<?php foreach( $this -> menu -> sidebar as $name => $href): ?>
						<a href="?<?php echo $href ?>"><?php echo $name ?></a> <br/>
						<?php endforeach; ?>
					</ul>
				</div>
				<? endif;?>
			</div>

			<div class="content">
				<!-- Main hero unit for a primary marketing message or call to action -->
				<div class="hero-unit">
					<br/>
					<?php require_once( SERVER_ROOT . DEFAULT_VIEW_PATH . $view . '.php'); ?>
				</div>

				<hr>

				<footer>
				<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
				</footer>
			</div>
		</div>
	</body>
</html>
