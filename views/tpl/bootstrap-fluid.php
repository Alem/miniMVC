<!DOCTYPE html>
<html lang="en"><head>
		<meta charset="utf-8">
		<title><?php echo SITE_NAME ?> <?php if( isset($this -> model -> data['title'])) echo " - " . $this -> model -> data['title']; ?> </title> 
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<link href="media/css/bootstrap.css" rel="stylesheet">
		<link href="media/css/bs-extra.css" rel="stylesheet">
		<style type="text/css">
			body {
				padding-top: 60px;
			}
		</style>

		<link rel="shortcut icon" href="images/favicon.ico">
		<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
	</head>

	<body>

		<div class="topbar">
			<div class="topbar-inner">
				<div class="container-fluid">
					<a class="brand" href="?<?php echo DEFAULT_CONTROLLER?>"><?php echo SITE_NAME; ?></a>
					<ul class="nav">
						<li class="active"><a href="?<?php echo DEFAULT_CONTROLLER?>">Home</a></li>
						<?php if ( ( $this -> menu -> nav() ) ): ?>
						<?php foreach( $this -> menu -> nav() as $name => $href): ?>
						<li><a href="?<?php echo $href ?>"><?php echo $name ?></a></li>
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
			<div class="sidebar">
				<div class="well">
					<ul>
						<?php if((($this -> menu -> sidebar()) )): ?>
						<h5>NAVIGATION</h5>
						<?php foreach( $this -> menu -> sidebar() as $name => $href): ?>
						<a href="?<?php echo $href ?>"><?php echo $name ?></a> <br/>
						<?php endforeach; ?>
						<? endif;?>
					</ul>
				</div>
			</div>
			<div class="content">
				<!-- Main hero unit for a primary marketing message or call to action -->
				<div class="hero-unit">
					<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
					<br/>
					<?php require_once( SERVER_ROOT . DEFAULT_VIEW_PATH . $view . '.php'); ?>
				</div>
<!--
					<p><a class="btn primary large">Learn more »</a></p>
				<div class="row">
					<div class="span6">
						<h2>Heading</h2>
						<p><a class="btn" href="#">View details »</a></p>
					</div>
					<div class="span5">
						<h2>Heading</h2>
						<p><a class="btn" href="#">View details »</a></p>
					</div>
					<div class="span5">
						<h2>Heading</h2>
						<p><a class="btn" href="#">View details »</a></p>
					</div>
				</div>

				<hr>

-->

				<footer>
				<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
				</footer>
			</div>
		</div>


</body><link rel="stylesheet" type="text/css" href="data:text/css,"></html>
