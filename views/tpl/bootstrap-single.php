<!DOCTYPE html>
<html lang="en">
	<head>
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

		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
			.container{
				width: 980px;
			}

			.logo-small {
				max-width: 20px;
				max-height: 20px;
			}

		</style>
		<link rel="stylesheet" href="../assets/css/bootstrap-responsive.css">

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
							<?php if ( isset( $this -> menu -> nav ) ): ?>
							<?php foreach( $this -> menu -> nav as $name => $href ): ?>
							<li class = "<?php if( $name == $this -> menu -> nav_active ) echo "active" ?>">
							<a href= "<?php echo $href ?>"><?php echo $name ?></a>
							</li>
							<?php endforeach; ?>
							<?php endif; ?>
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
				<div class="container-fluid" style ='background-color: rgba(0, 0, 0, 0.45)'>
					<div class="nav-collapse">
						<ul class="nav">
							<?php foreach( $this -> menu -> sec_nav as $name => $href): ?>
							<li class ="<?php if( $name == $this -> menu -> sec_nav_active ) echo 'active';?>">
							<a href="<?php echo $href ?>"><?php echo $name ?></a>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<div class="container">
			<div class="hero-unit">
				<?php require_once( SERVER_ROOT . DEFAULT_VIEW_PATH . $view . '.php'); ?>
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.container-->

	</body>
</html>

