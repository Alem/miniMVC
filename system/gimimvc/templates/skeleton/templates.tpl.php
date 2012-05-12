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

		<?php if( defined('BASE_HREF') ): ?>
		<base href = '<?php echo BASE_HREF ?>'>
		<?php endif; ?>

		<meta charset="utf-8"/>
		<title><?php echo SITE_NAME ?> - <?php echo ( isset( \$model -> title ) ) ? \$model -> title : SITE_TAG; ?> </title> 

		<?php if( defined('META_DESCRIPTION') ): ?>
		<meta name="description" content="<?php echo META_DESCRIPTION?>"/>
		<?php endif; ?>

		<?php if( defined('META_KEYWORDS') ): ?>
		<meta name="keywords" content="<?php echo META_KEYWORDS ?>"/>
		<?php endif; ?>

		<meta name="author" content="">

		<?php echo element::loadCSS(); ?>
		<?php echo element::loadJS(); ?>

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
					<?php if ( defined('DEFAULT_LOGO_PATH') ): ?>
					<img class="logo-small brand" src="<?php echo DEFAULT_LOGO_PATH?>"/>
					<?php endif; ?>
					<a class="brand" href="<?php echo DEFAULT_CONTROLLER?>"><?php echo SITE_NAME; ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<li> <a href='main/about'>About</a></li>
						</ul>
						<?php if ( Session::get('logged_in') ): ?>
						<p class="navbar-text pull-right">
						Logged in as <a href="user"><?php echo  Session::get('username') ?></a>
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
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.container-->

	</body>
</html>
bootstrap_single;

		return $bootstrap_single;
	}


}

?>
