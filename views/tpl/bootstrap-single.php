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
			.container{
				width:1000px;
			}
			input{
				height:21px;
			}
		</style>

		<!-- Le fav and touch icons -->
		<link href="media/img/favicon.ico" rel="shortcut icon">
		<link href="images/apple-touch-icon.png" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
	</head>
	<body>
		<div class="topbar">
			<div class="fill">
				<div class="container">
					<?php if ( defined('DEFAULT_LOGO_PATH') ): ?>
					<img class="brand" src="<?php echo DEFAULT_LOGO_PATH?>"/>
					<?php endif; ?>
					<a href="?<?php echo DEFAULT_CONTROLLER?>" class="brand"><?php echo SITE_NAME; ?></a>
					<ul class="nav">
						<?php if ( isset( $this -> menu -> nav ) ): ?>
						<?php foreach( $this -> menu -> nav as $name => $href): ?>
						<li class ="<?php if($href == $_SERVER['QUERY_STRING']) echo 'active';?>">
						<a href="?<?php echo $href ?>"><?php echo $name ?></a>
						</li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
			<!-- Main hero unit for a primary marketing message or call to action -->
			<div class="hero-unit">
				<!--
				<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
				-->
				<br/>
				<?php require_once( SERVER_ROOT . DEFAULT_VIEW_PATH . $view . '.php'); ?>
			</div>
			<!-- 
			<div class="row">
				<div class="span6">
					<h2>Heading</h2>
					<p><a href="#" class="btn">View details »</a></p>
				</div>
				<div class="span5">
					<h2>Heading</h2>
					<p><a href="#" class="btn">View details »</a></p>
				</div>
				<div class="span5">
					<h2>Heading</h2>
					<p><a href="#" class="btn">View details »</a></p>
				</div>
			</div>
			Example row of columns -->
			<footer>
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>
		</div> <!-- /container -->
	</body>
</html>
