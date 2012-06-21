<?php

class Templates extends Scaffold
{

	public $undo_directory = true;

	public function initialize()
	{
		$this->root_path = GIMIMVC_ROOT . $this->config['apps_path'] . $this->name . '/views/';
		$path = $this->root_path . 'template/';
		$this->file( 'bootstrap-single', $path );
	}

	public function getContent()

	{

		$bootstrap_single = <<<bootstrap_single
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php if( isset( \$data['config']['base_href'] ) ): ?>
		<base href = '<?php echo \$data['config']['base_href'] ?>'>
		<?php endif; ?>

		<meta charset="utf-8"/>
		<title><?php echo \$data['config']['site_name'] ?> - <?php echo ( isset( \$data['model']['title'] ) ) ? \$data['model']['title'] : \$data['config']['site_tag']; ?> </title>

		<?php if( isset( \$data['config']['meta_description'] ) ): ?>
		<meta name="description" content="<?php echo \$data['config']['meta_description']?>"/>
		<?php endif; ?>

		<?php if( isset( \$data['config']['meta_keywords'] ) ): ?>
		<meta name="keywords" content="<?php echo \$data['config']['meta_keywords'] ?>"/>
		<?php endif; ?>

		<meta name="author" content="">

		<?php echo element::loadCSS( \$data['config']['default_css'] ); ?>
		<?php echo element::loadJS( \$data['config']['default_javascript'] ); ?>

		<style type="text/css">
			body {
				padding-top: 75px;
				padding-bottom: 40px;
			}
			.container{
				width: 1180px;
			}
			.sec-nav{
				 background-color: rgba(0, 0, 0, 0.45);
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
					<?php if ( isset( \$data['config']['default_logo_path'] ) ): ?>
					<img class="logo-small brand" src="<?php echo \$data['config']['default_logo_path']?>"/>
					<?php endif; ?>
					<a class="brand" href=""><?php echo \$data['config']['site_name']; ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<li> <a href='about'>About</a></li>
						</ul>
						<?php if ( isset( \$data['session']['logged_in']) ): ?>
						<p class="navbar-text pull-right">
						Logged in as <a href="user"><?php echo  \$data['session']['username'] ?></a>
						</p>
						<?php endif; ?>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>


		<div class="container">
			<div class="hero-unit">
				<?php \$this->renderLoaded( \$data ); ?>
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo \$data['config']['company_website']; ?>'><?php echo \$data['config']['company'] . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.container-->

	</body>
</html>

bootstrap_single;

		return $bootstrap_single;
	}

}

?>
