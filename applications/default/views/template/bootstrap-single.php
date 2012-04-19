<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ( load::path( 'shared', 'header') ); ?>

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
		<?php include ( load::path( 'shared', 'nav') ); ?>

		<div class="container">
			<div class="hero-unit">

				<?php if ( isset( $this -> module('base/menu') -> menus['breadcrumb'] ) ): ?>
				<ul class="breadcrumb">
					<?php echo $this -> module('base/menu') -> display('breadcrumb') ?>
				</ul>
				<br/>
				<?php endif; ?>

				<?php require_once( $this -> loaded['view']['main']['path'] ); ?>
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.container-->

	</body>
</html>

