<html lang="en"><head>

		<?php if( defined('BASE_HREF') ): ?>
		<base href = '<?php echo $data['config']['base_href'] ?>'>
		<?php endif; ?>

		<meta charset="utf-8"/>
		<title><?php echo $data['config']['site_name'] ?> - <?php echo ( isset($data['model']['title'] ) ) ? $data['model']['title']: $data['config']['site_tag']; ?> </title>

		<?php if( defined('META_DESCRIPTION') ): ?>
		<meta name="description" content="<?php echo $data['config']['meta_description']?>"/>
		<?php endif; ?>

		<?php if( defined('META_KEYWORDS') ): ?>
		<meta name="keywords" content="<?php echo $data['config']['meta_keywords'] ?>"/>
		<?php endif; ?>

		<meta name="author" content="">

		<?php if( isset( $this->helper ) ) echo $this->helper->loadCss() ; ?>
		<?php if( isset( $this->helper ) ) echo $this->helper->loadJs() ; ?>
		<?php if( isset( $this->analytics) )  echo ($this->analytics->track()); ?>

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
		<link href="" rel="shortcut icon">
		<link href="" rel="apple-touch-icon">
		<link href="" sizes="72x72" rel="apple-touch-icon">
		<link href="" sizes="114x114" rel="apple-touch-icon">
	</head>

	<body>

		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a data['config']-target=".nav-collapse" data['config']-toggle="collapse" class="btn btn-navbar">
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
							<?php echo $this->helper->menuLinks('nav') ?>
						</ul>
						<?php if ( $data['config']['logged_in'] ): ?>
						<p class="navbar-text pull-right">
						Logged in as <a href="user"><?php echo  $data['config']['username'] ?></a>
						</p>
						<?php endif; ?>
					</div><!--/.nav-collapse -->
				</div>
			</div>

			<?php if ( isset( $this->menu->sec_nav ) ): ?>
			<div class="navbar-inner"  >
				<div class="container-fluid sec-nav" >
					<div class="nav-collapse">
						<ul class="nav">
							<?php echo $this->helper->menuLinks('sec_nav') ?>
						</ul>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="container">
			<?php $this->renderLoaded( $data ); ?>
			<hr>

			<footer>
			<p>(c) <a href='<?php echo $data['config']['company_website']; ?>'><?php echo $data['config']['company'] . ' - ' . date("Y"); ?></a></p>
			</footer>
		</div> <!-- /container -->

</body></html>
