<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include ( load::path( 'shared', 'header') ); ?>

		<style type="text/css">
			body {
				padding-top: 75px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}
			.sec-nav{
				 background-color: rgba(0, 0, 0, 0.45);
			}
			.logo-small {
				max-width: 20px;
				max-height: 20px;
			}
				
		</style>

		<!-- Le fav and touch icons -->
		<link href="images/favicon.ico" rel="shortcut icon">
		<link href="images/apple-touch-icon.png" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
		<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
	</head>

	<body>

		<?php include ( load::path( 'shared', 'nav') ); ?>

		<div class="container-fluid">
			<div class="row-fluid">
				<?php if((isset($this -> module('base/menu') -> menus['sidebar']) )): ?>
				<div class="span2">
					<br/>
					<div class="well sidebar-nav">
						<ul class="nav nav-list">
						<li class="nav-header"> Navigation </li>
							<?php echo $this -> module('base/menu') -> display('sidebar') ?>
						</ul>
					</div><!--/.well -->
				</div><!--/span-->
				<? endif;?>
				<div class="<?php echo ((isset($this -> module('base/menu') -> menus['sidebar']) )) ? 'span9' : 'span12' ?>"> 
					<div class="hero-unit">

						<?php if ( isset( $this -> module('base/menu') -> menus['breadcrumb'] ) ): ?>
						<ul class="breadcrumb">
							<?php echo $this -> module('base/menu') -> display('breadcrumb') ?>
						</ul>
						<? endif;?>

						<br/>
						<?php require_once( $this -> loaded['view']['main']['path'] ); ?>
					</div><!--/row-->
				</div><!--/span-->
			</div><!--/row-->

			<hr>
			<footer>
			<p>(c) <a href='<?php echo COMPANY_WEBSITE; ?>'><?php echo COMPANY . ' - ' . date("Y"); ?></a></p>
			</footer>

		</div><!--/.fluid-container-->
	</body>
</html>
