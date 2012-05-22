<?php


class Templates extends Template
{


	public function __construct( $name )
	{
		parent::__construct( $name );
		$this -> root_path = GIMIMVC_ROOT . 'applications/' . $this -> name . '/views/';
		$this -> fileCache() -> path = $this -> root_path . 'template/';
		$this -> fileCache() -> id = 'bootstrap-single';
	}

	public function generate()
	{
		mkdir( $this -> fileCache() -> path , 0777 , true );
		parent::generate();
	}

	public function undo()
	{
		parent::undo();
		rmdir( $this -> fileCache() -> path );
	}

	public function scaffold()
	{

		$bootstrap_single = <<<bootstrap_single
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php if( isset( \$data['base_href'] ) ): ?>
		<base href = '<?php echo \$data['base_href'] ?>'>
		<?php endif; ?>

		<meta charset="utf-8"/>
		<title><?php echo \$data['site_name'] ?> - <?php echo ( isset( \$data['title'] ) ) ? \$data['title'] : \$data['site_tag']; ?> </title> 

		<?php if( isset( \$data['meta_description'] ) ): ?>
		<meta name="description" content="<?php echo \$data['meta_description']?>"/>
		<?php endif; ?>

		<?php if( isset( \$data['meta_keywords'] ) ): ?>
		<meta name="keywords" content="<?php echo \$data['meta_keywords'] ?>"/>
		<?php endif; ?>

		<meta name="author" content="">

		<?php echo element::loadCSS( \$data['default_css'] ); ?>
		<?php echo element::loadJS( \$data['default_javascript'] ); ?>

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
					<?php if ( isset( \$data['default_logo_path'] ) ): ?>
					<img class="logo-small brand" src="<?php echo \$data['default_logo_path']?>"/>
					<?php endif; ?>
					<a class="brand" href=""><?php echo \$data['site_name']; ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<li> <a href='about'>About</a></li>
						</ul>
						<?php if ( isset( \$data['logged_in']) ): ?>
						<p class="navbar-text pull-right">
						Logged in as <a href="user"><?php echo  \$data['username'] ?></a>
						</p>
						<?php endif; ?>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>


		<div class="container">
			<div class="hero-unit">
				<?php require_once( \$this -> loaded['view']['main']['path'] ); ?>
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo \$data['company_website']; ?>'><?php echo \$data['company'] . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.container-->

	</body>
</html>

bootstrap_single;

		return $bootstrap_single;
	}


}

?>
