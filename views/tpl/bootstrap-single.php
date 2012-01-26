<html lang="en"><head>
		<meta charset="utf-8">
		<title><?php echo SITE_NAME ?> <?php if( isset($this -> model -> data['title'])) echo " - " . $this -> model -> data['title']; ?> </title> 
		<meta content="" name="description">
		<meta content="" name="author">
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="media/bootstrap.css" rel="stylesheet">
		<link href="media/bs-extra.css" rel="stylesheet">
		<style type="text/css">
			body {
				padding-top: 60px;
			}
		</style>
		<!-- Le fav and touch icons -->
		<link href="images/favicon.ico" rel="shortcut icon">
		<link href="images/apple-touch-icon.png" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
	</head>
	<body>
		<div class="topbar">
			<div class="fill">
				<div class="container">
					<a href="?<?php echo DEFAULT_CONTROLLER?>" class="brand"><?php echo SITE_NAME; ?></a>
					<ul class="nav">
						<li class="active"><a href="?<?php echo DEFAULT_CONTROLLER?>">Home</a></li>
						<?php if ( isset( $this -> model -> data['nav'] ) ): ?>
						<?php foreach( $this -> model -> data['nav'] as $name => $href): ?>
						<li><a href="?<?php echo $href ?>"><?php echo $name ?></a></li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
			<!-- Main hero unit for a primary marketing message or call to action -->
			<div class="hero-unit">
				<h1><?php echo SITE_NAME; ?> <small><?php echo SITE_TAG; ?></small></h1>
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
</body></html>
