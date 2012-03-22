<html lang="en"><head>

		<?php if( defined('BASE_HREF') ): ?>
		<base href = '<?php echo BASE_HREF ?>'>
		<?php endif; ?>

		<meta charset="utf-8"/>
		<title><?php echo SITE_NAME ?> - <?php echo ( isset($this -> model -> title ) ) ? $this -> model -> title : SITE_TAG; ?> </title> 

		<?php if( defined('META_DESCRIPTION') ): ?>
		<meta name="description" content="<?php echo META_DESCRIPTION?>"/>
		<?php endif; ?>

		<?php if( defined('META_KEYWORDS') ): ?>
		<meta name="keywords" content="<?php echo META_KEYWORDS ?>"/>
		<?php endif; ?>

		<meta name="author" content="">

		<?php if( isset( $this -> helper ) ) echo $this -> helper -> loadCss() ; ?>
		<?php if( isset( $this -> helper ) ) echo $this -> helper -> loadJs() ; ?>
		<?php if( isset( $this -> analytics) )  echo ($this -> analytics -> track()); ?>

		<!-- Le styles -->
		<link rel="stylesheet" href="../assets/css/bootstrap.css">
		<style>
			body {
				padding-top: 100px; /* 60px to make the container go all the way to the bottom of the topbar */
			}

			.logo-small {
				max-width: 20px;
				max-height: 20px;
			}
				
			.sec-nav{
				 background-color: rgba(0, 0, 0, 0.45);
			}
		</style>

		<!-- Le fav and touch icons -->
		<link href="images/favicon.ico" rel="shortcut icon">
		<link href="images/apple-touch-icon.png" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
	</head>

	<body>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<?php if ( defined('DEFAULT_LOGO_PATH') ): ?>
					<img class="logo-small brand" src="<?php echo DEFAULT_LOGO_PATH?>"/>
					<?php endif; ?>
					<a class="brand" href="<?php echo DEFAULT_CONTROLLER?>"><?php echo SITE_NAME; ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<?php echo $this -> helper -> menuLinks('nav') ?>
						</ul>
						<?php if ( Session::open() -> get('logged_in') ): ?>
						<p class="navbar-text pull-right">
						Logged in as <a href="?user"><?php echo  Session::open() -> get('username') ?></a>
						</p>
						<?php endif; ?>
					</div><!--/.nav-collapse -->
				</div>
			</div>

			<?php if ( isset( $this -> menu -> sec_nav ) ): ?>
			<div class="navbar-inner"  >
				<div class="container-fluid sec-nav" >
					<div class="nav-collapse">
						<ul class="nav">
							<?php echo $this -> helper -> menuLinks('sec_nav') ?>
						</ul>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="container">
			<?php require_once( $this -> view_path ); ?>
			<hr>

			<footer>
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>
		</div> <!-- /container -->

</body></html>
